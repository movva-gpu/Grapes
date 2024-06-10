<?php

function set_session_error(ErrorTypes $error_type, ?string $message = null, bool $close_session = true): bool|null
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (!!$message) $error = [ 'error' => ['message' => $message, 'type' => $error_type] ];
    else $error = ['error' => ['type' => $error_type]];

    $_SESSION['error'] = $error;
    if ($close_session) return session_write_close();

    return null;
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
