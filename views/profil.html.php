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
            <p class="erreur"><?php echo htmlspecialchars($erreur_tweet); ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="tweet-form">

            <!-- Barre d'outils -->
            <div class="editor-toolbar">
                <button type="button" onclick="formatText('bold')" title="Gras"><b>G</b></button>
                <button type="button" onclick="formatText('italic')" title="Italique"><i>I</i></button>
                <button type="button" onclick="toggleEmojiPicker()" title="Emojis">😀</button>
            </div>

            <!-- Picker d'emojis -->
            <div id="emoji-picker" style="display:none;" class="emoji-picker">
                <?php
                $emojis = ['😀','😂','😍','🔥','👍','❤️','😎','🎉','💯','🤔','😢','😡','👏','🙏','✨','💪','🚀','😴','🤣','😊'];
                foreach ($emojis as $e): ?>
                    <span onclick="insertEmoji('<?php echo $e; ?>')"><?php echo $e; ?></span>
                <?php endforeach; ?>
            </div>

            <textarea
                id="tweet-editor"
                name="contenu"
                rows="3"
                maxlength="280"
                placeholder="Quoi de neuf ? Utilise @pseudo pour mentionner quelqu'un"
            ></textarea>

            <div class="editor-footer">
                <label class="btn-upload" for="image_tweet">📎 Joindre une image</label>
                <input type="file" id="image_tweet" name="image_tweet" accept="image/*" style="display:none"
                    onchange="previewImage(this)">
                <span id="char-count">280</span>
            </div>

            <!-- Prévisualisation image -->
            <div id="image-preview" style="display:none; margin-top:8px;">
                <img id="preview-img" src="" alt="preview" style="max-width:100%; max-height:200px; border-radius:8px;">
                <button type="button" onclick="removeImage()" style="margin-left:8px;">✕ Retirer</button>
            </div>

            <br>
            <button type="submit">Tweeter</button>
        </form>

        <script>
        // Compteur de caractères
        document.getElementById('tweet-editor').addEventListener('input', function() {
            document.getElementById('char-count').textContent = 280 - this.value.length;
        });

        // Gras / Italique via les marqueurs **texte** et _texte_
        function formatText(type) {
            const ta = document.getElementById('tweet-editor');
            const start = ta.selectionStart;
            const end = ta.selectionEnd;
            const selected = ta.value.substring(start, end);
            let result = '';

            if (type === 'bold')   result = '**' + (selected || 'texte') + '**';
            if (type === 'italic') result = '_'  + (selected || 'texte') + '_';

            ta.value = ta.value.substring(0, start) + result + ta.value.substring(end);
            ta.focus();
        }

        // Picker d'emojis
        function toggleEmojiPicker() {
            const p = document.getElementById('emoji-picker');
            p.style.display = p.style.display === 'none' ? 'flex' : 'none';
        }

        function insertEmoji(emoji) {
            const ta = document.getElementById('tweet-editor');
            const pos = ta.selectionStart;
            ta.value = ta.value.substring(0, pos) + emoji + ta.value.substring(pos);
            ta.focus();
            document.getElementById('emoji-picker').style.display = 'none';
        }

        // Prévisualisation image
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            document.getElementById('image_tweet').value = '';
            document.getElementById('image-preview').style.display = 'none';
        }
        </script>
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
                            <?php echo formaterTweet($tweet['contenu']); ?>
                        </p>

                        <?php if (!empty($tweet['image'])): ?>
                            <img class="tweet-image" src="../uploads/<?php echo htmlspecialchars($tweet['image']); ?>" alt="image du tweet">
                        <?php endif; ?>

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