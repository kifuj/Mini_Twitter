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

$stmt = $pdo->prepare('SELECT * FROM tweet WHERE id_membre = ? ORDER BY date_tweet DESC LIMIT 4');
$stmt->execute([$id_membre]);
$tweets = $stmt->fetchAll();

include '../views/profil.html.php';