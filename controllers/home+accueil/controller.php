<?php

function index(): void
{
    $page_title = 'Accueil';
    $view_name = 'Home';
    require viewsPath('templates/Main_template.php');
}
