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
    $mdp_confirm = trim($_POST['mdp_confirm'] ?? '');
    $photo = null;

    if ($identifiant === '' || $mdp === '' || $mdp_confirm === '') {
        $erreur = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (mb_strlen($identifiant) < 3) {
        $erreur = 'L\'identifiant doit faire au moins 3 caracteres.';
    } elseif ($mdp !== $mdp_confirm) {
        $erreur = 'Les mots de passe ne correspondent pas.';
    } elseif (mb_strlen($mdp) < 6) {
        $erreur = 'Le mot de passe doit faire au moins 6 caracteres.';
    } else {
        $stmt = $pdo->prepare('SELECT id_membre FROM membre WHERE identifiant = ?');
        $stmt->execute([$identifiant]);
        if ($stmt->fetch()) {
            $erreur = 'Cet identifiant est deja utilise.';
        } else {
            if (!empty($_FILES['photo']['name'])) {
                $extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                $extensions_autorisees = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($extension, $extensions_autorisees)) {
                    $erreur = 'Format de photo non autorise.';
                } elseif ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
                    $erreur = 'La photo ne doit pas depasser 2 Mo.';
                } else {
                    $nom_fichier = uniqid('photo_') . '.' . $extension;
                    $dossier = '../uploads/';
                    if (!is_dir($dossier))
                        mkdir($dossier, 0755, true);
                    move_uploaded_file($_FILES['photo']['tmp_name'], $dossier . $nom_fichier);
                    $photo = $nom_fichier;
                }
            }

            if ($erreur === '') {
                $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO membre (identifiant, mdp, photo) VALUES (?, ?, ?)');
                $stmt->execute([$identifiant, $mdp_hash, $photo]);
                $id_nouveau = $pdo->lastInsertId();
                $_SESSION['id_membre'] = (int) $id_nouveau;
                $_SESSION['identifiant'] = $identifiant;
                header('Location: profil.php?id=' . $id_nouveau);
                exit;
            }
        }
    }
}

include '../views/register.html.php';