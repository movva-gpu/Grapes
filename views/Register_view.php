<form action="/register/validate/" method="POST">
    <h1>S'inscrire</h1>
    <label for="name" title="Champ obligatoire">Nom<span style="color: red;">*</span></label>
    <input type="text" name="name" id="name" aria-required="true" required><br>

    <label for="fname" title="Champ obligatoire">Prénom<span style="color: red;">*</span></label>
    <input type="text" name="fname" id="fname" aria-required="true" required><br>

    <label for="nick">Pseudonyme</label>
    <input type="text" name="nick" id="nick" aria-required="false"><br>

    <fieldset>
        <legend>Identité de genre</legend>
        <label for="gender-f">Féminin</label>
        <input type="radio" name="gender" id="gender-f" value="f">
        <label for="gender-m">Masculin</label>
        <input type="radio" name="gender" id="gender-m" value="m">
        <label for="gender-nb">Neutre / Non-binaire</label>
        <input type="radio" name="gender" id="gender-nb" value="nb">
        <label for="gender-no">Ne pas préciser</label>
        <input type="radio" name="gender" id="gender-no" value="no">
    </fieldset>

    <label for="mail" title="Champ obligatoire">Adresse e-mail<span style="color: red;">*</span></label>
    <input type="email" name="mail" id="mail" aria-required="true" required><br>

    <label for="passwd" title="Champ obligatoire">Mot de passe<span style="color: red;">*</span></label>
    <input type="password" name="passwd" id="passwd" aria-required="true" required><br>

    <label for="passwd-rep" title="Champ obligatoire">Répéter le mot de passe<span style="color: red;">*</span></label>
    <input type="password" name="passwd-rep" id="passwd-rep" aria-required="true" required><br>

    <button type="submit">S'inscrire</button>

    <footer>Vous avez déjà un compte ? <a href="/connexion">Se connecter</a></footer>
</form>
