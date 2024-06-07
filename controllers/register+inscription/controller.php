<?php

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\RFCValidation;

function index(): void
{
    $view_name = 'Register';
    $page_title = 'Inscription';

    require viewsPath('templates/Main_template.php');
}

function validate(): void
{
    session_start();

    $EXPECTED_FIELDS = [
        'name' => [
            'required' => true,
            'max_length' => 50
        ],
        'fname' => [
            'required' => true,
            'max_length' => 50
        ],
        'nick' => [
            'required' => false,
            'max_length' => 42
        ],
        'gender' => [
            'required' => true,
            'max_length' => 2
        ],
        'mail' => [
            'required' => true,
            'max_length' => 128
        ],
        'passwd' => [
            'required' => true,
            'max_length' => 72
        ],
        'passwd-rep' => [
            'required' => true,
            'max_length' => 72
        ],
    ];

    $fields = [];

    foreach ($EXPECTED_FIELDS as $field => $field_req)
    {
        if (!isset($_POST[$field]) && $field_req['required'])
        {
            $_SESSION['error'] = [
                'error' => ['message' => $field, 'code' => ErrorTypesEnum::MissingField],
                'expected_fields' => $EXPECTED_FIELDS
            ];
            \Safe\session_write_close();
            header('Location: /register');
            exit;
        } else if (strlen($_POST[$field]) > $field_req['max_length'])
        {
            $_SESSION['error'] = [
                'error' => ['message' => $field, 'code' => ErrorTypesEnum::TooLongField],
                'expected_fields' => $EXPECTED_FIELDS
            ];
            \Safe\session_write_close();
            header('Location: /register');
            exit;
        }
    }

    $name       = htmlentities($_POST['name']);
    $fname      = htmlentities($_POST['fname']);
    $nick       = empty($_POST['nick']) || !isset($_POST['nick']) ?
                    htmlentities($_POST['nick']) : null;
    $gender     = htmlentities($_POST['gender']);
    $email      = htmlentities($_POST['mail']);
    $passwd     = htmlentities($_POST['passwd']);
    $passwd_rep = htmlentities($_POST['passwd-rep']);

    if ($passwd !== $passwd_rep)
    {
        $_SESSION['error'] = [
            'error' => ['code' => ErrorTypesEnum::RepeatPasswordNotEqual],
            'expected_fields' => $EXPECTED_FIELDS
        ];
        \Safe\session_write_close();
        header('Location: /register');
        exit;
    }

    $email_validator = new EmailValidator();

    if(!$email_validator->isValid($email, new RFCValidation()))
    {
        $_SESSION['error'] = [
            'error' => ['code' => ErrorTypesEnum::BadEmailFormat],
            'expected_fields' => $EXPECTED_FIELDS
        ];
        \Safe\session_write_close();
        header('Location: /register');
        exit;
    }

    if(!$email_validator->isValid($email, new DNSCheckValidation()))
    {
        $_SESSION['error'] = [
            'error' => ['code' => ErrorTypesEnum::EmailDoesNotExist],
            'expected_fields' => $EXPECTED_FIELDS
        ];
        \Safe\session_write_close();
        header('Location: /register');
        exit;
    }

    /** @var PDO $db */
    $db = include rootPath('config/db.inc.php');

    \Safe\session_abort();
}
