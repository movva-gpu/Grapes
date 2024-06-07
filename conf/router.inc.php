<?php

/**
 * Parses the url and returns only its path
 * @param $url string
 * @return string
 */
function parse_url_path(string $url): string
{
    return parse_url($url)['path'];
}

/**
 * Finds the appropriate controller directory based on the page name
 * @param $pageName string
 * @return string|null
 */
function find_controller_directory(string $pageName): ?string
{
    $directories = glob(rootPath('controllers/*'), GLOB_ONLYDIR);

    foreach ($directories as $directory)
    {
        $dirname = basename($directory);

        foreach (explode('+', $dirname) as $route)
        {
            if (
                $route === $pageName &&
                file_exists(separatesPath($directory . '/controller.php'))
            )
            {
                $GLOBALS['current_dir'] = $dirname;
                return $directory;
            }
        }
    }

    header('Location: /404', true);
    exit();
}

$path = parse_url_path($_SERVER['REQUEST_URI']);

$path_parts = explode('/', $path);

$page_name = empty($path_parts[1]) ? 'home' : $path_parts[1];
$action = empty($path_parts[2]) ? 'index' : $path_parts[2];

$controller_dir = find_controller_directory($page_name);

require_once separatesPath($controller_dir . '/controller.php');

if (function_exists($action))
{
    $action();
} elseif (file_exists(separatesPath($controller_dir . '/' . $action . '/controller.php')))
{
    require_once separatesPath($controller_dir . '/' . $action . '/controller.php');
} else
{
    header('Location: /404', true);
    exit();
}
