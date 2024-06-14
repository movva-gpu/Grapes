<form action="/gardens/validateedit" method="post">
    <div class="label-input-wp">
        <label for="name">Nom / Surnom du jardin</label>
        <input type="text" name="name" id="name" value="<?= $GLOBALS['garden']['garden_name'] ?>" required>
    </div>
    <div class="latlng">
        <div class="label-input-wp">
            <label for="lat">Latitude</label>
            <input type="text" name="lat" id="lat" value="<?= $GLOBALS['garden']['garden_lat'] ?? '' ?>" required>
        </div>
        <div class="label-input-wp">
            <label for="long">Longitude</label>
            <input type="text" name="long" id="long" value="<?= $GLOBALS['garden']['garden_long'] ?>" required>
        </div>
    </div>
    <div class="street">
        <div class="label-input-wp">
            <label for="street-num">Numéro d'adresse</label>
            <input type="text" name="street-num" id="street-num" value="<?= $GLOBALS['garden']['garden_street_number'] ?>" required>
        </div>
        <div class="label-input-wp">
            <label for="street-name">Nom de l'adresse</label>
            <input type="text" name="street-name" id="street-name" value="<?= $GLOBALS['garden']['garden_street_name'] ?>" required>
        </div>
    </div>
    <div class="label-input-wp">
        <label for="size">Taille (en m²)</label>
        <input type="text" name="size" id="size" value="<?= $GLOBALS['garden']['garden_size'] ?>" required>
    </div>

    <input type="hidden" name="uuid" value="<?= encrypt($GLOBALS['user']['user_uuid']) ?>">
    <input type="hidden" name="id" value="<?= encrypt($GLOBALS['garden']['garden_id']) ?>">

    <button type="submit">Envoyer</button>
</form>
