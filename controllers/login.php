<?php
require_once '../connexion.php';
session_start();

if (isset($_SESSION['id_membre'])) {
    header('Location: ../index.php');
    exit;
}

$pdo = getConnexion();
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = trim($_POST['identifiant'] ?? '');
    $mdp = trim($_POST['mdp'] ?? '');

    if ($identifiant === '' || $mdp === '') {
        $erreur = 'Veuillez remplir tous les champs.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM membre WHERE identifiant = ?');
        $stmt->execute([$identifiant]);
        $membre = $stmt->fetch();

        if ($membre && password_verify($mdp, $membre['mdp'])) {
            $_SESSION['id_membre'] = $membre['id_membre'];
            $_SESSION['identifiant'] = $membre['identifiant'];
            header('Location: profil.php?id=' . $membre['id_membre']);
            exit;
        } else {
            $erreur = 'Identifiant ou mot de passe incorrect.';
        }
    }
}

include '../views/login.html.php';