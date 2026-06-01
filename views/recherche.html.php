<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Recherche - Mini Twitter</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <?php session_start(); ?>

    <a href="../index.php">Retour a l'accueil</a>

    <h1>Recherche</h1>

    <form method="GET" action="recherche.php">

        <input type="text" name="q" value="<?php echo htmlspecialchars($recherche); ?>"
            placeholder="Rechercher un membre ou un tweet...">

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

                    <li class="tweet">

                        <div class="tweet-left">

                            <?php if ($tweet['photo']): ?>

                                <img class="tweet-avatar" src="../uploads/<?php echo htmlspecialchars($tweet['photo']); ?>">

                            <?php else: ?>

                                <div class="tweet-avatar default-avatar"></div>

                            <?php endif; ?>

                            <a class="tweet-user" href="profil.php?id=<?php echo $tweet['id_membre']; ?>">

                                <?php echo htmlspecialchars($tweet['identifiant']); ?>

                            </a>

                        </div>

                        <div class="tweet-right">

                            <p class="tweet-content">

                                <?php echo nl2br(htmlspecialchars($tweet['contenu'])); ?>

                            </p>

                            <small class="tweet-date">

                                <?php echo $tweet['date_tweet']; ?>

                            </small>

                            <br><br>

                            <?php if (isset($_SESSION['id_membre'])): ?>

                                <a class="btn-like" href="like.php?id_tweet=<?php echo $tweet['id_tweet']; ?>">

                                    ❤️ <?php echo $tweet['nb_likes']; ?>

                                </a>

                            <?php else: ?>

                                <span class="btn-like">

                                    ❤️ <?php echo $tweet['nb_likes']; ?>

                                </span>

                            <?php endif; ?>

                        </div>

                    </li>

                <?php endforeach; ?>

            </ul>

        <?php endif; ?>

    <?php endif; ?>

</body>

</html>