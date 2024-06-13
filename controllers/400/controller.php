<?php

function index(): void
{
    http_response_code(400);
    $GLOBALS['400'] = true;

    require_once views_path('errors/400.php');
}
