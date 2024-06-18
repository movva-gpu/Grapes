<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Jardinage Passion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require('header.php'); ?>

<main>
    <div class="profile-container">
        <div class="profile-header">
            <img src="profile-picture.jpg" alt="Photo de profil" class="profile-picture">
            <h2>Votre Prénom</h2>
        </div>
        <div class="profile-info">
            <h3>Informations personnelles</h3>
            <p><strong>Email :</strong> utilisateur@example.com</p>
            <p><strong>Prénom :</strong> Votre Prénom</p>
            <p><strong>Date de naissance :</strong> 01/01/2000</p>
            <p><strong>Adresse :</strong> 123 Rue Exemple, Ville, Pays</p>
        </div>
        <div class="profile-actions">
            <button>Modifier le profil</button>
            <button>Déconnexion</button>
        </div>
    </div>

    <div class="messages-gardens-container">
        <div class="messages-section">
            <h3>Voir mes messages</h3>
            <button>Voir les messages</button>
        </div>

        <div class="gardens-section">
            <h3>Mes jardins</h3>
            <div class="garden">
                <div class="garden-left">
                    <img src="garden-image.jpg" alt="Image du jardin" class="garden-image">
                </div>
                <div class="garden-info">
                    <p><strong>Nom du jardin :</strong> Jardin d'Exemple</p>
                    <p><strong>Lieu :</strong> Paris, France</p>
                    <p><strong>Nombre de parcelles :</strong> 10</p>
                    <p><strong>Taille du jardin :</strong> 200 m²</p>
                </div>
                <div class="garden-actions">
                    <button onclick="location.href='modifier.php'">Modifier</button>
                    <button>Ajouter une parcelle</button>
                </div>
            </div>

            <div class="grey-background">
                <div class="grid-container">
                    <div class="grid-item">
                        <img src="image1.jpg" alt="Image 1">
                        <p><strong>Nom :</strong> Jardin 1</p>
                        <p><strong>Taille :</strong> 300 m²</p>
                        <p><strong>Contenu :</strong> Fleurs, Légumes</p>
                        <p><strong>Lieu :</strong> Paris, France</p>
                        <p><strong>Horaires :</strong> 9h-18h</p>
                        <button>Modifier</button>
                    </div>
                    <div class="grid-item">
                        <img src="image2.jpg" alt="Image 2">
                        <p><strong>Nom :</strong> Jardin 2</p>
                        <p><strong>Taille :</strong> 500 m²</p>
                        <p><strong>Contenu :</strong> Herbes aromatiques, Fruits</p>
                        <p><strong>Lieu :</strong> Lyon, France</p>
                        <p><strong>Horaires :</strong> 10h-19h</p>
                        <button>Modifier</button>
                    </div>
                    <div class="grid-item">
                        <img src="image3.jpg" alt="Image 3">
                        <p><strong>Nom :</strong> Jardin 3</p>
                        <p><strong>Taille :</strong> 400 m²</p>
                        <p><strong>Contenu :</strong> Légumes, Arbustes</p>
                        <p><strong>Lieu :</strong> Marseille, France</p>
                        <p><strong>Horaires :</strong> 8h-17h</p>
                        <button>Modifier</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</main>

<?php require('footer.php'); ?>

</body>
</html>
