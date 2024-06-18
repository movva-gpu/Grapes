<?php
$page_title = ($page_title ?? 'Document sans titre') . ' - ' . APP_NAME;
session_start();

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

    <?php if ($view_name === 'Home'): ?>
        <div class="hero">
    <div class="hero-content">
        <div class="hero-text">
            <h1 style="font-family: Skingrape">GRAPES</h1>
            <p>Le co-jardinage c’est maintenant !</p>
        </div>
        <div class="hero-buttons">
            <a href="/inscription" class="btn">Commencer </a>
        </div>
    </div>
    <svg class="wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="white" fill-opacity="1" d="M0,224L60,186.7C120,149,240,75,360,85.3C480,96,600,192,720,208C840,224,960,160,1080,149.3C1200,139,1320,181,1380,202.7L1440,224L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path>
    </svg>
</div>
<?php endif ?>

    <main style="min-height: calc(90svh)">
        <?php if (!empty($error)): ?>
            <div class="error">
                    Erreur : <?= (function() use ($error)
                    {
                        if (isset($error['error']['message']))
                        {
                            switch ($error['error']['message'])
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
                                    case 'lat':
                                        $field_name = 'Latitude';
                                        break;
                                    case 'long':
                                        $field_name = 'Longitude';
                                        break;
                                    case 'street-name':
                                        $field_name = 'Nom d\'adresse';
                                        break;
                                    case 'street-num':
                                        $field_name = 'Numéro d\'adresse';
                                        break;
                                    case 'size':
                                        $field_name = 'Taille';
                                        break;
                                    case 'subject':
                                        $field_name = 'Sujet';
                                        break;
                                    case 'message':
                                        $field_name = 'Message';
                                        break;
                                }
                        }

                        switch ($error['error']['type'])
                        {
                            case ErrorTypes::MISSING_FIELD:
                                $to_return = 'Le champ "' . $field_name . '"  est obligatoire.';
                                break;
                            case ErrorTypes::FIELD_TOO_LONG:
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
                            case ErrorTypes::VERIFICATION_MAIL_NOT_SENT:
                                $to_return = 'Le mail de vérification ne s\'est pas envoyé, veuillez réessayer.';
                                break;
                            case ErrorTypes::WRONG_TOKEN:
                                $to_return = 'Le lien de vérification est incorrect.';
                                break;
                            case ErrorTypes::ACCOUNT_DOES_NOT_EXIST:
                                $to_return = 'Le compte spécifié n\'existe pas.';
                                break;
                            case ErrorTypes::WRONG_PASSWORD:
                                $to_return = 'Mot de passe incorrect.';
                                break;
                            case ErrorTypes::NOT_LOGGED_IN:
                                $to_return = 'Vous devez être connecter pour effectuer cette action';
                                break;
                            case ErrorTypes::BAD_FORMAT:
                                $to_return = 'Le format d\'un champ spécifié est incorrect';
                                break;
                            case ErrorTypes::ALREADY_RESERVING:
                                $to_return = 'Vous êtes déjà en train de réserver';
                                break;
                            case ErrorTypes::TOO_MANY_PLOTS:
                                $to_return = 'Toutes les parcelles sont déjà réservées';
                                break;
                            default:
                                $to_return = 'Une erreur s\'est produite, veuillez réessayer.';
                                break;
                        }
                        return $to_return;
                    })() ?>
            </div>
        <?php endif ?>

        <?= $field_name ?>

        <?php if ($error !== '' && $error['error']['message']): ?>

        <style>
        <?= <<<CSS
            input#{$error['error']['message']} {
                border-color: red !important
            }

        CSS ?>
        </style>

        <?php unset($error); endif ?>

        <?php require views_path($view_name . '_view.php'); ?>
    </main>

    <?php require views_path('shared/Footer.inc.php'); ?>
</body>
</html>

<?php
session_write_close();
?>
