<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<?php
require('header.php');
?>
<body>
<h2 class="h2">Inscrivez vous</h2>
    <form class="t" action="" method="post">
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" required>
        <label for="prenom">Prénom</label>
        <input type="text" name="prenom" id="prenom" required>
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo" required>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" required>
        <input class="b" type="submit" value="Inscription">
    </form>
    <div class="no">
    <p>Déjà inscrit ? Connectez-vous</p>
    <a href="connexion.php">Se Connectez</a>
</div>


</body>
<?php
require('footer.php');
?>
</html>