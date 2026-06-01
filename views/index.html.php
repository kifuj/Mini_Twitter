<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mini Twitter - Accueil</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Mini Twitter</h1>

    <form action="controllers/recherche.php" method="GET">
        <input type="text" name="q" placeholder="Rechercher un membre...">
        <button type="submit">Rechercher</button>
    </form>

    <hr>

    <h2>Derniers membres inscrits</h2>

    <?php if (empty($derniers_membres)): ?>
        <p>Aucun membre inscrit pour le moment.</p>
    <?php else: ?>
        <ul class="membres-liste">

            <?php foreach ($derniers_membres as $membre): ?>

                <li class="membre-card">

                    <a href="controllers/profil.php?id=<?php echo $membre['id_membre']; ?>">

                        <?php if ($membre['photo']): ?>

                            <img class="membre-avatar" src="uploads/<?php echo htmlspecialchars($membre['photo']); ?>">

                        <?php else: ?>

                            <div class="membre-avatar default-avatar"></div>

                        <?php endif; ?>

                        <br>

                        <span class="membre-nom">

                            <?php echo htmlspecialchars($membre['identifiant']); ?>

                        </span>

                    </a>

                </li>

            <?php endforeach; ?>

        </ul>
    <?php endif; ?>

    <hr>

    <h2>Derniers tweets</h2>

    <?php if (empty($derniers_tweets)): ?>
        <p>Aucun tweet pour le moment.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($derniers_tweets as $tweet): ?>

                <li class="tweet">

                    <div class="tweet-left">

                        <?php if ($tweet['photo']): ?>

                            <img class="tweet-avatar" src="uploads/<?php echo htmlspecialchars($tweet['photo']); ?>">

                        <?php else: ?>

                            <div class="tweet-avatar default-avatar"></div>

                        <?php endif; ?>

                        <a class="tweet-user" href="controllers/profil.php?id=<?php echo $tweet['id_membre']; ?>">

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

                            <a class="btn-like" href="controllers/like.php?id_tweet=<?php echo $tweet['id_tweet']; ?>">

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

    <?php if (isset($_SESSION['id_membre'])): ?>
        <a href="controllers/profil.php?id=<?php echo $_SESSION['id_membre']; ?>">Mon profil
            (<?php echo htmlspecialchars($_SESSION['identifiant']); ?>)</a> |
        <a href="controllers/logout.php">Se deconnecter</a>
    <?php else: ?>
        <a href="controllers/login.php">Se connecter</a> |
        <a href="controllers/register.php">S'inscrire</a>
    <?php endif; ?>

</body>

</html>