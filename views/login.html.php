<?php include 'header.php'; ?>

    <h1>Connexion</h1>

    <?php if ($erreur): ?>
        <p><?php echo htmlspecialchars($erreur); ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">

        <label for="identifiant">Identifiant :</label><br>
        <input type="text" id="identifiant" name="identifiant"
            value="<?php echo htmlspecialchars($_POST['identifiant'] ?? ''); ?>">
        <br><br>

        <label for="mdp">Mot de passe :</label><br>
        <input type="password" id="mdp" name="mdp">
        <br><br>

        <button type="submit">Se connecter</button>

    </form>

    <br>
    <a href="register.php">Pas encore de compte ? S'inscrire</a> |
    <a href="../index.php">Retour a l'accueil</a>

<?php include 'footer.php'; ?>