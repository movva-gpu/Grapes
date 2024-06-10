<?php

function db_connect(): PDO|false
{
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
        \Safe\error_log($err);
        return false;
    }
}

function get_users_by_email(string $email, ?PDO $db = null): array|false
{
    if (is_null($db)) $db = db_connect();
    if ($db === false) return false;
    
    try
    {
        $email = $db->quote($email);

        $stmt = $db->prepare('SELECT * FROM users WHERE user_email = :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $err)
    {
        \Safe\error_log($err);
        return false;
    }
}
