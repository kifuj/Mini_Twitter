<?php
require_once '../connexion.php';
require_once '../helpers.php';
session_start();

$pdo = getConnexion();

if (!isset($_GET['id'])) {
    die('Membre non specifie.');
}

$id_membre = (int) $_GET['id'];



$stmt = $pdo->prepare('SELECT * FROM membre WHERE id_membre = ?');
$stmt->execute([$id_membre]);

$membre = $stmt->fetch();

if (!$membre) {
    die('Membre introuvable.');
}


$est_proprietaire = isset($_SESSION['id_membre'])
    && $_SESSION['id_membre'] === $id_membre;


$est_follow = false;

if (isset($_SESSION['id_membre']) && !$est_proprietaire) {

    $stmt = $pdo->prepare('
        SELECT *
        FROM follow
        WHERE follower_id = ?
          AND followed_id = ?
    ');

    $stmt->execute([
        $_SESSION['id_membre'],
        $id_membre
    ]);

    $est_follow = (bool) $stmt->fetch();
}   


$stmt = $pdo->prepare('
    SELECT COUNT(*)
    FROM follow
    WHERE followed_id = ?
');

$stmt->execute([$id_membre]);

$nb_followers = $stmt->fetchColumn();


$stmt = $pdo->prepare('
    SELECT COUNT(*)
    FROM follow
    WHERE follower_id = ?
');

$stmt->execute([$id_membre]);

$nb_following = $stmt->fetchColumn();

$erreur_tweet = '';

if ($est_proprietaire && isset($_POST['contenu'])) {

    $contenu = trim($_POST['contenu']);

    if ($contenu === '') {

        $erreur_tweet = 'Le tweet ne peut pas etre vide.';

    } elseif (mb_strlen($contenu) > 280) {

        $erreur_tweet = 'Le tweet ne peut pas depasser 280 caracteres.';

    } else {

        $image_tweet = null;

        // Gestion de l'image jointe
        if (!empty($_FILES['image_tweet']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image_tweet']['name'], PATHINFO_EXTENSION));
            $exts_ok = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($ext, $exts_ok)) {
                $erreur_tweet = 'Format image non autorisé (jpg, png, gif, webp).';
            } elseif ($_FILES['image_tweet']['size'] > 5 * 1024 * 1024) {
                $erreur_tweet = 'L\'image ne doit pas dépasser 5 Mo.';
            } else {
                $dossier = '../uploads/';
                if (!is_dir($dossier)) mkdir($dossier, 0755, true);
                $nom_fichier = uniqid('tweet_') . '.' . $ext;
                move_uploaded_file($_FILES['image_tweet']['tmp_name'], $dossier . $nom_fichier);
                $image_tweet = $nom_fichier;
            }
        }

        if ($erreur_tweet === '') {

            $stmt = $pdo->prepare('
                INSERT INTO tweet (contenu, image, id_membre)
                VALUES (?, ?, ?)
            ');

            $stmt->execute([$contenu, $image_tweet, $id_membre]);

            header('Location: profil.php?id=' . $id_membre);
            exit;
        }
    }
}


$recherche = trim($_GET['q'] ?? '');


$stmt_count = $pdo->prepare(
    'SELECT COUNT(*) FROM tweet WHERE id_membre = ?'
    . ($recherche !== '' ? ' AND contenu LIKE ?' : '')
);

if ($recherche !== '') {

    $stmt_count->execute([
        $id_membre,
        '%' . $recherche . '%'
    ]);

} else {

    $stmt_count->execute([$id_membre]);
}

$total_tweets = $stmt_count->fetchColumn();

/*
|--------------------------------------------------------------------------
| Recuperation des tweets + likes
|--------------------------------------------------------------------------
*/

if ($recherche !== '') {

    $stmt = $pdo->prepare('
        SELECT
            tweet.*,

            membre.identifiant,
            membre.photo,

            COUNT(DISTINCT like_tweet.id_like) AS nb_likes,

MAX(
    CASE
        WHEN like_tweet.id_membre = ?
        THEN 1
        ELSE 0
    END
) AS deja_like

        FROM tweet

        JOIN membre
            ON tweet.id_membre = membre.id_membre

        LEFT JOIN like_tweet
            ON tweet.id_tweet = like_tweet.id_tweet

        WHERE tweet.id_membre = ?
          AND tweet.contenu LIKE ?

        GROUP BY tweet.id_tweet

        ORDER BY tweet.date_tweet DESC

        LIMIT 4
    ');

    $stmt->execute([
        $_SESSION['id_membre'] ?? 0,
        $id_membre,
        '%' . $recherche . '%'
    ]);

} else {

    $stmt = $pdo->prepare('
        SELECT
            tweet.*,

            membre.identifiant,
            membre.photo,

            COUNT(DISTINCT like_tweet.id_like) AS nb_likes,

            MAX(
                CASE
                WHEN like_tweet.id_membre = ?
                THEN 1
                ELSE 0
            END
            ) AS deja_like

        FROM tweet

        JOIN membre
            ON tweet.id_membre = membre.id_membre

        LEFT JOIN like_tweet
            ON tweet.id_tweet = like_tweet.id_tweet

        WHERE tweet.id_membre = ?

        GROUP BY tweet.id_tweet

        ORDER BY tweet.date_tweet DESC

        LIMIT 4
    ');

    $stmt->execute([
    $_SESSION['id_membre'] ?? 0,
    $id_membre
    ]);
}

$tweets = $stmt->fetchAll();


include '../views/profil.html.php';