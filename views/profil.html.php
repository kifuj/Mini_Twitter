<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil de <?php echo htmlspecialchars($membre['identifiant']); ?></title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <a href="../index.php">Retour a l'accueil</a>

    <hr>

    <h1><?php echo htmlspecialchars($membre['identifiant']); ?></h1>

    <?php if ($membre['photo']): ?>
        <img src="../uploads/<?php echo htmlspecialchars($membre['photo']); ?>" alt="Photo" width="100">
    <?php else: ?>
        <p>Pas de photo de profil.</p>
    <?php endif; ?>

    <hr>

    <h2>Tweets de <?php echo htmlspecialchars($membre['identifiant']); ?></h2>

    <?php if (empty($tweets)): ?>
        <p>Aucun tweet pour le moment.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($tweets as $tweet): ?>
                <li>
                    <p><?php echo htmlspecialchars($tweet['contenu']); ?></p>
                    <small><?php echo $tweet['date_tweet']; ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>