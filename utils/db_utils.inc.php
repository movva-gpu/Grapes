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

function get_users(?PDO $db = null): array|bool
{
    if (is_null($db)) $db = db_connect();
    if ($db === false) return false;

    try
    {
        $stmt = $db->prepare('SELECT * FROM `users`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === DBActions::DELETE) {
            $stmt = $db->prepare('DELETE FROM `users` WHERE user_id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }
    } catch (PDOException $err)
    {
        \Safe\error_log($err);
        return false;
    }
}

function user_by_uuid(string $uuid, ?PDO $db = null, DBActions $action = DBActions::SELECT): array|bool
{
    if (is_null($db)) $db = db_connect();
    if ($db === false) return false;

    try
    {
        if ($action === DBActions::SELECT)
        {
            $stmt = $db->prepare('SELECT * FROM `users` WHERE user_uuid = :uuid');
            $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === DBActions::DELETE) {
            $stmt = $db->prepare('DELETE FROM `users` WHERE user_uuid = :uuid');
            $stmt->bindParam(':uuid', $uuid, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }
    } catch (PDOException $err)
    {
        \Safe\error_log($err);
        return false;
    }
}

function user_by_nickname_or_mail(string $email_or_nickname, ?PDO $db = null, DBActions $action = DBActions::SELECT): array|bool
{
    if (is_null($db)) $db = db_connect();
    if ($db === false) return false;

    try
    {
        if ($action === DBActions::SELECT)
        {
            $stmt = $db->prepare('SELECT * FROM `users` WHERE user_email = :email_or_nick OR user_nickname = :email_or_nick');
            $stmt->bindParam(':email_or_nick', $email_or_nickname, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === DBActions::DELETE) {
            $stmt = $db->prepare('DELETE FROM `users` WHERE user_email = :email_or_nick OR user_nickname = :email_or_nick');
            $stmt->bindParam(':email_or_nick', $email_or_nickname, PDO::PARAM_STR);
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

function gardens_by_user_id(int $id, ?PDO $db = null, DBActions $action = DBActions::SELECT): array|bool
{
    if (is_null($db)) $db = db_connect();
    if ($db === false) return false;

    try
    {
        if ($action === DBActions::SELECT)
        {
            $stmt = $db->prepare('SELECT * FROM `gardens` WHERE _user_id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($action === DBActions::DELETE) {
            $stmt = $db->prepare('DELETE FROM `gardens` WHERE _user_id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }
    } catch (PDOException $err)
    {
        \Safe\error_log($err);
        return false;
    }
}

function get_gardens(?PDO $db = null): array|bool
{
    if (is_null($db)) $db = db_connect();
    if ($db === false) return false;

    try
    {
        $stmt = $db->prepare('SELECT * FROM `gardens`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $err)
    {
        \Safe\error_log($err);
        return false;
    }
}

function garden_by_id(int $id, ?PDO $db = null, DBActions $action = DBActions::SELECT): array|bool
{
    if (is_null($db)) $db = db_connect();
    if ($db === false) return false;

    try
    {
        if ($action === DBActions::SELECT)
        {
            $stmt = $db->prepare('SELECT * FROM `gardens` WHERE garden_id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } elseif ($action === DBActions::DELETE) {
            $stmt = $db->prepare('DELETE FROM `gardens` WHERE garden_id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }
    } catch (PDOException $err)
    {
        \Safe\error_log($err);
        return false;
    }
}

function encrypt(string $unencrypted_value): string
{
    $iv     = openssl_random_pseudo_bytes(openssl_cipher_iv_length(OPENSSL_METHOD));
    return base64_encode(
        openssl_encrypt($unencrypted_value, OPENSSL_METHOD, OPENSSL_SECRET, iv: $iv) .
        '::' .
        $iv
    );
}

function decrypt(string $encrypted_value): string {
    list($encrypted_data, $iv) = explode('::', \Safe\base64_decode($encrypted_value), 2);
    return \Safe\openssl_decrypt($encrypted_data, OPENSSL_METHOD, OPENSSL_SECRET, iv: $iv);
}

enum DBActions
{
    case DELETE;
    case SELECT;
}

enum DisplayName: int
{
    case REAL_NAME = 0;
    case NICKNAME  = 1;
}

enum UserGender: string
{
    case FEMALE       = 'f';
    case MALE         = 'm';
    case NON_BINARY   = 'nb';
    case NOT_PRECISED = 'no';
}
