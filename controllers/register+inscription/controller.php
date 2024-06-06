<?php

function index(): void
{
    $view_name = 'Register';
    $page_title = 'Inscription';

    require viewsPath('templates/Main_template.php');
}

function validate(): void
{
    // TODO
}
