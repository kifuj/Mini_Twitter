<?php

require_once '../connexion.php';
session_start();

if (!isset($_SESSION['id_membre'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    die('Utilisateur introuvable.');
}

$pdo = getConnexion();

$follower_id = $_SESSION['id_membre'];
$followed_id = (int) $_GET['id'];

/*
|--------------------------------------------------------------------------
| Interdire de se follow soi-même
|--------------------------------------------------------------------------
*/

if ($follower_id === $followed_id) {
    header('Location: profil.php?id=' . $followed_id);
    exit;
}

/*
|--------------------------------------------------------------------------
| Vérifier si déjà follow
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare('
    SELECT *
    FROM follow
    WHERE follower_id = ?
      AND followed_id = ?
');

$stmt->execute([$follower_id, $followed_id]);

$follow = $stmt->fetch();

/*
|--------------------------------------------------------------------------
| Follow / Unfollow
|--------------------------------------------------------------------------
*/

if ($follow) {

    $stmt = $pdo->prepare('
        DELETE FROM follow
        WHERE follower_id = ?
          AND followed_id = ?
    ');

    $stmt->execute([$follower_id, $followed_id]);

} else {

    $stmt = $pdo->prepare('
        INSERT INTO follow (follower_id, followed_id)
        VALUES (?, ?)
    ');

    $stmt->execute([$follower_id, $followed_id]);
}

header('Location: profil.php?id=' . $followed_id);
exit;