<form action="/gardens/validate" method="POST">
    <div class="label-input-wp">
        <label for="name">Nom / Surnom du jardin</label>
        <input type="text" name="name" id="name" required>
    </div>
    <div class="latlng">
        <div class="label-input-wp">
            <label for="lat">Latitude</label>
            <input type="text" name="lat" id="lat" value="<?= $_GET['lat'] ?? '' ?>" required>
        </div>
        <div class="label-input-wp">
            <label for="long">Longitude</label>
            <input type="text" name="long" id="long" value="<?= $_GET['lng'] ?? '' ?>" required>
        </div>
    </div>
    <div class="street">
        <div class="label-input-wp">
            <label for="street-num">Numéro d'adresse</label>
            <input type="text" name="street-num" id="street-num" required>
        </div>
        <div class="label-input-wp">
            <label for="street-name">Nom de l'adresse</label>
            <input type="text" name="street-name" id="street-name" required>
        </div>
    </div>
    <div class="label-input-wp">
        <label for="size">Taille (en m²)</label>
        <input type="text" name="size" id="size" required>
    </div>
    <div class="label-input-wp">
        <label for="n-plots">Nombre de parcelles disponibles</label>
        <input type="text" name="n-plots" id="n-plots" required>
    </div>

    <input type="hidden" name="uuid" value="<?= encrypt($GLOBALS['user']['user_uuid']) ?>">

    <button type="submit">Envoyer</button>
</form>
