<?php

function index(): void
{
    session_start();
    
    unset($_SESSION['user_id']);

    \Safe\session_write_close();

    header('Location: /connexion');
    exit;
}
