<?php

function index(): void
{
    http_response_code(404);
    $GLOBALS['404'] = true;

    require_once views_path('errors/404.php');
}
