<?php declare(strict_types = 1);

if (session_status() === PHP_SESSION_ACTIVE)
{
    session_abort();
}

error_reporting(E_ALL);

const __ROOT__ = __DIR__ . DIRECTORY_SEPARATOR . '..';

require_once __ROOT__ . DIRECTORY_SEPARATOR .
    'utils' . DIRECTORY_SEPARATOR .
    'autoload.php';

require_once root_path('conf/config.inc.php');
include root_path('vendor/autoload.php');

$gardens = get_gardens();
$users = get_users();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <link rel="author" href="/humans.txt">
    <link rel="author" href="/humans.en.txt">
    <link rel="stylesheet" href="<?= assets_path('css/styles.css') ?>">
    <script src="<?= assets_path('js/main.js') ?>" defer></script>
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <main style="height: fit-content;">
        <h1>Bienvenue Maître du jeu... 🎲</h1>
        <br>
        <p>
            Il y a actuellement <?= count($gardens) ?> jardins de disponible sur le site.
            <br>
            Et il y a <?= count($users) ?> utilisateurs enregistrés sur le site.
        </p>
        <br>
        <a href="<?= SITE_URL ?>/gestion/gardens.php">Gestion des jardins</a>
        &nbsp;&nbsp;&nbsp;
        <a href="<?= SITE_URL ?>/gestion/users.php">Gestion des utilisateurs</a>
    </main>
</body>
</html>
