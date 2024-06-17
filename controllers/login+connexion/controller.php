<?php

use function Safe\phpcredits;

function index(): void
{
    if (isset($_SESSION['user_id']))
    {
        header('Location: /profile/about');
        exit;
    }

    $view_name = 'Login';
    $page_title = 'Connexion';

    require_once views_path('templates/Main_template.php');
}

function validate(): void
{
    if (isset($_SESSION['user_id']))
    {
        header('Location: /profile/about');
        exit;
    }

    session_start();

    $EXPECTED_FIELDS = [
        'email-or-nickname' => [
            'required' => true,
            'max_length' => 128
        ],
        'passwd' => [
            'required' => true,
            'max_length' => 72
        ]
    ];

    foreach ($EXPECTED_FIELDS as $field => $field_req)
    {
        if (!isset($_GET[$field]) && $field_req['required'])
        {
            set_session_error(ErrorTypes::MISSING_FIELD, $field, __LINE__);
            header('Location: /login');
            exit;
        } else if (strlen($_GET[$field]) > $field_req['max_length'])
        {
            set_session_error(ErrorTypes::FIELD_TOO_LONG, $field, __LINE__);
            header('Location: /login');
            exit;
        }
    }

    $email_or_nickname = $_GET['email-or-nickname'];
    $passwd            = $_GET['passwd'];

    $user = user_by_nickname_or_mail($email_or_nickname);

    if ($user === false)
    {
        set_session_error(ErrorTypes::SQL_ERROR, __LINE__);
        header('Location: /login');
        exit;
    }

    if (empty($user))
    {
        set_session_error(ErrorTypes::ACCOUNT_DOES_NOT_EXIST, __LINE__);
        header('Location: /login');
        exit;
    }

    if (!password_verify($passwd, $user['user_password_hash']))
    {
        set_session_error(ErrorTypes::WRONG_PASSWORD, __LINE__);
        header('Location: /login');
        exit;
    }

    $_SESSION['user_id'] = $user['user_id'];
    session_write_close();

    header('Location: /profile/about');
    exit;
}
