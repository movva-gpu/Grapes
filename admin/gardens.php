<?php declare(strict_types = 1);

if (session_status() === PHP_SESSION_ACTIVE)
{
    \Safe\session_abort();
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
<body style="display: flex; justify-content: center; min-height: 100vh;">
    <main style="height: fit-content;">
    <h1>Jardins</h1>
    <br>
    <br>
<?php foreach($gardens as $garden): 
    $created_at = date_create($garden['garden_created_at']);
    $day          = date_format($created_at, 'd');
    $month        = date_format($created_at, 'M');
    $hour         = date_format($created_at, 'H:i');

    $created_at_date = $day . ' ' . $months[$month] . ' à ' . $hour;
    
    $updated_at = date_create($garden['garden_created_at']);
    $day          = date_format($updated_at, 'd');
    $month        = date_format($updated_at, 'M');
    $hour         = date_format($updated_at, 'H:i');

    $updated_at_date = $day . ' ' . $months[$month] . ' à ' . $hour;
    $owner = user_by_id($garden['_user_id']);
?>

    <h2><?= $garden['garden_name'] ?></h2>
    <small>ID: <?= $garden['garden_id'] ?></small>

    <p>
        <b>Publié par</b>
        <?= $owner['user_first_name'] ?>
        <small>(ID: <?= $owner['user_id'] ?>)</small>
    </p>

    <p>
        <b>Latitude:</b> <?= $garden['garden_lat'] ?><br>
        <b>Longitude:</b> <?= $garden['garden_long'] ?><br>
        <b>Adresse:</b> <?= $garden['garden_street_number'] . ' ' . $garden['garden_street_name'] ?><br>
    </p>

    <a href="/gestion/gardens_edit.php?id=<?= $garden['garden_id'] ?>">Modifier</a>
    <a href="/gestion/gardens_delete.php?id=<?= $garden['garden_id'] ?>">Supprimer</a>

    <footer>
        <b>Créé le</b> <?= $created_at_date ?><br>
        <b>Dernière modification le</b> <?= $updated_at_date ?>
    </footer>

    <hr>
    <br>
    <br>

<?php endforeach ?>
    </main>
</body>
</html>
