<?php

try
{
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=UTF8;',
        DB_USER,
        DB_PWD
    );
    $db->query('SET NAMES utf8;');

    return $db;
} catch (PDOException $err)
{
    throw $err;
}
