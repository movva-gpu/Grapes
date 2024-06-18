<?php
function is_current_page(string $pageName): bool
{
    $current_dir = $GLOBALS['current_dir'];
    foreach (explode('+', $current_dir) as $str)
    {
        if ($pageName === $str)
        {
            return true;
        }
    }

    return false;
} ?>

<header>
    <nav>
        <img src="/assets/logo.png" alt="Logo">
        <div class="links">
            <a href="/" <?= is_current_page('home') ? 'aria-current="page"' : '' ?>>
                <div class="h">
                    <span class="main">Accueil</span>
                </div>
            </a>
            <div class="dropdown">
                <a href="/jardins" class="dropdown-btn" <?= is_current_page('jardins') ? 'aria-current="page"' : '' ?>>
                    <div class="h">
                        <span class="main">Les jardins</span>
                    </div>
                </a>
            </div>
            <a href="/contact" <?= is_current_page('contact') ? 'aria-current="page"' : '' ?>>
                <div class="h">
                    <span class="main">Contactez-nous</span>
                </div>
            </a>

            <?php if(isset($GLOBALS['user'])): ?>
            <a href="/profil">
                    <div class="h">
                        <span class="main">Profil</span>
                    </div>
            </a>
            <?php else: ?>
            <div class="co-ins">
                <a href="/login">
                    <div class="h">
                        <span class="main">Connexion</span>
                    </div>
                </a>
                /
                <a href="/inscription">
                    <div class="h">
                        <span class="main">Inscription</span>
                    </div>
                </a>
            </div>
            <?php endif ?>
        </div>
    </nav>
</header>

