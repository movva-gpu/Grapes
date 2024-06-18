<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Connexion</title>
</head>

<?php
require('header.php');
?>

<body>

<h2 class="h2">Connectez vous</h2>
<form class="t" action="" method="post">
    <label for="pseudo">Pseudonyme</label> <br>
    <input type="pseudo" name="pseudo" id="pseudo" placeholder="pseudo" require> <br>
    <label for="password">Mot de passe</label> <br>
    <input type="password" name="password" id="password" placeholder="mots de passe" require> <br>
    <input class="b" type="submit" value="Connexion">
</form>
<div class="no">
    <p>Vous n'avez pas de compte ? </p>
    <a href="inscription.php">S'inscrire</a>
</div>


</body>
<?php
require('footer.php');
?>
</html>
