<?php

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\RFCValidation;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function index(): void
{
    if (isset($_SESSION['user_id']))
    {
        header('Location: /profile/about');
        exit;
    }

    $view_name = 'Register';
    $page_title = 'Inscription';

    require views_path('templates/Main_template.php');
}

function validate(): void
{
    if (isset($_SESSION['user_id']))
    {
        header('Location: /profile/about');
        exit;
    }

    if (!isset($_GET['token']))
    {
        validate_registration(true);
        return;
    } else
    {
        validate_account(true);
        return;
    }
}

function validate_registration(bool $from_inside = false): void
{
    if ($from_inside === false)
    {
        header('Location: /inscription');
        exit;
    }

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
            set_session_error(ErrorTypes::MISSING_FIELD, $field, line: __LINE__);
            header('Location: /register');
            exit;
        } else if (strlen($_POST[$field]) > $field_req['max_length'])
        {
            set_session_error(ErrorTypes::FIELD_TOO_LONG, $field, line: __LINE__);
            header('Location: /register');
            exit;
        }
    }

    $uuid  = uniqid('usr_', true);
    $name  = htmlentities($_POST['name']);
    $fname = htmlentities($_POST['fname']);
    
    $nick = null;
    if (!empty($_POST['nick']) || isset($_POST['nick']))
    {
        $nick = htmlentities($_POST['nick']);
    }
     
    $gender     = htmlentities($_POST['gender']);
    $email      = htmlentities($_POST['mail']);
    $passwd     = htmlentities($_POST['passwd']);
    $passwd_rep = htmlentities($_POST['passwd-rep']);

    if ($passwd !== $passwd_rep)
    {
        set_session_error(ErrorTypes::REPEATED_PASSWORD_NOT_EQUALS, line: __LINE__);
        header('Location: /register');
        exit;
    }

    $email_validator = new EmailValidator();

    if(!$email_validator->isValid($email, new RFCValidation()))
    {
        set_session_error(ErrorTypes::BAD_EMAIL_FORMAT, line: __LINE__);
        header('Location: /register');
        exit;
    }

    if(!$email_validator->isValid($email, new DNSCheckValidation()))
    {
        set_session_error(ErrorTypes::EMAIL_DOES_NOT_EXIST, line: __LINE__);
        header('Location: /register');
        exit;
    }

    /** @var PDO $db */
    $db = db_connect();

    if ($db === false)
    {
        set_session_error(ErrorTypes::SQL_ERROR, line: __LINE__);
        header('Location: /register');
        exit;
    }

    $users_with_same_email = users_by_email($email);

    if ($users_with_same_email === false)
    {
        set_session_error(ErrorTypes::SQL_ERROR, line: __LINE__);
        header('Location: /register');
        exit;
    }

    if (!empty($users_with_same_email))
    {
        set_session_error(ErrorTypes::ACCOUNT_WITH_EMAIL_ALREADY_EXISTS, line: __LINE__);
        header('Location: /register');
        exit;
    }

    $token = bin2hex(random_bytes(30));

    try
    {
        $stmt = $db->prepare(
            'INSERT INTO `users`(
                `user_uuid`,
                `user_last_name`,
                `user_first_name`,
                `user_nickname`,
                `user_password_hash`,
                `user_email`,
                `user_gender`,
                `user_validation_token`
            ) VALUES (
                :uuid, :lname, :fname, :nick, :passwd, :email, :gender, :token
            )');
        $stmt->bindParam(':uuid', $uuid);
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
        error_log($err);
        set_session_error(ErrorTypes::SQL_ERROR, line: __LINE__);
        header('Location: /register');
        exit;
    }

    $mailer = new PHPMailer(true);
    $verification_url = SITE_URL . '/register/validate/account?token=' . urlencode($token);

    session_abort();

    if(send_verification_mail($mailer, $email, $fname, $name, $verification_url) === true) {
        echo '👍';
        header('Location: /inscription/validation');
        exit;
    }

    echo '👎';
    set_session_error(ErrorTypes::VERIFICATION_MAIL_NOT_SENT, line: __LINE__);
    users_by_email($email, action: DBActions::DELETE);
    header('Location: /inscription');
    
    session_abort();
    exit;
}

