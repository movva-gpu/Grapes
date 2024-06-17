<?php

function set_session_error(ErrorTypes $error_type, ?string $message = null, bool $close_session = true, ?int $line = null): bool|null
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (!!$message) $error = [ 'error' => ['message' => $message, 'type' => $error_type] ];
    else $error = ['error' => ['type' => $error_type]];

    if (!is_null($line)) $error['line'] = $line;

    $_SESSION['error'] = $error;
    if ($close_session) return session_write_close();

    return null;
}

enum ErrorTypes
{
    /**
     * To use when a required field is not.
     * Please provide the field that needs to be added to the user.
     */
    case MISSING_FIELD;

    /**
     * To use when a provided field is too long from what's expected.
     * Please provide the field that needs to be corrected to the user.
     */
    case FIELD_TOO_LONG;

    /**
     * To use when registering or changing passwords if the two passwords are not equals.
     */
    case REPEATED_PASSWORD_NOT_EQUALS;
    
    /**
     * To use when a specified e-mail isn't corresponding to most regular formats.
     */
    case BAD_EMAIL_FORMAT;

    /**
     * To use when a specified e-mail doesn't exist (DNS Check).
     */
    case EMAIL_DOES_NOT_EXIST;

    /**
     * To use when a database error occurs.
     */
    case SQL_ERROR;

    /** 
     * To use when registering or changing user profile if there's an account
     * with the specified email that already exists.
     */
    case ACCOUNT_WITH_EMAIL_ALREADY_EXISTS;

    /** 
     * To use when registering or changing user profile if there's an account
     * with the specified email that already exists.
     */
    case ACCOUNT_WITH_NICKNAME_ALREADY_EXISTS;


    /** 
     * To use when a verification mail for any operation could not be sent.
     */
    case VERIFICATION_MAIL_NOT_SENT;

    /**
     * To use when a validation token isn't correct or doesn't exist.
     */
    case WRONG_TOKEN;

    /**
     * To use when a specified account doesn't exist in the database.
     */
    case ACCOUNT_DOES_NOT_EXIST;

    /**
     * To use when the specified password doesn't math the corresponding user.
     */
    case WRONG_PASSWORD;

    /**
     *  To use when an action is performed without being logged in.
     */
    case NOT_LOGGED_IN;

    case BAD_FORMAT;
}
