<?php

$path = parse_url_path($_SERVER['REQUEST_URI']);

$path_parts = explode('/', $path);

$page_name = empty($path_parts[1]) ? 'home' : $path_parts[1];
$action = empty($path_parts[2]) ? 'index' : $path_parts[2];

$controller_dir = find_controller_directory($page_name);

session_start();

if (isset($_SESSION['user_id']))
{

    $user = user_by_id($_SESSION['user_id']);
    
    if ($user === false || empty($user))
    {
        unset($_SESSION['user_id']);
        \Safe\session_write_close();
    }

    $GLOBALS['user'] = $user;
}

\Safe\session_abort();

require_once separate_path($controller_dir . '/controller.php');

if (function_exists($action))
{
    $action();
} elseif (file_exists(separate_path($controller_dir . '/' . $action . '/controller.php')))
{
    require_once separate_path($controller_dir . '/' . $action . '/controller.php');
} else
{
    header('Location: /404', true);
    exit;
}
