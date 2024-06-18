<?php

$user = $provided_user ?? $GLOBALS['user'];

$user['user_uuid'] = encrypt($user['user_uuid']);

unset($user['user_id']);

$user_no_entities = array_map(function ($val) {
    return html_entity_decode($val);
}, $user);

unset($user_no_entities['user_uuid']);
unset($user_no_entities['user_validation_token']);
unset($user_no_entities['user_password_hash']);
unset($user_no_entities['user_email']);
unset($user_no_entities['user_profile_picture_filename']);
unset($user_no_entities['user_validated']);
unset($user_no_entities['user_created_at']);
unset($user_no_entities['user_last_updated_at']);

if ($user['user_display_name'] === DisplayName::NICKNAME->value && !is_null($user['user_nickname']))
{
    $display_name = $user['user_nickname'];
    if (!is_null($user['user_pronouns']))
    {
        $second_line = $user['user_first_name'] . ' ' . $user['user_last_name'] . ' · ' . $user['user_pronouns'];
    } else
    {
        $second_line = $user['user_first_name'] . ' ' . $user['user_last_name'];
    }
} else
{
    $display_name = $user['user_first_name'] . ' ' . $user['user_last_name'];
    if (!is_null($user['user_nickname']))
    {
        $second_line = $user['user_nickname'];
        if (!is_null($user['user_pronouns']))
        {
            $second_line .= ' · ' . $user['user_pronouns'];
        }
    } elseif (!is_null($user['user_pronouns']))
    {
        $second_line = $user['user_pronouns'];
    } else
    {
        $second_line = 'pseudonyme / pronoms non définis';
    }
}

?>

<script><?php

$bio_small = '\'bio-small\'';

$display_checkbox_attribute = $user['user_display_name'] === DisplayName::NICKNAME->value ? 'checked' : '';
$img_path = assets_path('pfp/' . $user['user_profile_picture_filename'] . '_256px.avif', true);

$image_input_onchange = \JShrink\Minifier::minify(<<<JS

((e) => {
    if (e.files && e.files[0]) {
        const reader = new FileReader();
        
        reader.onload = event => {
            document.getElementById(`pfp-image`).style.backgroundImage = `url(` + event.target.result + `)`;
        };

        reader.readAsDataURL(e.files[0]);
    };
})(this);
JS);

$profile_form = <<<HTML
<form action="/profil/edit" method="POST" enctype="multipart/form-data">

    <input
        type="hidden"
        name="uuid"
        value="{$user['user_uuid']}"
        hidden
    >

    <input
        type="file"
        id="pfp"
        name="pfp"
        onchange="{$image_input_onchange}"
        hidden
    >

    <label for="pfp" id="pfp-image" style="background-image: url({$img_path})">
        <button type="button" onclick="revert(this, event)" data-for="pfp-image" class="back">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16">
                <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                <path d="M48.5 224H40c-13.3 0-24-10.7-24-24V72c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2L98.6 96.6c87.6-86.5 228.7-86.2 315.8 1c87.5 87.5 87.5 229.3 0 316.8s-229.3 87.5-316.8 0c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0c62.5 62.5 163.8 62.5 226.3 0s62.5-163.8 0-226.3c-62.2-62.2-162.7-62.5-225.3-1L185 183c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8H48.5z"/>
            </svg>
        </button>
    </label>

    <div class="label-input-wp">
        <label for="first-name">Prénom</label>
        <div class="cancel-input-wp">
            <input
                type="text"
                name="first-name" id="first-name"
                value="{$user['user_first_name']}"
                oninput="onInput(this, event, 40, 50, `first-name-small`)"
                maxlength="50"
            >
            <button type="button" onclick="revert(this, event)" data-for="first-name" class="back">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16">
                    <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                    <path d="M48.5 224H40c-13.3 0-24-10.7-24-24V72c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2L98.6 96.6c87.6-86.5 228.7-86.2 315.8 1c87.5 87.5 87.5 229.3 0 316.8s-229.3 87.5-316.8 0c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0c62.5 62.5 163.8 62.5 226.3 0s62.5-163.8 0-226.3c-62.2-62.2-162.7-62.5-225.3-1L185 183c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8H48.5z"/>
                </svg>
            </button>
        </div>
        <small id="first-name-small" hidden>0 / 0</small>
        
    </div>
    <div class="label-input-wp">
        <label for="last-name">Nom</label>
        <div class="cancel-input-wp">
            <input
                type="text"
                name="last-name" id="last-name"
                value="{$user['user_last_name']}"
                oninput="onInput(this, event, 40, 50, `last-name-small`)"
                maxlength="50"
            >
            <button type="button" onclick="revert(this, event)" data-for="last-name" class="back">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16">
                    <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                    <path d="M48.5 224H40c-13.3 0-24-10.7-24-24V72c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2L98.6 96.6c87.6-86.5 228.7-86.2 315.8 1c87.5 87.5 87.5 229.3 0 316.8s-229.3 87.5-316.8 0c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0c62.5 62.5 163.8 62.5 226.3 0s62.5-163.8 0-226.3c-62.2-62.2-162.7-62.5-225.3-1L185 183c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8H48.5z"/>
                </svg>
            </button>
        </div>
        <small id="last-name-small" hidden>0 / 0</small>
    </div>
    <div class="label-input-wp">
        <label for="nickname">Pseudonyme</label>
        <div class="cancel-input-wp">
            <input
                type="text"
                name="nickname" id="nickname"
                value="{$user['user_nickname']}"
                oninput="onInput(this, event, 30, 40, `nickname-small`)"
                maxlength="40"
            >
            <button type="button" onclick="revert(this, event)" data-for="nickname" class="back">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16">
                    <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                    <path d="M48.5 224H40c-13.3 0-24-10.7-24-24V72c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2L98.6 96.6c87.6-86.5 228.7-86.2 315.8 1c87.5 87.5 87.5 229.3 0 316.8s-229.3 87.5-316.8 0c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0c62.5 62.5 163.8 62.5 226.3 0s62.5-163.8 0-226.3c-62.2-62.2-162.7-62.5-225.3-1L185 183c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8H48.5z"/>
                </svg>
            </button>
        </div>
        <small id="nickname-small" hidden>0 / 0</small>
    </div>
    <div class="label-input-wp">
        <label for="display">Afficher le pseudonyme au lieu du nom réel</label>
        <input type="checkbox" id="display" name="display" {$display_checkbox_attribute}>
    </div>
    <div class="label-input-wp">
        <label for="pronouns">Pronoms</label>
        <div class="cancel-input-wp">
            <input
                type="text"
                name="pronouns" id="pronouns"
                value="{$user['user_pronouns']}"
                oninput="onInput(this, event, 30, 40, `pronouns-small`)"
                maxlength="40"
            >
            <button type="button" onclick="revert(this, event)" data-for="pronouns" class="back">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16">
                    <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                    <path d="M48.5 224H40c-13.3 0-24-10.7-24-24V72c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2L98.6 96.6c87.6-86.5 228.7-86.2 315.8 1c87.5 87.5 87.5 229.3 0 316.8s-229.3 87.5-316.8 0c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0c62.5 62.5 163.8 62.5 226.3 0s62.5-163.8 0-226.3c-62.2-62.2-162.7-62.5-225.3-1L185 183c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8H48.5z"/>
                </svg>
            </button>
        </div>
        <small id="pronouns-small" hidden>0 / 0</small>
    </div>

    <div class="label-input-wp">
        <label for="biography">Biographie</label>
        <div class="cancel-input-wp">
            <textarea maxlength="128"
                id="biography"
                oninput="onInput(this, event, 100, 128, `bio-small`)"
            >{$user['user_biography']}</textarea>
            <button type="button" onclick="revert(this, event)" data-for="biography" class="back">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16">
                    <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                    <path d="M48.5 224H40c-13.3 0-24-10.7-24-24V72c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2L98.6 96.6c87.6-86.5 228.7-86.2 315.8 1c87.5 87.5 87.5 229.3 0 316.8s-229.3 87.5-316.8 0c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0c62.5 62.5 163.8 62.5 226.3 0s62.5-163.8 0-226.3c-62.2-62.2-162.7-62.5-225.3-1L185 183c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8H48.5z"/>
            </svg>
            </button>
        </div>
        <small id="bio-small" hidden>0 / 0</small>
    </div>

    <div class="btn-wp">
        <button type="submit">Envoyer</button>
        <button type="button" onclick="exitForm()">Annuler</button>
    </div class="btn-wp">

