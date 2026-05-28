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
        SELECT tweet.id_tweet, tweet.contenu, tweet.date_tweet,
               membre.id_membre, membre.identifiant
        FROM tweet
        JOIN membre ON tweet.id_membre = membre.id_membre
        WHERE tweet.contenu LIKE ?
        ORDER BY tweet.date_tweet DESC
    ');
    $stmt->execute(['%' . $recherche . '%']);
    $tweets = $stmt->fetchAll();
}

include '../views/recherche.html.php';