<?php

function index(): void
{
    header('Location: /profile/about');
    exit;
}

function apropos(): void
{
    about();
}

function about(): void
{
    if (!isset($GLOBALS['user']))
    {
        set_session_error(ErrorTypes::NOT_LOGGED_IN);
        header('Location: /login');
        exit;
    }

    if (isset($_GET['id']))
    {
        $tmp = user_by_id($_GET['id']);
        if ($tmp === false)
        {
            header('Location: /profil/404');
            exit;
        }

        $GLOBALS['get_user'] = $tmp;
        unset($tmp);
    }

    $page_title = 'Profil';
    $view_name = 'profile/About';

    require views_path('templates/Main_template.php');
}

function edit(): void
{

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST))
    {
        header('Location: /400');
        exit;
    }

    $uuid = decrypt($_POST['uuid']);
    $user = user_by_uuid($uuid);

    if (!$user)
    {
        set_session_error(ErrorTypes::SQL_ERROR);
        header('Location: /profile/about');
        exit;
    }

    $queue_path = root_path('queue.txt');
    $queue_file = \Safe\file_get_contents($queue_path);
    $queue      = explode(PHP_EOL, $queue_file);

    if (!empty($_FILES) && !empty($_FILES['pfp']['name']))
    {
        $tmp_path  = $_FILES['pfp']['tmp_name'];
        $path_info = pathinfo($_FILES['pfp']['name']);

        $new_file_name    = 'pfp' . '_' . \Safe\date('Ymd_Gis') . '.' . $path_info['extension'];
        $new_file_name_db = 'pfp' . '_' . \Safe\date('Ymd_Gis');
        $new_file_path    = root_path('assets/uploads/' . $new_file_name);

        if (move_uploaded_file($tmp_path, $new_file_path))
        {
            $queue[] = $new_file_path;

            $db = db_connect();
            $stmt = $db->prepare('UPDATE `users` SET `user_profile_picture_filename` = :pfp_path WHERE `users`.`user_id` = :id');
            $stmt->bindParam(':pfp_path', $new_file_name_db);
            $stmt->bindParam(':id', $user['user_id'], PDO::PARAM_INT);

            try
            {
                $stmt->execute();
            } catch (PDOException $e)
            {
                \Safe\error_log('Something went wrong with SQL:' . "\r\n" . $e);
                set_session_error(ErrorTypes::SQL_ERROR);
                header('Location: /profile/about');
                exit;
            }

            \Safe\file_put_contents($queue_path, implode(PHP_EOL, $queue));

            foreach (\Safe\glob(root_path('assets/pfp/' . $user['user_profile_picture_filename'] . '*')) as $file) {
                if (explode('_', $file)[0] === '000') continue;
                if (explode('_', $file)[0] === '001') continue;
                \Safe\unlink($file);
            }

            echo '👍';
        } else
        {
            set_session_error(ErrorTypes::SQL_ERROR);
            header('Location: /profile/about');
            exit;
        }
    }

    $EXPECTED_FIELDS = [
        'last-name' => [
            'max_length' => 50
        ],
        'first-name' => [
            'max_length' => 50
        ],
        'nickname' => [
            'max_length' => 42
        ],
        'pronouns' => [
            'max_length' => 40
        ]
    ];

    foreach ($EXPECTED_FIELDS as $field => $field_req)
    {
        if (strlen($_POST[$field]) > $field_req['max_length'])
        {
            set_session_error(ErrorTypes::FIELD_TOO_LONG, $field);
            header('Location: /register');
            exit;
        }
    }

    $name    = htmlentities($_POST['last-name']);
    $fname   = htmlentities($_POST['first-name']);

    if($_POST['display'] === 'on')
    {
        $display = DisplayName::NICKNAME->value;
    } elseif(!isset($_POST['display']))
    {
        $display = DisplayName::REAL_NAME->value;
    } else
    {
        set_session_error(ErrorTypes::BAD_FORMAT, $field);
        header('Location: /register');
        exit;
    }
    
    $nick = null;
    if (!empty($_POST['nickname']) || isset($_POST['nickname']))
    {
        $nick = htmlentities($_POST['nickname']);
    }

    $pronouns = null;
    if (!empty($_POST['pronouns']) || isset($_POST['pronouns']))
    {
        $pronouns = htmlentities($_POST['pronouns']);
    }

    $db = db_connect();

    try
    {
        $stmt = $db->prepare(
            'UPDATE `users`
            SET
                `user_last_name`    = :last_name,
                `user_first_name`   = :first_name,
                `user_nickname`     = :nickname,
                `user_pronouns`     = :pronouns,
                `user_display_name` = :display_name
            WHERE `user_id` = :id');

        $stmt->bindParam(':last_name',    $name,                PDO::PARAM_STR);
        $stmt->bindParam(':first_name',   $fname,               PDO::PARAM_STR);
        $stmt->bindParam(':nickname',     $nick,                PDO::PARAM_STR);
        $stmt->bindParam(':pronouns',     $pronouns,            PDO::PARAM_STR);
        $stmt->bindParam(':display_name', $display,             PDO::PARAM_INT);
        $stmt->bindParam(':id',           $user['user_id'], PDO::PARAM_INT);

        $stmt->execute();

    } catch (PDOException $err)
    {
        \Safe\error_log($err);
        set_session_error(ErrorTypes::SQL_ERROR);
        header('Location: /profile/about');
        exit;
    }

    header('Location: /profile/about');
    exit;
}
