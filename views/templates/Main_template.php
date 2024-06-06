<?php
$page_title = ($page_title ?? 'Document sans titre') . ' - ' . APP_NAME;
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="<?= getAsset('css/styles.css') ?>">
    <script src="<?= getAsset('js/main.js') ?>" defer></script>
</head>
<body style="font-family: system-ui, -apple-system, sans-serif">
    <?php require viewsPath('shared/Header.inc.php') ?>

    <main style="min-height: calc(90svh)">
        <?php if (http_response_code() !== 200) {
            echo '<h1 style="text-align: center">Erreur ' . http_response_code() . '</h1>';
        } else {
            require viewsPath($view_name . '_view.php');
        } ?>
    </main>

    <?php require viewsPath('shared/Footer.inc.php') ?>
</body>
</html>
