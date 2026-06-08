<?php include 'header.php'; ?>

<h1>Mon feed</h1>

<hr>

<?php if (empty($tweets)): ?>

    <p>Aucun tweet dans votre feed.</p>

<?php else: ?>

    <ul>

        <?php foreach ($tweets as $tweet): ?>

            <li class="tweet">

                <div class="tweet-left">

                    <?php if ($tweet['photo']): ?>

                        <img
                            class="tweet-avatar"
                            src="../uploads/<?php echo htmlspecialchars($tweet['photo']); ?>"
                        >

                    <?php else: ?>

                        <div class="tweet-avatar default-avatar"></div>

                    <?php endif; ?>

                    <a
                        class="tweet-user"
                        href="profil.php?id=<?php echo $tweet['id_membre']; ?>"
                    >

                        <?php echo htmlspecialchars($tweet['identifiant']); ?>

                    </a>

                </div>

                <div class="tweet-right">

                    <p class="tweet-content">
                        <?php echo formaterTweet($tweet['contenu']); ?>
                    </p>

                    <?php if (!empty($tweet['image'])): ?>
                        <img class="tweet-image" src="../uploads/<?php echo htmlspecialchars($tweet['image']); ?>" alt="image du tweet">
                    <?php endif; ?>

                    <small class="tweet-date">
                        <?php echo $tweet['date_tweet']; ?>
                    </small>

                    <br><br>

                    <a
                        class="btn-like"
                        href="like.php?id_tweet=<?php echo $tweet['id_tweet']; ?>"
                    >

                        ❤️ <?php echo $tweet['nb_likes']; ?>

                    </a>

                </div>

            </li>

        <?php endforeach; ?>

    </ul>

<?php endif; ?>

</body>
</html>