<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Mini Twitter</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <h1>Connexion</h1>

    <form method="POST" action="login.php">

        <label for="identifiant">Identifiant :</label><br>
        <input type="text" id="identifiant" name="identifiant">
        <br><br>

        <label for="mdp">Mot de passe :</label><br>
        <input type="password" id="mdp" name="mdp">
        <br><br>

        <button type="submit">Se connecter</button>

    </form>

    <br>
    <a href="register.php">Pas encore de compte ? S'inscrire</a> |
    <a href="../index.php">Retour a l'accueil</a>

</body>
</html>