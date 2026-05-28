<?php
require_once '../connexion.php';
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

$est_proprietaire = isset($_SESSION['id_membre']) && $_SESSION['id_membre'] === $id_membre;

$erreur_tweet = '';
if ($est_proprietaire && isset($_POST['contenu'])) {
    $contenu = trim($_POST['contenu']);
    if ($contenu === '') {
        $erreur_tweet = 'Le tweet ne peut pas etre vide.';
    } elseif (mb_strlen($contenu) > 280) {
        $erreur_tweet = 'Le tweet ne peut pas depasser 280 caracteres.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO tweet (contenu, id_membre) VALUES (?, ?)');
        $stmt->execute([$contenu, $id_membre]);
        header('Location: profil.php?id=' . $id_membre);
        exit;
    }
}

$recherche = trim($_GET['q'] ?? '');

$stmt_count = $pdo->prepare('SELECT COUNT(*) FROM tweet WHERE id_membre = ?' . ($recherche !== '' ? ' AND contenu LIKE ?' : ''));
if ($recherche !== '') {
    $stmt_count->execute([$id_membre, '%' . $recherche . '%']);
} else {
    $stmt_count->execute([$id_membre]);
}
$total_tweets = $stmt_count->fetchColumn();

if ($recherche !== '') {
    $stmt = $pdo->prepare('SELECT * FROM tweet WHERE id_membre = ? AND contenu LIKE ? ORDER BY date_tweet DESC LIMIT 4');
    $stmt->execute([$id_membre, '%' . $recherche . '%']);
} else {
    $stmt = $pdo->prepare('SELECT * FROM tweet WHERE id_membre = ? ORDER BY date_tweet DESC LIMIT 4');
    $stmt->execute([$id_membre]);
}

$tweets = $stmt->fetchAll();

include '../views/profil.html.php';