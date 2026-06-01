<?php include 'header.php'; ?>

    <h1>Inscription</h1>

    <?php if ($erreur): ?>
        <p><?php echo htmlspecialchars($erreur); ?></p>
    <?php endif; ?>

    <form method="POST" action="register.php" enctype="multipart/form-data">

        <label for="identifiant">Identifiant :</label><br>
        <input type="text" id="identifiant" name="identifiant"
            value="<?php echo htmlspecialchars($_POST['identifiant'] ?? ''); ?>">
        <br><br>

        <label for="mdp">Mot de passe :</label><br>
        <input type="password" id="mdp" name="mdp">
        <br><br>

        <label for="mdp_confirm">Confirmer le mot de passe :</label><br>
        <input type="password" id="mdp_confirm" name="mdp_confirm">
        <br><br>

        <label for="photo">Photo de profil (optionnel) :</label><br>
        <input type="file" id="photo" name="photo" accept="image/*">
        <br><br>

        <button type="submit">S'inscrire</button>

    </form>

    <br>
    <a href="login.php">Deja un compte ? Se connecter</a> |
    <a href="../index.php">Retour a l'accueil</a>

<?php include 'footer.php'; ?>