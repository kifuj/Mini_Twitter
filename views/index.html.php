<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mini Twitter - Accueil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Mini Twitter</h1>

    <hr>

    <h2>Derniers membres inscrits</h2>

    <?php if (empty($derniers_membres)): ?>
        <p>Aucun membre inscrit pour le moment.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($derniers_membres as $membre): ?>
                <li>
                    <a href="controllers/profil.php?id=<?php echo $membre['id_membre']; ?>">
                        <?php echo htmlspecialchars($membre['identifiant']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <?php if ($total > 4): ?>
                <li>...</li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>

</body>
</html>