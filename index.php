<?php declare(strict_types = 1);

if (session_status() === PHP_SESSION_ACTIVE)
{
    \Safe\session_abort();
}

error_reporting(E_ALL);

const __ROOT__ = __DIR__;

require_once __ROOT__ . DIRECTORY_SEPARATOR .
    'utils' . DIRECTORY_SEPARATOR .
    'autoload.php';

require_once root_path('conf/config.inc.php');
include root_path('vendor/autoload.php');

require_once root_path('conf/router.inc.php');

echo array_key_exists('error', $_SESSION);
