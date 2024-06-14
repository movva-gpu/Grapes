<?php
$page_title = ($page_title ?? 'Document sans titre') . ' - ' . APP_NAME;
session_start();

print_r($_SESSION['error']);
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $page_title ?></title>
    <link rel="author" href="/humans.txt">
    <link rel="author" href="/humans.en.txt">
    <?= $head ?? '' ?>
    <link rel="stylesheet" href="<?= assets_path('css/styles.css') ?>">
    <script src="<?= assets_path('js/main.js') ?>" defer></script>
</head>
<body style="font-family: system-ui, -apple-system, sans-serif">
    <?php require views_path('shared/Header.inc.php'); ?>

    <main style="min-height: calc(90svh)">
        <?php if (!empty($error)): ?>
            <div class="error">
                    Erreur : <?= (function($error)
                    {
                        switch ($error['error']['type'])
                        {
                            case ErrorTypes::MISSING_FIELD:
                                $field = $error['error']['message'];
                                switch ($field)
                                {
                                    case 'name':
                                        $field_name = 'Nom';
                                        break;
                                    case 'fname':
                                        $field_name = 'Prénom';
                                        break;
                                    case 'gender':
                                        $field_name = 'Genre';
                                        break;
                                    case 'mail':
                                        $field_name = 'Adresse e-mail';
                                        break;
                                }
                                $to_return = 'Le champ "' . $field_name . '"  est obligatoire.';
                                break;
                            case ErrorTypes::FIELD_TOO_LONG:
                                $field = $error['error']['message'];
                                switch ($field)
                                {
                                    case 'name':
                                        $field_name = 'Nom';
                                        break;
                                    case 'fname':
                                        $field_name = 'Prénom';
                                        break;
                                    case 'gender':
                                        $field_name = 'Genre';
                                        break;
                                    case 'mail':
                                        $field_name = 'Adresse e-mail';
                                        break;
                                }
                                $to_return = 'Le champ "' . $field_name . '"  est trop long.';
                                break;
                            case ErrorTypes::BAD_EMAIL_FORMAT:
                                $to_return = 'Votre adresse e-mail est invalide.';
                                break;
                            case ErrorTypes::EMAIL_DOES_NOT_EXIST:
                                $to_return = 'L\'adresse e-mail spécifiée n\'existe pas.';
                                break;
                            case ErrorTypes::REPEATED_PASSWORD_NOT_EQUALS:
                                $to_return = 'Les mots de passes spécifiés ne sont pas identiques.';
                                break;
                            case ErrorTypes::ACCOUNT_WITH_EMAIL_ALREADY_EXISTS:
                                $to_return = 'Un compte avec cette adresse e-mail existe déjà. Vouliez-vous vous <a href="/connexion">connecter</a> ?';
                                break;
                            default:
                                $to_return = 'Une erreur s\'est produite, veuillez réessayer.';
                                break;
                        }
                        return $to_return;
                    })($error) ?>
            </div>
        <?php unset($error); endif ?>

        <?php require views_path($view_name . '_view.php'); ?>
    </main>

    <?php require views_path('shared/Footer.inc.php'); ?>
</body>
</html>

<?php
session_write_close();
?>
