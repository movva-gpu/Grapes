<?php

function index(): void
{
    return;
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

    print_r($_POST);
    print_r($_FILES);
    print_r($_FILES['error']);

    if (!empty($_FILES))
    {
        $tmp_path  = $_FILES['pfp']['tmp_name'];
        $path_info = pathinfo($_FILES['pfp']['name']);

        $new_file_name = root_path('assets/uploads/' . $path_info['filename'] . '_' . \Safe\date('Ymd_Gis') . '.' . $path_info['extension']);

        if (move_uploaded_file($tmp_path, $new_file_name))
        {
    

            $queue[] = $new_file_name;
            \Safe\file_put_contents($queue_path, implode(PHP_EOL, $queue));

            echo '👍';
        } else
        {
            echo '👎';
        }
    }
}
