<?php

$site_url = SITE_URL;

$map_js = <<<JS

const siteURL = '{$site_url}';

const map = L.map('map', {
    center: [48.2973, 4.0742],
    zoom: 13,
    preferCanvas: true,
    doubleClickZoom: false
});

map.on('dblclick', e => {
    const createMap = confirm('Voulez vous créer un jardin aux coordonnées ' + (Math.round(e.latlng.lat * 1e4) / 1e4) + ', ' + (Math.round(e.latlng.lng * 1e4) / 1e4) + ' ?');
    
    if (createMap) {
        open(siteURL + '/gardens/add?lat=' + e.latlng.lat + '&lng=' + e.latlng.lng, '_self');
    }
});

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

const deleteGarden = (gardenID, userUUID) => {
    const confirmation = confirm('Êtes vous sûr de vouloir faire cela ? Cette action est irréversible.');
    if (confirmation) {
        open(siteURL + '/gardens/delete?id=' + gardenID + '&user_uuid=' + userUUID, '_self');
    }
};

JS;

$GLOBALS['garden_user_ids'] = [];

$months = [
    'Jan' => 'jan.',
    'Feb' => 'fév.',
    'Mar' => 'mars',
    'Apr' => 'avr.',
    'May' => 'mai',
    'Jun' => 'juin',
    'Jul' => 'juil.',
    'Aug' => 'août',
    'Sep' => 'sep.',
    'Oct' => 'oct.',
    'Nov' => 'nov.',
    'Dec' => 'déc.'
];

$markers_js = (function () use ($months) {
    $to_return = [];
    foreach ($GLOBALS['user_gardens'] as $garden) {
        $garden_id          = $garden['garden_id'];
        $GLOBALS['garden_user_ids'][]  = $garden_id;
        $garden_name        = $garden['garden_name'];
        $garden_latlng      = $garden['garden_lat'] . ', ' . $garden['garden_long'];
        $garden_street_text = $garden['garden_street_number'] . ' ' . strtolower($garden['garden_street_name']);
        $garden_size        = $garden['garden_size'] . ' m²';
        $garden_n_plots     = $garden['garden_n_plots'];
        $garden_edit_link   = SITE_URL . '/gardens/edit?id=' . $garden_id;

        $current_time = date_create($garden['garden_created_at']);
        $day          = date_format($current_time, 'd');
        $month        = date_format($current_time, 'M');
        $hour         = date_format($current_time, 'H:i');

        $date = $day . ' ' . $months[$month] . ' à ' . $hour;

        $uuid = encrypt($GLOBALS['user']['user_uuid']);

        $to_return[] = <<<JS
        
        const marker{$garden_id} = L.marker([$garden_latlng]).addTo(map);
        marker{$garden_id}.bindPopup('<h3>&laquo;&nbsp;{$garden_name}&nbsp;&raquo;</h3>' +
            '<small>' +
            'posté par <i>vous</i> le <i>$date</i><br>' +
            '<b>Adresse&nbsp;: </b>{$garden_street_text}<br>' +
            '<b>Taille&nbsp;: </b>{$garden_size}<br>' +
            '<b>Parcelles occupées&nbsp;: </b>N/A / {$garden_n_plots}</small><br>' +
            '<div class="links" style="display: flex; align-items: center; gap: 0.33em;">' +
            '<a href="{$garden_edit_link}">Modifier</a>' +
            '<a class="delete-garden" href="javascript:void(0);" onclick="deleteGarden(`{$garden_id}`, `{$uuid}`)">' +
            '<svg width="16" height="16" fill="#e0111f" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M170.5 51.6L151.5 80h145l-19-28.4c-1.5-2.2-4-3.6-6.7-3.6H177.1c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80H368h48 8c13.3 0 24 10.7 24 24s-10.7 24-24 24h-8V432c0 44.2-35.8 80-80 80H112c-44.2 0-80-35.8-80-80V128H24c-13.3 0-24-10.7-24-24S10.7 80 24 80h8H80 93.8l36.7-55.1C140.9 9.4 158.4 0 177.1 0h93.7c18.7 0 36.2 9.4 46.6 24.9zM80 128V432c0 17.7 14.3 32 32 32H336c17.7 0 32-14.3 32-32V128H80zm80 64V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16z"/></svg>' +
            '</a>' +
            '</div>'
        );
        
        JS;
    }
    return implode(PHP_EOL, $to_return);
})();

