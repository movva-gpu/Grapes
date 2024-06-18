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
                    <form action="update_garden.php" method="post">
                        <label for="garden-name">Nom du jardin :</label>
                        <input type="text" id="garden-name" name="garden-name" value="Jardin d'Exemple"><br>
                        
                        <label for="garden-location">Lieu :</label>
                        <input type="text" id="garden-location" name="garden-location" value="Paris, France"><br>
                        
                        <label for="garden-parcels">Nombre de parcelles :</label>
                        <input type="number" id="garden-parcels" name="garden-parcels" value="10"><br>
                        
                        <label for="garden-size">Taille du jardin :</label>
                        <input type="text" id="garden-size" name="garden-size" value="200 m²"><br>
                        
                        <button type="submit">Enregistrer</button>
                    </form>
                </div>
                <div class="garden-actions">
                    <button onclick="location.href='ajouter_parcelle.html'">Ajouter une parcelle</button>
                </div>
            </div>

            <div class="grey-background">
                <div class="grid-container">
                    <div class="grid-item">
                        <img src="image1.jpg" alt="Image 1">
                        <form action="update_garden1.php" method="post">
                            <label for="garden1-name">Nom :</label>
                            <input type="text" id="garden1-name" name="garden1-name" value="Jardin 1"><br>
                            
                            <label for="garden1-size">Taille :</label>
                            <input type="text" id="garden1-size" name="garden1-size" value="300 m²"><br>
                            
                            <label for="garden1-content">Contenu :</label>
                            <input type="text" id="garden1-content" name="garden1-content" value="Fleurs, Légumes"><br>
                            
                            <label for="garden1-location">Lieu :</label>
                            <input type="text" id="garden1-location" name="garden1-location" value="Paris, France"><br>
                            
                            <label for="garden1-hours">Horaires :</label>
                            <input type="text" id="garden1-hours" name="garden1-hours" value="9h-18h"><br>
                            
                            <button type="submit">Modifier</button>
                        </form>
                    </div>
                    <div class="grid-item">
                        <img src="image2.jpg" alt="Image 2">
                        <form action="update_garden2.php" method="post">
                            <label for="garden2-name">Nom :</label>
                            <input type="text" id="garden2-name" name="garden2-name" value="Jardin 2"><br>
                            
                            <label for="garden2-size">Taille :</label>
                            <input type="text" id="garden2-size" name="garden2-size" value="500 m²"><br>
                            
                            <label for="garden2-content">Contenu :</label>
                            <input type="text" id="garden2-content" name="garden2-content" value="Herbes aromatiques, Fruits"><br>
                            
                            <label for="garden2-location">Lieu :</label>
                            <input type="text" id="garden2-location" name="garden2-location" value="Lyon, France"><br>
                            
                            <label for="garden2-hours">Horaires :</label>
                            <input type="text" id="garden2-hours" name="garden2-hours" value="10h-19h"><br>
                            
                            <button type="submit">Modifier</button>
                        </form>
                    </div>
                    <div class="grid-item">
                        <img src="image3.jpg" alt="Image 3">
                        <form action="update_garden3.php" method="post">
                            <label for="garden3-name">Nom :</label>
                            <input type="text" id="garden3-name" name="garden3-name" value="Jardin 3"><br>
                            
                            <label for="garden3-size">Taille :</label>
                            <input type="text" id="garden3-size" name="garden3-size" value="400 m²"><br>
                            
                            <label for="garden3-content">Contenu :</label>
                            <input type="text" id="garden3-content" name="garden3-content" value="Légumes, Arbustes"><br>
                            
                            <label for="garden3-location">Lieu :</label>
                            <input type="text" id="garden3-location" name="garden3-location" value="Marseille, France"><br>
                            
                            <label for="garden3-hours">Horaires :</label>
                            <input type="text" id="garden3-hours" name="garden3-hours" value="8h-17h"><br>
                            
                            <button type="submit">Modifier</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</main>

<?php require('footer.php'); ?>

</body>
</html>
