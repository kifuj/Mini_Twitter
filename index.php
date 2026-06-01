<?php
require_once 'connexion.php';
session_start();

$pdo = getConnexion();

// Récupérer le nombre total de membres
$total = $pdo->query('SELECT COUNT(*) FROM membre')->fetchColumn();

// Récupérer les 4 derniers membres inscrits
$stmt = $pdo->query('SELECT id_membre, identifiant FROM membre ORDER BY id_membre DESC LIMIT 4');
$derniers_membres = $stmt->fetchAll();

// Derniers tweets
    $stmt = $pdo->query('
        SELECT tweet.contenu, tweet.date_tweet,
                membre.id_membre, membre.identifiant
        FROM tweet
        JOIN membre ON tweet.id_membre = membre.id_membre
        ORDER BY tweet.date_tweet DESC
        LIMIT 5
    ');
    $derniers_tweets = $stmt->fetchAll();


include 'views/index.html.php';