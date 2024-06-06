<?php

session_start();

/**
 * Parses the url and returns only its path
 * @param $url string
 * @return string
 */
function parse_url_path(string $url): string
{
    return parse_url($url)['path'];
}

$path = parse_url_path($_SERVER['REQUEST_URI']);

$path_parts = explode('/', $path);

$GLOBALS['page_name'] = empty($path_parts[1]) ? 'Home' : ucfirst($path_parts[1]);
$action = empty($path_parts[2]) ? 'index' : $path_parts[2];

require_once rootPath('controllers/' . $GLOBALS['page_name'] . '_controller.php');

$action();
