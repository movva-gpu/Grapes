<?php
use PHPMailer\PHPMailer\PHPMailer;

function claim(): void
{
    if (!isset($GLOBALS['user']))
    {
        set_session_error(ErrorTypes::NOT_LOGGED_IN, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    if (!isset($_GET['garden_id']))
    {
        set_session_error(ErrorTypes::WENT_WRONG, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    $user_id      = $GLOBALS['user']['user_id'];
    $garden       = garden_by_id($_GET['garden_id']);
    $garden_id    = $garden['garden_id'];
    $garden_owner = user_by_id($garden['_user_id']);

    if ($garden === false)
    {
        set_session_error(ErrorTypes::WENT_WRONG, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    $db = db_connect();

    $current_reservation = "$user_id:0";

    $reserving = null;

    $users_reserving = explode(',', $garden['_user_reserving']);

    if (count($users_reserving) > intval($garden['garden_n_plots']))
    {
        set_session_error(ErrorTypes::TOO_MANY_PLOTS, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    $users_reserving = array_map(
        fn (string $user_reserving) => explode(':', $user_reserving),
        $users_reserving ?? []
    );

    $current_user_reserving = array_filter(
        $users_reserving,
        function (array $user_reserving) {
            return $user_reserving[0] == $GLOBALS['user']['user_id'] && $user_reserving[1] == 0;
        }
    );

    
    $is_current_user_reserving = !empty($current_user_reserving);

    if ($is_current_user_reserving)
    {
        set_session_error(ErrorTypes::ALREADY_RESERVING, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    if (is_null($garden['_user_reserving'] || empty($garden['_user_reserving'])))
    {
        $reserving = $current_reservation;
    } else
    {
        $reserving .= ",$current_reservation";
    }

    try
    {
        $stmt = $db->prepare('UPDATE `gardens` SET `_user_reserving` = :reservations WHERE `gardens`.`garden_id` = :garden_id');
        $stmt->bindParam(':reservations', $reserving);
        $stmt->bindParam(':garden_id', $garden['garden_id']);

        $stmt->execute();
    } catch (PDOException $err)
    {
        error_log($err);
        set_session_error(ErrorTypes::SQL_ERROR, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    $garden = garden_by_id($_GET['garden_id']);

    $garden_owner_mail     = $garden_owner['user_email'];
    $garden_owner_fullname = $garden_owner['user_first_name'] . ' ' . $garden_owner['user_last_name'];
    $user_fullname         = $GLOBALS['user']['user_first_name'] . ' ' . $GLOBALS['user']['user_last_name'];
    $garden_name           = $garden['garden_name'];
    $uuid                  = encrypt($GLOBALS['user']['user_uuid']);
    $accepting_link        = SITE_URL . '/plots/accept?garden_id=' . $garden['garden_id'] . '&user_uuid=' . $uuid;

    if(
        !send_mail(new PHPMailer(true),
            $garden_owner_mail,
            $garden_owner_fullname,
            'Réservation de parcelle',
            "$user_fullname demande à réserver une parcelle dans votre jardin $garden_name.<br>" .
            "<a href=\"$accepting_link\">" ."Accepter la demande</a>",
            "$user_fullname demande à réserver une parcelle dans votre jardin $garden_name.\r\n" .
            "Accepter la demande : $accepting_link"
        )
    )
    {
        set_session_error(ErrorTypes::WENT_WRONG, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    header('Location: /gardens?success=plots');
    exit;
}

function accept(): void {
    if (!isset($GLOBALS['user']))
    {
        set_session_error(ErrorTypes::NOT_LOGGED_IN, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    if (!isset($_GET['garden_id']))
    {
        set_session_error(ErrorTypes::WENT_WRONG, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    if (!isset($_GET['user_uuid']))
    {
        set_session_error(ErrorTypes::WENT_WRONG, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    $uuid = decrypt($_GET['user_uuid']);
    $user = user_by_uuid($uuid);
    $garden = garden_by_id($_GET['garden_id']);
    
    if ($garden === false)
    {
        set_session_error(ErrorTypes::WENT_WRONG, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    if ($user === false)
    {
        set_session_error(ErrorTypes::WENT_WRONG, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    $users_reserving = explode(',', $garden['_user_reserving']);

    $user_id = $user['user_id'];

    $new_users_reserving = array_map(function ($value) use ($user_id) {
        $parts = explode(':', $value);
        if ($parts[0] == $user_id) {
            $parts[1] = '1';
        }
        return implode(':', $parts);
    }, $users_reserving);

    $users_reserving_str = implode(':', $users_reserving);

    $db = db_connect();

    try
    {
        $stmt = $db->prepare('UPDATE `gardens` SET `_user_reserving` = :reservations WHERE `gardens`.`garden_id` = :garden_id');
        $stmt->bindParam(':reservations', $users_reserving_str);
        $stmt->bindParam(':garden_id', $garden['garden_id']);

        $stmt->execute();
    } catch (PDOException $err)
    {
        error_log($err);
        set_session_error(ErrorTypes::SQL_ERROR, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    $user_mail = $user['user_email'];
    $user_fullname = $user['user_first_name'] . ' ' . $user['user_last_name'];

    if(
        !send_mail(new PHPMailer(true),
            $user_mail,
            $user_fullname,
            'Réservation de parcelle',
            'Votre demande de réservation de parcelle a été acceptée',
            'Votre demande de réservation de parcelle a été acceptée'
        )
    )
    {
        set_session_error(ErrorTypes::WENT_WRONG, line: __LINE__);
        header('Location: /gardens');
        exit;
    }

    header('Location: /gardens?success=accept');
    exit;
}