$other_markers_js = (function () use ($months) {
    $to_return = [];
    foreach ($GLOBALS['gardens'] as $garden) {
        $garden_id          = $garden['garden_id'];
        $garden_name        = $garden['garden_name'];
        $garden_latlng      = $garden['garden_lat'] . ', ' . $garden['garden_long'];
        $garden_street_text = $garden['garden_street_number'] . ' ' . strtolower($garden['garden_street_name']);
        $garden_size        = $garden['garden_size'] . ' m²';
        $garden_n_plots     = $garden['garden_n_plots'];
        $plot_claim_link    = SITE_URL . '/plot/claim?garden_id=' . $garden_id;

        if (!empty(array_filter($GLOBALS['garden_user_ids'], function(int $id) use ($garden_id) {
            return $garden_id === $id;
        })))
        {
            continue;
        }

        $garden_owner = array_filter($GLOBALS['users'], function(array $user) use ($garden) {
            return $user['user_id'] === $garden['_user_id'];
        })[0];

        if (is_null($garden_owner)) continue;

        $garden_owner_full_name     = $garden_owner['user_first_name'] . ' ' . $garden_owner['user_last_name'];
        $garden_owner_nickname      = $garden_owner['user_nickname'];
        $garden_owner_show_nickname = $garden_owner['user_display_name'] === DisplayName::NICKNAME->value;

        $garden_owner_display_name = $garden_owner_full_name;

        if ($garden_owner_show_nickname && !is_null($garden_owner_nickname))
        {
            $garden_owner_display_name = $garden_owner_nickname;
        }

        $current_time = date_create($garden['garden_created_at']);
        $day          = date_format($current_time, 'd');
        $month        = date_format($current_time, 'M');
        $hour         = date_format($current_time, 'H:i');

        $date = $day . ' ' . $months[$month] . ' à ' . $hour;

        $to_return[] = <<<JS
        
        const marker{$garden_id} = L.marker([$garden_latlng]).addTo(map);
        marker{$garden_id}.bindPopup('<h3>&laquo;&nbsp;{$garden_name}&nbsp;&raquo;</h3>' +
            '<small>' +
            'posté par <i>{$garden_owner_display_name}</i>le <i>{$date}</i><br>' +
            '<b>Adresse&nbsp;: </b>{$garden_street_text}<br>' +
            '<b>Taille&nbsp;: </b>{$garden_size}<br>' +
            '<b>Parcelles occupées&nbsp;: </b>N/A / {$garden_n_plots}</small><br>' +
            '<a href="{$plot_claim_link}">Réserver une parcelle</a>');

        L.DomUtil.addClass(marker{$garden_id}._icon, 'other-people');
        L.DomUtil.addClass(marker{$garden_id}._shadow, 'other-people');

        JS;
    }
    return implode(PHP_EOL, $to_return);
})();

?>

<h1>Jardins</h1>
<br>

<div class="map-wp">
    <label for="hide-others-check" class="eye" role="button" title="Cache les jardins des autres utilisateurs">
        <svg class="hide" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
            <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
            <path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/>
        </svg>
        <svg class="show" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
            <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
            <path d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L525.6 386.7c39.6-40.6 66.4-86.1 79.9-118.4c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C465.5 68.8 400.8 32 320 32c-68.2 0-125 26.3-169.3 60.8L38.8 5.1zM223.1 149.5C248.6 126.2 282.7 112 320 112c79.5 0 144 64.5 144 144c0 24.9-6.3 48.3-17.4 68.7L408 294.5c8.4-19.3 10.6-41.4 4.8-63.3c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3c0 10.2-2.4 19.8-6.6 28.3l-90.3-70.8zM373 389.9c-16.4 6.5-34.3 10.1-53 10.1c-79.5 0-144-64.5-144-144c0-6.9 .5-13.6 1.4-20.2L83.1 161.5C60.3 191.2 44 220.8 34.5 243.7c-3.3 7.9-3.3 16.7 0 24.6c14.9 35.7 46.2 87.7 93 131.1C174.5 443.2 239.2 480 320 480c47.8 0 89.9-12.9 126.2-32.5L373 389.9z"/>
        </svg>
    </label>
    <input type="checkbox" id="hide-others-check" onchange="console.log(this.checked)" hidden>
    
    <div id="map" style="height: 580px"></div>
</div>

<small style="opacity: 0.7"><i>Pour ajouter un jardin, double-cliquez à l'endroit où il est situé.</i></small>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

<script><?= \JShrink\Minifier::minify($map_js . $markers_js . $other_markers_js); ?></script>
