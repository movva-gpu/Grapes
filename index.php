<?php declare(strict_types = 1);

if (session_status() === PHP_SESSION_ACTIVE) {
    \Safe\session_abort();
}

error_reporting(E_ALL);

const __ROOT__ = __DIR__;

require_once __ROOT__ . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'utils.inc.php';
require_once rootPath('conf/config.inc.php');
include rootPath('vendor/autoload.php');

require_once rootPath('conf/router.inc.php');

echo array_key_exists('error', $_SESSION);
