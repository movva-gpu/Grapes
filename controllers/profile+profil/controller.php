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

    if (!empty($_FILES))
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
                \Safe\unlink($file);
            }

            echo '👍';
            header('Location: /profile/about');
        } else
        {
            echo '👎';
            header('Location: /profile/about');
        }
    }
}
