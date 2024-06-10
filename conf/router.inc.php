<?php

$path = parse_url_path($_SERVER['REQUEST_URI']);

$path_parts = explode('/', $path);

$page_name = empty($path_parts[1]) ? 'home' : $path_parts[1];
$action = empty($path_parts[2]) ? 'index' : $path_parts[2];

$controller_dir = find_controller_directory($page_name);

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