function send_verification_mail(PHPMailer $mailer, string $email, string $fname, string $name, string $verification_url): bool
{
    try
    {
        $mailer->isSMTP();
        $mailer->Host        = SMTP_SERVER;
        $mailer->SMTPAuth    = true;
        $mailer->Username    = EMAIL_ADDRESS;
        $mailer->Password    = EMAIL_PASSWORD;
        $mailer->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mailer->Port = 587;

        $mailer->setFrom('no-reply@mmi-troyes.fr', 'Grapes 🍇');
        $mailer->addAddress($email, $fname . ' ' . $name);

        $mailer->isHTML(true);
        $mailer->CharSet = 'UTF-8';
        $mailer->Subject = 'Grapes 🍇 - Mail de vérification';
        $mailer->Body    =
            '<!doctype html>' . "\r\n" .
            '<html lang="fr">' . "\r\n" .
            '<head>' . "\r\n" .
                '<meta charset="utf-8">' . "\r\n" .
                '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\r\n" .
                '<title>Mail de vérification</title>' . "\r\n" .
            '</head>' . "\r\n" .
            '<body style="margin:0;font-family:system-ui, -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Open Sans\', \'Helvetica Neue\', sans-serif;font-size:125%;line-height:1.5;background-color:whitesmoke;">' . "\r\n" .
                '<div style="margin:auto;padding:4em;background-color:#f8eccf;border-bottom:2px #c2b79f solid;">' . "\r\n" .
                    '<h1 style="margin:0;line-height:0.8;margin-bottom:0.33em;color:#222;">Mail de vérification</h1>' . "\r\n" .
                    '<p class="start" style="margin:0;margin-bottom:0.8em;color:#222;">' . "\r\n" .
                        'Hey <span class="fname" style="font-weight:bold;">' . $fname . '</span> ! Pour finaliser votre inscription vous devez valider votre' . "\r\n" .
                        'inscription en appuyant sur le bouton suivant :' . "\r\n" .
                    '</p>' . "\r\n" .
                    '<a class="button" href="' . $verification_url . '" style="display:block;color:white;width:fit-content;padding:1em 2em;margin:0.5em auto;margin-inline:auto;background-color:#4B755F;outline:4px solid black;text-decoration:none;border-radius:1em;">Valider mon inscription</a>' . "\r\n" .
                    '<small style="display:block;width:fit-content;margin-bottom:1em;margin-inline:auto;font-style:italic;">' . "\r\n" .
                        'Ou en cliquant sur le lien suivant&nbsp;: ' . "\r\n" .
                        '<a href="' . $verification_url . '" style="color:#75A358;text-decoration:1px solid underline;">' . $verification_url . '</a>' . "\r\n" .
                    '</small>' . "\r\n" .
                    '<p style="margin:0;color:#222;">' . "\r\n" .
                        'Si quelque chose d\'innatendu se produit, veuillez' . "\r\n" .
                        '<a href="' . SITE_URL . '/contact" style="color:#75A358;text-decoration:1px solid underline;">nous contacter</a>' . "\r\n" .
                        'dans les plus brefs délais.' . "\r\n" .
                    '</p>' . "\r\n" .
                '</div>' . "\r\n" .
                '<footer style="color:#222;bottom:0;text-align:center;padding:2em;font-style:italic;">L\'équipe de Grapes</footer>' . "\r\n" .
            '</body>' . "\r\n" .
            '</html>';
        $mailer->AltBody = 'Hey ' . $fname . ' ! Pour finaliser votre inscription vous devez valider votre inscription en appuyant sur le lien suivant :' . $verification_url . ".\r\n" .
            'Si quelque chose d\'innatendu se produit, veuillez nous contacter ici → ' . SITE_URL . '/contact';


        $mailer->send();
        return true;
    } catch (Exception $err)
    {
        error_log($err);
        return false;
    }
}

function validate_account(bool $from_inside = false): void
{
    if ($from_inside === false)
    {
        header('Location: /inscription');
        exit;
    }

    $token = urldecode($_GET['token']);

    if (strlen($token) !== 60)
    {
        set_session_error(ErrorTypes::WRONG_TOKEN, line: __LINE__);
        header('Location: /register');
        exit;
    }

    $user = get_user_by_validation_token($token);

    if($user === false)
    {
        set_session_error(ErrorTypes::SQL_ERROR, line: __LINE__);
        header('Location: /register');
        exit;
    }

    if(empty($user))
    {
        set_session_error(ErrorTypes::WRONG_TOKEN, line: __LINE__);
        header('Location: /register');
        exit;
    }

    if (validate_account_by_token($user) === false)
    {
        set_session_error(ErrorTypes::SQL_ERROR, line: __LINE__);
        header('Location: /register');
        exit;
    }

    header('Location: /register/validated');
    exit;
}
