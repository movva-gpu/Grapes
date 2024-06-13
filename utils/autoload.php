<?php

$utility_files = glob(__ROOT__ . DIRECTORY_SEPARATOR . 'utils/*.php');

foreach ($utility_files as $utility_file)
{
    require_once $utility_file;
}
