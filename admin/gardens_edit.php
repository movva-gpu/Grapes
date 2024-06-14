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

if (!isset($_GET['id'])) header('Location: /gestion/gardens.php');

$garden = garden_by_id(intval($_GET['id']));

?>

<form action="gardens_edit_validate.php" method="post">
    <div class="label-input-wp">
        <label for="name">Nom / Surnom du jardin</label>
        <input type="text" name="name" id="name" value="<?= $garden['garden_name'] ?>" required>
    </div>
    <div class="latlng">
        <div class="label-input-wp">
            <label for="lat">Latitude</label>
            <input type="text" name="lat" id="lat" value="<?= $garden['garden_lat'] ?? '' ?>" required>
        </div>
        <div class="label-input-wp">
            <label for="long">Longitude</label>
            <input type="text" name="long" id="long" value="<?= $garden['garden_long'] ?>" required>
        </div>
    </div>
    <div class="street">
        <div class="label-input-wp">
            <label for="street-num">Numéro d'adresse</label>
            <input type="text" name="street-num" id="street-num" value="<?= $garden['garden_street_number'] ?>" required>
        </div>
        <div class="label-input-wp">
            <label for="street-name">Nom de l'adresse</label>
            <input type="text" name="street-name" id="street-name" value="<?= $garden['garden_street_name'] ?>" required>
        </div>
    </div>
    <div class="label-input-wp">
        <label for="size">Taille (en m²)</label>
        <input type="text" name="size" id="size" value="<?= $garden['garden_size'] ?>" required>
    </div>

    <input type="hidden" name="id" value="<?= $garden['garden_id'] ?>">
    <button type="submit">Envoyer</button>
</form>
