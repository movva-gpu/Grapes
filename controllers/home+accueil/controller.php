<?php

function index(): void
{
    $page_title = 'Accueil';
    $view_name = 'Home';
    require views_path('templates/Main_template.php');
}
