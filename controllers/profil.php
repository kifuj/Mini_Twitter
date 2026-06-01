<?php
require_once '../connexion.php';
session_start();

$pdo = getConnexion();

if (!isset($_GET['id'])) {
    die('Membre non specifie.');
}

$id_membre = (int) $_GET['id'];

/*
|--------------------------------------------------------------------------
| Recuperation du membre
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare('SELECT * FROM membre WHERE id_membre = ?');
$stmt->execute([$id_membre]);

$membre = $stmt->fetch();

if (!$membre) {
    die('Membre introuvable.');
}

/*
|--------------------------------------------------------------------------
| Verification proprietaire du profil
|--------------------------------------------------------------------------
*/

$est_proprietaire = isset($_SESSION['id_membre'])
    && $_SESSION['id_membre'] === $id_membre;


/*
|--------------------------------------------------------------------------
| Verification follow
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Nombre d'abonnes
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare('
    SELECT COUNT(*)
    FROM follow
    WHERE followed_id = ?
');

$stmt->execute([$id_membre]);

$nb_followers = $stmt->fetchColumn();

/*
|--------------------------------------------------------------------------
| Nombre d'abonnements
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare('
    SELECT COUNT(*)
    FROM follow
    WHERE follower_id = ?
');

$stmt->execute([$id_membre]);

$nb_following = $stmt->fetchColumn();
/*
|--------------------------------------------------------------------------
| Ajout d'un tweet
|--------------------------------------------------------------------------
*/

$erreur_tweet = '';

if ($est_proprietaire && isset($_POST['contenu'])) {

    $contenu = trim($_POST['contenu']);

    if ($contenu === '') {

        $erreur_tweet = 'Le tweet ne peut pas etre vide.';

    } elseif (mb_strlen($contenu) > 280) {

        $erreur_tweet = 'Le tweet ne peut pas depasser 280 caracteres.';

    } else {

        $stmt = $pdo->prepare('
            INSERT INTO tweet (contenu, id_membre)
            VALUES (?, ?)
        ');

        $stmt->execute([$contenu, $id_membre]);

        header('Location: profil.php?id=' . $id_membre);
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| Recherche
|--------------------------------------------------------------------------
*/

$recherche = trim($_GET['q'] ?? '');

/*
|--------------------------------------------------------------------------
| Nombre total de tweets
|--------------------------------------------------------------------------
*/

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

            COUNT(like_tweet.id_like) AS nb_likes

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
        $id_membre,
        '%' . $recherche . '%'
    ]);

} else {

    $stmt = $pdo->prepare('
        SELECT
            tweet.*,

            membre.identifiant,
            membre.photo,

            COUNT(like_tweet.id_like) AS nb_likes

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

    $stmt->execute([$id_membre]);
}

$tweets = $stmt->fetchAll();
/*
|--------------------------------------------------------------------------
| Vue
|--------------------------------------------------------------------------
*/

include '../views/profil.html.php';