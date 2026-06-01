<?php include 'header.php'; ?>


    <h1>Mon compte</h1>

    <h2>Photo de profil actuelle</h2>
    <?php if ($membre['photo']): ?>
        <img src="../uploads/<?php echo htmlspecialchars($membre['photo']); ?>" alt="Ma photo" width="100">
    <?php else: ?>
        <p>Pas de photo de profil.</p>
    <?php endif; ?>

    <h2>Changer ma photo</h2>

    <?php if ($erreur): ?>
        <p><?php echo htmlspecialchars($erreur); ?></p>
    <?php endif; ?>

    <?php if ($succes): ?>
        <p><?php echo htmlspecialchars($succes); ?></p>
    <?php endif; ?>

    <form method="POST" action="mon_compte.php" enctype="multipart/form-data">
        <label for="photo">Ajouter une photo :</label>
        <input type="file" id="photo" name="photo" accept="image/*">
        <br><br>
        <button type="submit">Valider</button>
    </form>

</body>

</html>