</form>

HTML;

$profile_form = str_replace(PHP_EOL, '', $profile_form);
$profile_form = str_replace('    ', ' ', $profile_form);

$user_json = json_encode($user_no_entities);

// echo $profile_form;

echo \JShrink\Minifier::minify(<<<JS

window.profileForm = '{$profile_form}';
window.currentPage = '';

const user = $user_json;

const editMode = () => {
    window.currentPage = document.querySelector('.profile').innerHTML;
    document.querySelector('.profile').innerHTML = window.profileForm;
};

const exitForm = () => {
    const confirmPrompt = confirm('Êtes vous sûr ?');
    if (confirmPrompt) document.querySelector('.profile').innerHTML = window.currentPage;
};

const onInput = (el, e, limit, max, smallID) => {
    if (el.value.length > max) e.preventDefault;
    if (el.value.length >= limit) document.getElementById(smallID).hidden = false;
    if (el.value.length <= limit) document.getElementById(smallID).hidden = true;
    document.getElementById(smallID).innerHTML = el.value.length + ' / ' + max;
};

const revert = (btn, e) => {
    e.preventDefault();
    let for_ = btn.dataset.for
    switch (for_) {
        case 'first-name':
            document.getElementById(for_).value = user.user_first_name;
            break;
        case 'last-name':
            document.getElementById(for_).value = user.user_last_name;
            break;
        case 'pronouns':
            document.getElementById(for_).value = user.user_pronouns;
            break;
        case 'nickname':
            document.getElementById(for_).value = user.user_nickname;
            break;
        case 'biography':
            document.getElementById(for_).value = user.user_biography;
            break;
        case 'pfp-image':
            document.getElementById(for_).style = "background-image: url({$img_path})";
            document.getElementById('pfp').value = null;
            break;
        default:
            break;
    }
};

JS); ?></script>

<div class="profile">
    <a class="pfp-link" href="javascript:void(0);" onclick="editMode()">
        <img width="256" height="256" class="pfp" src="<?= assets_path('pfp/' . $user['user_profile_picture_filename'] . '_256px.avif', true) ?>">
        <span class="tooltip">Changer d'avatar</span>
    </a>
    <h1 class="whoami">
        <span class="display-name"><?= $display_name ?></span>
        <?php if(isset($second_line)): ?>
            <span class="second-line"><?= $second_line ?? 'vide' ?></span>
        <?php endif ?>
        <button
            href="javascript:void(0);"
            class="edit"
            onclick="editMode()"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="24" height="24">
                <!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                <path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z" />
            </svg>
        </button>
    </h1>
    
    <?php if ($user['user_biography']): ?>
        <p>
            <?= $user['user_biography'] ?>
        </p>
    <?php endif ?>
</div>

<a href="/disconnect">Se déconnecter</a>
