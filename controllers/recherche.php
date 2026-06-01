<?php
require_once '../connexion.php';

$pdo = getConnexion();

$recherche = trim($_GET['q'] ?? '');
$membres   = [];
$tweets    = [];

if ($recherche !== '') {
    $stmt = $pdo->prepare('SELECT id_membre, identifiant, photo FROM membre WHERE identifiant LIKE ?');
    $stmt->execute(['%' . $recherche . '%']);
    $membres = $stmt->fetchAll();

    $stmt = $pdo->prepare('
    SELECT
        tweet.id_tweet,
        tweet.contenu,
        tweet.date_tweet,

        membre.id_membre,
        membre.identifiant,
        membre.photo,

        COUNT(like_tweet.id_like) AS nb_likes

    FROM tweet

    JOIN membre
        ON tweet.id_membre = membre.id_membre

    LEFT JOIN like_tweet
        ON tweet.id_tweet = like_tweet.id_tweet

    WHERE tweet.contenu LIKE ?

    GROUP BY tweet.id_tweet

    ORDER BY tweet.date_tweet DESC
');
    $stmt->execute(['%' . $recherche . '%']);
    $tweets = $stmt->fetchAll();
}

include '../views/recherche.html.php';