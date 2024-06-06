<?php

function index(): void
{
    $page_title = 'Accueil';
    $view_name = 'Home';

    require_once viewsPath('templates/Main_template.php');
}
