<?php
require_once 'connexion.php';
session_start();

$pdo = getConnexion();

// Récupérer le nombre total de membres
$total = $pdo->query('SELECT COUNT(*) FROM membre')->fetchColumn();

// Récupérer les 4 derniers membres inscrits
$stmt = $pdo->query('SELECT id_membre, identifiant, photo FROM membre ORDER BY id_membre DESC LIMIT 8');
$derniers_membres = $stmt->fetchAll();

// Derniers tweets
$stmt = $pdo->prepare('
    SELECT
        tweet.id_tweet,
        tweet.contenu,
        tweet.date_tweet,

        membre.id_membre,
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

    GROUP BY tweet.id_tweet

    ORDER BY tweet.date_tweet DESC

    LIMIT 5
');

$stmt->execute([
    $_SESSION['id_membre'] ?? 0
]);

$derniers_tweets = $stmt->fetchAll();


include 'views/index.html.php';