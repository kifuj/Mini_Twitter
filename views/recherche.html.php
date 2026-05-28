<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche - Mini Twitter</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <a href="../index.php">Retour a l'accueil</a>

    <h1>Recherche</h1>

    <form method="GET" action="recherche.php">
        <input type="text" name="q" value="<?php echo htmlspecialchars($recherche); ?>" placeholder="Rechercher un membre ou un tweet...">
        <button type="submit">Rechercher</button>
        <?php if ($recherche): ?>
            <a href="recherche.php">Effacer</a>
        <?php endif; ?>
    </form>

    <hr>

    <?php if ($recherche === ''): ?>
        <p>Entrez un mot pour lancer la recherche.</p>
    <?php else: ?>

        <h2>Membres (<?php echo count($membres); ?>)</h2>
        <?php if (empty($membres)): ?>
            <p>Aucun membre trouve.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($membres as $membre): ?>
                    <li>
                        <?php if ($membre['photo']): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($membre['photo']); ?>" width="40">
                        <?php endif; ?>
                        <a href="profil.php?id=<?php echo $membre['id_membre']; ?>">
                            <?php echo htmlspecialchars($membre['identifiant']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <hr>

        <h2>Tweets (<?php echo count($tweets); ?>)</h2>
        <?php if (empty($tweets)): ?>
            <p>Aucun tweet trouve.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($tweets as $tweet): ?>
                    <li>
                        <a href="profil.php?id=<?php echo $tweet['id_membre']; ?>">
                            <?php echo htmlspecialchars($tweet['identifiant']); ?>
                        </a>
                        : <?php echo htmlspecialchars($tweet['contenu']); ?>
                        <br>
                        <small><?php echo $tweet['date_tweet']; ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    <?php endif; ?>

</body>
</html>