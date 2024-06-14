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

<?php if(!isset($GLOBALS['user'])): ?>
<header>
    <nav style="display:flex; justify-content: space-between">
        <div class="left">
            <a href="/" <?= is_current_page('home') ? 'aria-current="page"' : '' ?>>Accueil</a>
            <a href="/jardins" <?= is_current_page('garden')
                ? 'aria-current="page"'
                : '' ?>>Jardin potager</a>
            <a href="#" <?= is_current_page('contact') ? 'aria-current="page"' : '' ?>>Contact</a>
        </div>
        <div class="right">
            <a href="/inscription" <?= is_current_page('register')
                ? 'aria-current="page"'
                : '' ?>>S'inscrire</a>
            <a href="/login" <?= is_current_page('login')
                ? 'aria-current="page"'
                : '' ?>>Se connecter</a>
        </div>
    </nav>
</header>
<?php else: ?>
    <header>
    <nav style="display:flex; justify-content: space-between">
        <div class="left">
            <a href="/" <?= is_current_page('home') ? 'aria-current="page"' : '' ?>>Accueil</a>
            <a href="/jardins" <?= is_current_page('gardens')
                ? 'aria-current="page"'
                : '' ?>>Jardin potager</a>
            <a href="#" <?= is_current_page('contact') ? 'aria-current="page"' : '' ?>>Contact</a>
        </div>
        <div class="right">
            <span>
                Bienvenue
                <span style="font-weight: bold;">
                <?= !empty($GLOBALS['user']['user_nickname']) ?
                        $GLOBALS['user']['user_nickname']
                      : $GLOBALS['user']['user_first_name'] ?>
                </span>
            </span>
            <a href="/profil" <?= is_current_page('profile')
                ? 'aria-current="page"'
                : '' ?>>Profil</a>
            <a href="/deconnexion" <?= is_current_page('disconnect')
                ? 'aria-current="page"'
                : '' ?>>Se déconnecter</a>
        </div>
    </nav>
</header>
<?php endif ?>
