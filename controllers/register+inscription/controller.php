<?php

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\RFCValidation;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function index(): void
{
    $view_name = 'Register';
    $page_title = 'Inscription';

    require views_path('templates/Main_template.php');
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

    foreach ($EXPECTED_FIELDS as $field => $field_req)
    {
        if (!isset($_POST[$field]) && $field_req['required'])
        {
            set_session_error(ErrorTypes::MISSING_FIELD, $field);
            header('Location: /register');
            exit;
        } else if (strlen($_POST[$field]) > $field_req['max_length'])
        {
            set_session_error(ErrorTypes::FIELD_TOO_LONG, $field);
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
        set_session_error(ErrorTypes::REPEATED_PASSWORD_NOT_EQUALS);
        header('Location: /register');
        exit;
    }

    $email_validator = new EmailValidator();

    if(!$email_validator->isValid($email, new RFCValidation()))
    {
        set_session_error(ErrorTypes::BAD_EMAIL_FORMAT);
        header('Location: /register');
        exit;
    }

    if(!$email_validator->isValid($email, new DNSCheckValidation()))
    {
        set_session_error(ErrorTypes::EMAIL_DOES_NOT_EXIST);
        header('Location: /register');
        exit;
    }

    /** @var PDO $db */
    $db = db_connect();

    if (!$db)
    {
        set_session_error(ErrorTypes::SQL_ERROR);
        header('Location: /register');
        exit;
    }

    $users_with_same_email = get_users_by_email($email, $db);

    if ($users_with_same_email === false)
    {
        set_session_error(ErrorTypes::SQL_ERROR);
        header('Location: /register');
        exit;
    }

    if (!empty($users_with_same_email))
    {
        set_session_error(ErrorTypes::ACCOUNT_WITH_EMAIL_ALREADY_EXISTS);
        header('Location: /register');
        exit;
    }

    $token = bin2hex(random_bytes(30));

    try
    {
        $stmt = $db->prepare(
            'INSERT INTO `users`(
                `user_name`,
                `user_first_name`,
                `user_nickname`,
                `user_password`,
                `user_email`,
                `user_gender`,
                `user_validation_token`
            ) VALUES (
                :lname, :fname, :nick, :passwd, :email, :gender, :token
            )');
        $stmt->bindParam(':lname', $name);
        $stmt->bindParam(':fname', $fname);

        if ($nick === null)
        {
            $stmt->bindParam(':nick', $nick, PDO::PARAM_NULL);
        } else
        {
            $stmt->bindParam(':nick', $nick);
        }

        $password_hash = password_hash($passwd, PASSWORD_BCRYPT);

        $stmt->bindParam(':passwd', $password_hash);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':token', $token);

        $stmt->execute();
    } catch (PDOException $err)
    {
        \Safe\error_log($err);
        set_session_error(ErrorTypes::SQL_ERROR);
        header('Location: /register');
        exit;
    }

    $mailer = new PHPMailer(true);
    $verification_url = SITE_URL . 'validate/account?token=' . urlencode($token);

    \Safe\session_abort();
}

function send_verification_mail(PHPMailer $mailer, string $email, string $fname, string $name, string $verification_url)
{
    try
    {
        $mailer->SMTPDebug = SMTP::DEBUG_SERVER;
        $mailer->isSMTP();
        $mailer->Host       = SMTP_SERVER;
        $mailer->SMTPAuth   = true;
        $mailer->Username   = EMAIL_ADDRESS;
        $mailer->Password   = EMAIL_PASSWORD;
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mailer->Port       = 465;

        $mailer->setFrom('no-reply@test.local');
        $mailer->addAddress($email, $fname . ' ' . $name);

        $mailer->isHTML(true);
        $mailer->Subject = 'Grapes 🍇 - Mail de vérification';
        $mailer->Body    =
            '<!doctype html>' . + "\r\n" .
            '<html lang="en">' . + "\r\n" .
            '<head>' . + "\r\n" .
                '<meta charset="UTF-8" />' . + "\r\n" .
                '<meta name="viewport" content="width=device-width, initial-scale=1.0" />' . + "\r\n" .
                '<title>Document</title>' . + "\r\n" .
                '<style>' . + "\r\n" .
                    'body{margin:0;font-family:system-ui, -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Open Sans\', \'Helvetica Neue\', sans-serif;font-size:125%;line-height:1.5;background-color:whitesmoke;min-height:100vh}h1,p{margin:0}h1{line-height:0.8;margin-bottom:0.33em}p{color:color-mix(in srgb, currentColor, transparent 20%)}a{color:#75A358;text-decoration:1px solid underline}main{padding:2em;background-color:#f8eccf;border-bottom:2px #c2b79f solid}footer{bottom:0;display:flex;align-items:center;justify-content:center;height:4em;height:2lh;font-style:italic}.start{margin-bottom:0.8em}.button{display:block;color:white;width:fit-content;padding:1em 2em;margin-bottom:0.5em;margin-inline:auto;background-color:#4B755F;outline:4px solid black;text-decoration:none;border-radius:1em}small{display:block;width:fit-content;margin-bottom:1em;margin-inline:auto;font-style:italic}.fname{font-weight:bold}' . + "\r\n" .
                '</style>' . + "\r\n" .
            '</head>' . + "\r\n" .
            '<body>' . + "\r\n" .
                '<main>' . + "\r\n" .
                    '<h1>Mail de vérification</h1>' . + "\r\n" .
                    '<p class="start">' . + "\r\n" .
                        'Hey <span class="fname">' . $fname . '</span> ! Pour finaliser votre inscription vous devez valider votre' . + "\r\n" .
                        'inscription en appuyant sur le bouton suivant :' . + "\r\n" .
                    '</p>' . + "\r\n" .
                    '<a class="button" href="' . $verification_url . '">Valider mon inscription</a>' . + "\r\n" .
                    '<small>' . + "\r\n" .
                        'Ou en cliquant sur le lien suivant&nbsp;: ' . + "\r\n" .
                        '<a href="' . $verification_url . '">' . $verification_url . '</a>' . + "\r\n" .
                    '</small>' . + "\r\n" .
                    '<p>' . + "\r\n" .
                        'Si quelque chose d\'innatendu se produit, veuillez' . + "\r\n" .
                        '<a href="' . SITE_URL . '/contact' . '">nous contacter</a>' . + "\r\n" .
                        'dans les plus brefs délais.' . + "\r\n" .
                    '</p>' . + "\r\n" .
                '</main>' . + "\r\n" .
                '<footer>L\'équipe de Grapes</footer>' . + "\r\n" .
            '</body>' .
            '</html>';
        $mailer->AltBody = 'Hey ' . $fname . ' ! Pour finaliser votre inscription vous devez valider votre inscription en appuyant sur le lien suivant :' . $verification_url . ".\r\n" .
            'Si quelque chose d\'innatendu se produit, veuillez nous contacter ici → ' .  SITE_URL . '/contact';

        $mailer->send();
        header('Location /inscription/validation');
    } catch (Exception $err)
    {
        set_session_error(ErrorTypes::VERIFICATION_MAIL_NOT_SENT);
    }
}
