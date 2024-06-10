<?php

/**
 * @param string $path Please use `/` not `\\`
 * @return string If on windows, `folder/file` will be turned into `folder\file`
 */
function separatesPath(string $path): string
{
    return implode(DIRECTORY_SEPARATOR, explode('/', $path));
}

/**
 * @param string $path Path to a file in the root dir.
 * @return string Absolute path to that file. (even if it doesn't exist).
 */
function rootPath(string $path): string
{
    return __ROOT__ . DIRECTORY_SEPARATOR . separatesPath($path);
}

/**
 * @param string $path Path to a file in the views' directory.
 * @return string Absolute path to that file. (even if it doesn't exist).
 */
function viewsPath(string $path): string
{
    return rootPath('views/' . $path);
}

/**
 * @param string $path Path to a file relative to the assets directory
 * @return string If a minified version of the file is found in dist/, then it outputs
 * (if it exists). Else, if it exists in dist/, then it outputs, else, it will take the one in the
 * assets/ directory.
 */
function getAsset(string $path): string
{
    $fileAssetsPath = 'assets/' . $path;

    $fileDistPath = 'dist/' . $path;

    $fileExtension = pathinfo($path, PATHINFO_EXTENSION);
    $fileNameOrPath = pathinfo($path, PATHINFO_FILENAME);
    $minifiedFilename = $fileNameOrPath . '.min.' . $fileExtension;
    $minifiedFilePath = 'dist/' . $minifiedFilename;

    if (file_exists(rootPath($minifiedFilePath))) {
        return SITE_URL . '/' . $minifiedFilePath;
    } elseif (file_exists(rootPath($fileDistPath))) {
        return SITE_URL . '/' . $fileDistPath;
    } elseif (file_exists(rootPath($fileAssetsPath))) {
        return SITE_URL . '/' . $fileAssetsPath;
    }

    throw new InvalidArgumentException('Asset ' . $path . ' not found.', 404);
}

function set_session_error(ErrorTypes $error_type, ?string $message = null, bool $close_session = true): bool|null {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (!!$message) $error = [ 'error' => ['message' => $message, 'type' => $error_type] ];
    else $error = ['error' => ['type' => $error_type]];

    $_SESSION['error'] = $error;
    if ($close_session) return session_write_close();

    return null;
}

function get_users_by_email(PDO $db, string $email): array|false
{
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

enum ErrorTypes
{
    case MISSING_FIELD;
    case FIELD_TOO_LONG;
    case REPEATED_PASSWORD_NOT_EQUALS;
    case BAD_EMAIL_FORMAT;
    case EMAIL_DOES_NOT_EXIST;
    case SQL_ERROR;
    case ACCOUNT_WITH_EMAIL_ALREADY_EXISTS;
    case VERIFICATION_MAIL_NOT_SENT;
}
