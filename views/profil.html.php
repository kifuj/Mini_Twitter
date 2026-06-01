<?php include 'header.php'; ?>


    <hr>

    <h1><?php echo htmlspecialchars($membre['identifiant']); ?></h1>

    <?php if ($membre['photo']): ?>
        <img src="../uploads/<?php echo htmlspecialchars($membre['photo']); ?>" alt="Photo" width="100">
    <?php else: ?>
        <p>Pas de photo de profil.</p>
    <?php endif; ?>

    <p>

        👥 <?php echo $nb_followers; ?> abonne(s)
        ·
        <?php echo $nb_following; ?> abonnement(s)

    </p>
    <?php if (isset($_SESSION['id_membre']) && !$est_proprietaire): ?>

        <a class="<?php echo $est_follow ? 'btn-unfollow' : 'btn-follow'; ?>"
            href="follow.php?id=<?php echo $membre['id_membre']; ?>">

            <?php if ($est_follow): ?>
                Ne plus suivre
            <?php else: ?>
                Suivre
            <?php endif; ?>

        </a>

    <?php endif; ?>

    <hr>

    <?php if ($est_proprietaire): ?>
        <h2>Poster un tweet</h2>
        <?php if ($erreur_tweet): ?>
            <p><?php echo htmlspecialchars($erreur_tweet); ?></p>
        <?php endif; ?>
        <form method="POST">
            <textarea name="contenu" rows="3" cols="50" maxlength="280" placeholder="Quoi de neuf ?"></textarea>
            <br>
            <button type="submit">Tweeter</button>
        </form>
        <hr>
    <?php endif; ?>

    <h2>Tweets de <?php echo htmlspecialchars($membre['identifiant']); ?></h2>

    <form method="GET">
        <input type="hidden" name="id" value="<?php echo $id_membre; ?>">
        <input type="text" name="q" value="<?php echo htmlspecialchars($recherche); ?>"
            placeholder="Rechercher dans ses tweets...">
        <button type="submit">Rechercher</button>
        <?php if ($recherche): ?>
            <a href="profil.php?id=<?php echo $id_membre; ?>">Effacer</a>
        <?php endif; ?>
    </form>

    <?php if ($recherche): ?>
        <p>Resultats pour : "<?php echo htmlspecialchars($recherche); ?>"</p>
    <?php endif; ?>

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

                        <a class="btn-like" href="like.php?id_tweet=<?php echo $tweet['id_tweet']; ?>">
                            ❤️ <?php echo $tweet['nb_likes']; ?>
                        </a>

                    </div>

                </li>
            <?php endforeach; ?>

            <?php if ($total_tweets > 4): ?>
                <li>...</li>
            <?php endif; ?>

        </ul>
    <?php endif; ?>

<?php include 'footer.php'; ?>