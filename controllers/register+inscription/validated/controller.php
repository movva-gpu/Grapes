<?php

function _index(): void
{
    $view_name = 'Register_Validated';
    $page_title = 'Adresse e-mail validée';

    require views_path('templates/Main_template.php');
}

_index();
