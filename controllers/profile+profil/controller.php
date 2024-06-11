<?php

function index(): void
{
    return;
}

function apropos(): void
{
    about();
}

function about(): void
{
    $page_title = 'Accueil';
    $view_name = 'Home';
    require views_path('templates/Main_template.php');
}
