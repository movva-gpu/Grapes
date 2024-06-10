<?php
$page_title = ($page_title ?? 'Document sans titre') . ' - ' . APP_NAME;
print_r($_SESSION);

session_start();

print_r($_SESSION);
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
    <link rel="stylesheet" href="<?= getAsset('css/styles.css') ?>">
    <script src="<?= getAsset('js/main.js') ?>" defer></script>
</head>
<body style="font-family: system-ui, -apple-system, sans-serif">
    <?php require viewsPath('shared/Header.inc.php'); ?>

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
                                $toReturn = 'Le champ "' . $field_name . '"  est obligatoire.';
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
                                $toReturn = 'Le champ "' . $field_name . '"  est trop long.';
                                break;
                            case ErrorTypes::BAD_EMAIL_FORMAT:
                                $toReturn = 'Votre adresse e-mail est invalide.';
                                break;
                            case ErrorTypes::EMAIL_DOES_NOT_EXIST:
                                $toReturn = 'L\'adresse e-mail spécifiée n\'existe pas.';
                                break;
                            case ErrorTypes::REPEATED_PASSWORD_NOT_EQUALS:
                                $toReturn = 'Les mots de passes spécifiés ne sont pas identiques.';
                                break;
                            case ErrorTypes::ACCOUNT_WITH_EMAIL_ALREADY_EXISTS:
                                $toReturn = 'Un compte avec cette adresse e-mail existe déjà. Vouliez-vous vous <a href="/connexion">connecter</a> ?';
                                break;
                            default:
                                $toReturn = 'Une erreur s\'est produite, veuillez réessayer.';
                                break;
                        }
                        return $toReturn;
                    })($error) ?>
            </div>
        <?php unset($error); endif ?>

        <?php require viewsPath($view_name . '_view.php'); ?>
    </main>

    <?php require viewsPath('shared/Footer.inc.php'); ?>
</body>
</html>

<?php
\Safe\session_write_close();
?>
