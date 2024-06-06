<?php

function index(): void
{
    http_response_code(404);
    $GLOBALS['404'] = true;

    require_once viewsPath('errors/404.php');
}
