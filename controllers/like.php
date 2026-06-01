<?php

require_once '../connexion.php';
session_start();

if (!isset($_SESSION['id_membre'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id_tweet'])) {
    die('Tweet introuvable.');
}

$pdo = getConnexion();

$id_tweet = (int) $_GET['id_tweet'];
$id_membre = (int) $_SESSION['id_membre'];

// Vérifier si déjà liké
$stmt = $pdo->prepare('
    SELECT id_like
    FROM like_tweet
    WHERE id_membre = ? AND id_tweet = ?
');

$stmt->execute([$id_membre, $id_tweet]);

$like = $stmt->fetch();

if ($like) {

    // Unlike
    $stmt = $pdo->prepare('
        DELETE FROM like_tweet
        WHERE id_membre = ? AND id_tweet = ?
    ');

    $stmt->execute([$id_membre, $id_tweet]);

} else {

    // Like
    $stmt = $pdo->prepare('
        INSERT INTO like_tweet (id_membre, id_tweet)
        VALUES (?, ?)
    ');

    $stmt->execute([$id_membre, $id_tweet]);
}

// Retour page précédente
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;