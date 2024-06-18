<?php
use PHPMailer\PHPMailer\PHPMailer;

function send_mail(
    PHPMailer $mailer,
    string    $recipient_email,
    string    $recipient_full_name,
    string    $subject,
    string    $body,
    string    $alt_body,
    array     $sender = ['no-reply@mmi-troyes.fr', 'Grapes 🍇'],

):  bool
{
    $subject_encoded = htmlentities($subject);

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

        $mailer->setFrom($sender[0], $sender[1]);
        $mailer->addAddress($recipient_email, $recipient_full_name);

        $mailer->isHTML(true);
        $mailer->CharSet = 'UTF-8';
        $mailer->Subject = "Grapes 🍇 - $subject";
        $mailer->Body    =
            '<!doctype html>' . "\r\n" .
            '<html lang="fr">' . "\r\n" .
            '<head>' . "\r\n" .
                '<meta charset="utf-8">' . "\r\n" .
                '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\r\n" .
                '<title>' . $subject_encoded .'</title>' . "\r\n" .
            '</head>' . "\r\n" .
            '<body style="margin:0;font-family:system-ui, -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Open Sans\', \'Helvetica Neue\', sans-serif;font-size:125%;line-height:1.5;background-color:whitesmoke;">' . "\r\n" .
                '<div style="margin:auto;padding:4em;background-color:#f8eccf;border-bottom:2px #c2b79f solid;">' . "\r\n" .
                    '<h1 style="margin:0;line-height:0.8;margin-bottom:0.33em;color:#222;">' . $subject_encoded .'</h1>' . "\r\n" .
                    '<p class="start" style="margin:0;margin-bottom:0.8em;color:#222;">' . "\r\n" .
                        $body . "\r\n" .
                    '</p>' . "\r\n" .
                '</div>' . "\r\n" .
                '<footer style="color:#222;bottom:0;text-align:center;padding:2em;font-style:italic;">L\'équipe de Grapes 🍇</footer>' . "\r\n" .
            '</body>' . "\r\n" .
            '</html>';
        $mailer->AltBody = $alt_body . "\r\n\r\n" . 'L\'équipe de Grapes 🍇';


        $mailer->send();
        return true;
    } catch (Exception $err)
    {
        error_log($err);
        return false;
    }
}
