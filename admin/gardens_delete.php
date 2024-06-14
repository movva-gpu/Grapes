<?php

declare(strict_types = 1);

if (session_status() === PHP_SESSION_ACTIVE)
{
    session_abort();
}

error_reporting(E_ALL);

const __ROOT__ = __DIR__ . DIRECTORY_SEPARATOR . '..';

require_once __ROOT__ . DIRECTORY_SEPARATOR .
    'utils' . DIRECTORY_SEPARATOR .
    'autoload.php';

require_once root_path('conf/config.inc.php');
include root_path('vendor/autoload.php');

garden_by_id(intval($_GET['id']), action: DBActions::DELETE);

header('Location: /gestion/gardens.php');
exit;

