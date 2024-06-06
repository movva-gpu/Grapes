<?php declare(strict_types=1);

const __ROOT__ = __DIR__;

require_once __ROOT__ . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'utils.inc.php';
require_once rootPath('conf/config.inc.php');
include rootPath('vendor/autoload.php');

require_once rootPath('conf/router.inc.php');

session_abort();
