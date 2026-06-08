<?php

require_once '../connexion.php';
session_start();

if (!isset($_SESSION['id_membre'])) {
    header('Location: login.php');
    exit;
}

$pdo = getConnexion();

$id_membre = $_SESSION['id_membre'];



$stmt = $pdo->prepare('
    SELECT
        tweet.id_tweet,
        tweet.contenu,
        tweet.date_tweet,

        membre.id_membre,
        membre.identifiant,
        membre.photo,

        COUNT(like_tweet.id_like) AS nb_likes,

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

       OR tweet.id_membre IN (

            SELECT followed_id
            FROM follow
            WHERE follower_id = ?
       )

    GROUP BY tweet.id_tweet

    ORDER BY tweet.date_tweet DESC
');

$stmt->execute([
    $_SESSION['id_membre'],
    $id_membre,
    $id_membre
]);

$tweets = $stmt->fetchAll();

include '../views/feed.html.php';