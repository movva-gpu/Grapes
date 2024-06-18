<?php
use PHPMailer\PHPMailer\PHPMailer;

function index(): void {
    $view_name = 'Contact';
    $page_title = 'Nous contacter';

    require views_path('templates/Main_template.php');
}

function send(): void {
    session_start();

    $EXPECTED_FIELDS = [
        'first-name' => [
            'required' => true,
            'max_length' => 50
        ],
        'last-name' => [
            'required' => true,
            'max_length' => 50
        ],
        'email' => [
            'required' => true,
            'max_length' => 128
        ],
        'subject' => [
            'required' => true,
            'max_length' => 42
        ],
        'message' => [
            'required' => true,
            'max_length' => 1024
        ]
    ];

    foreach ($EXPECTED_FIELDS as $field => $field_req)
    {
        if (!isset($_GET[$field]) && $field_req['required'])
        {
            set_session_error(ErrorTypes::MISSING_FIELD, $field, __LINE__);
            header('Location: /contact');
            exit;
        } else if (strlen($_GET[$field]) > $field_req['max_length'])
        {
            set_session_error(ErrorTypes::FIELD_TOO_LONG, $field, __LINE__);
            header('Location: /contact');
            exit;
        }
    }

    $first_name         = $_GET['first-name'];

    $last_name         = $_GET['last-name'];

    $email = $_GET['email'];

    $subject         = $_GET['subject'];
    
    $message         = $_GET['message'];
    $message_encoded = htmlentities($message);

    $full_name         = "$first_name $last_name";

    if(
        !send_mail(new PHPMailer(true),
            EMAIL_ADDRESS,
            'Me',
            "Mail de $full_name : $subject",
            $message_encoded,
            $message,
            [$email, $full_name],
        )
    )
    {
        set_session_error(ErrorTypes::MAIL_NOT_SENT, line: __LINE__);
        header('Location: /contact');
        exit;
    }

    if(
        !send_mail(new PHPMailer(true),
            $email,
            $full_name,
            'Mail de confirmation',
            'Votre mail nous a bien été envoyé !<br>' .
                'Nous vous recontacterons dans les plus brefs délais',
            'Votre mail nous a bien été envoyé !' . "\r\n" .
                'Nous vous recontacterons dans les plus brefs délais'
        )
    )
    {
        set_session_error(ErrorTypes::MAIL_NOT_SENT, line: __LINE__);
        header('Location: /contact');
        exit;
    }

    header('Location: /contact?success');
    exit;
}