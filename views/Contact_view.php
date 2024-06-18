<?php

    if (isset($_GET['success']))
    {
        echo <<<HTML
        <div class="error">Mail envoyé !</div>
        HTML;
    }

?>

<form action="/contact/send" method="get">
    <div class="label-input-wrapper">
        <label for="first-name">
            Prénom <span style="color: #f22; cursor: help;" title="Champ obligatoire">*</span>
        </label>
        <input type="text" name="first-name" id="first-name" required maxlength="50" data-small-id="first-name-small"
            oninput="onInput(this, event)"
        >
        <small id="first-name-small" hidden>0 / 0</small>
    </div>
    <div class="label-input-wrapper">
        <label for="last-name">
            Nom de famille <span style="color: #f22; cursor: help;" title="Champ obligatoire">*</span>
        </label>
        <input type="text" name="last-name" id="last-name" required maxlength="50" data-small-id="last-name-small"
            oninput="onInput(this, event)"
        >
        <small id="last-name-small" hidden>0 / 0</small>
    </div>
    <div class="label-input-wrapper">
        <label for="email">
            Adresse e-mail <span style="color: #f22; cursor: help;" title="Champ obligatoire">*</span>
        </label>
        <input type="text" name="email" id="email" required maxlength="128" data-small-id="email-small"
            oninput="onInput(this, event)"
        >
        <small id="email-small" hidden>0 / 0</small>
    </div>
    <div class="label-input-wrapper">
        <label for="subject">
            Objet du mail <span style="color: #f22; cursor: help;" title="Champ obligatoire">*</span>
        </label>
        <input type="text" name="subject" id="subject" required maxlength="42" data-small-id="subject-small"
            oninput="onInput(this, event)"
        >
        <small id="subject-small" hidden>0 / 0</small>
    </div>
    <div class="label-input-wrapper">
        <label for="message">
            Message <span style="color: #f22; cursor: help;" title="Champ obligatoire">*</span>
        </label>
        <textarea type="text" name="message" id="message" required maxlength="1024" data-small-id="message-small"
            oninput="onInput(this, event)"
        ></textarea>
        <small id="message-small" hidden>0 / 0</small>
    </div>
    <button type="submit">Envoyer</button>
</form>

<script>
<?= /* \JShrink\Minifier::minify( */<<<JS
    const onInput = (el, e) => {
        const smallEl = document.getElementById(el.dataset.smallId);
        const max = el.maxLength;
        const limit = Math.round(max * 0.67);
        
        console.log({
            smallEl: document.getElementById(el.dataset.smallId),
            max: el.maxlength,
            limit: Math.round(max * 0.8)
        });

        if (el.value.length > max) e.preventDefault;
        if (el.value.length >= limit) smallEl.hidden = false;
        if (el.value.length <= limit) smallEl.hidden = true;
        smallEl.innerHTML = el.value.length + ' / ' + max;
    };
JS/* ) */; ?>
</script>

<style>
    :has(form) {
        position: relative;
    }

    form {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: absolute;
        top: 50%;
        left: 50%;
        padding: 2em;
        border-radius: 1em;
        background-color: wheat;
        min-height: 55vh;
        width: 33%;
        transform: translateY(-50%) translateX(-50%);
    }

    .label-input-wrapper label {
        display: block;
        margin-bottom: 0.33em;
    }

    button[type="submit"] {
        min-width: 25%;
        width: fit-content;
        transition: translate ease 667ms;

        &:hover {
            translate: 0 -8px;
        }
    }

    @media screen and (width <= 1200px) {
        form {
            width: 50%;
        }
    }

    @media screen and (width <= 950px) {
        form {
            width: 80%;
        }
    }

    @media screen and (width <= 600px) {
        form {
            position: static;
            transform: none;
            width: 100%;
            border-radius: 0;
            background-color: transparent;
        }
    }
</style>
