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

function users_by_email(string $email, ?PDO $db = null, DBActions $action = DBActions::SELECT): array|bool
{
    if (is_null($db)) $db = db_connect();
    if ($db === false) return false;

    try
    {
        if ($action === DBActions::SELECT)
        {
            $stmt = $db->prepare('SELECT * FROM `users` WHERE user_email = :email');
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($action === DBActions::DELETE) {
            $stmt = $db->prepare('DELETE FROM `users` WHERE user_email = :email');
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        }
    } catch (PDOException $err)
    {
        \Safe\error_log($err);
        return false;
    }
}

function user_by_id(int $id, ?PDO $db = null, DBActions $action = DBActions::SELECT): array|bool
{
    if (is_null($db)) $db = db_connect();
    if ($db === false) return false;

    try
    {
        if ($action === DBActions::SELECT)
        {
            $stmt = $db->prepare('SELECT * FROM `users` WHERE user_id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === DBActions::DELETE) {
            $stmt = $db->prepare('DELETE FROM `users` WHERE user_id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        }
    } catch (PDOException $err)
    {
        \Safe\error_log($err);
        return false;
    }
}

function get_user_by_validation_token(string $token, ?PDO $db = null): array|false
{
    if (is_null($db)) $db = db_connect();
    if ($db === false) return false;

    try
    {
        $stmt = $db->prepare('SELECT * FROM `users` WHERE user_validation_token = :token');
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $err)
    {
        \Safe\error_log($err);
        return false;
    }
}

function validate_account_by_token(mixed $user, ?PDO $db = null): bool
{
    if (is_null($db)) $db = db_connect();
    if ($db === false) return false;

    try
    {
        $stmt_validated = $db->prepare('UPDATE `users` SET `user_validated` = TRUE WHERE `users`.`user_id` = :id');
        $stmt_validated->bindParam(':id', $user['user_id'], PDO::PARAM_STR);
        $stmt_validated->execute();

        $stmt_token = $db->prepare('UPDATE `users` SET `user_validation_token` = NULL WHERE `users`.`user_id` = :id');
        $stmt_token->bindParam(':id', $user['user_id'], PDO::PARAM_STR);
        $stmt_token->execute();
        return true;
    } catch (PDOException $err)
    {
        \Safe\error_log($err);
        return false;
    }
}

enum DBActions {
    case SELECT;
    case DELETE;
}
