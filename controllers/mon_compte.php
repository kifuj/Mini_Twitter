<?php
require_once '../connexion.php';
session_start();

if (!isset($_SESSION['id_membre'])) {
    header('Location: login.php');
    exit;
}

$pdo = getConnexion();
$id_membre = (int) $_SESSION['id_membre'];

$stmt = $pdo->prepare('SELECT * FROM membre WHERE id_membre = ?');
$stmt->execute([$id_membre]);
$membre = $stmt->fetch();

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_FILES['photo']['name'])) {
        $erreur = 'Veuillez selectionner une photo.';
    } else {
        $extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $extensions_autorisees = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($extension, $extensions_autorisees)) {
            $erreur = 'Format non autorise (jpg, jpeg, png, gif uniquement).';
        } elseif ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
            $erreur = 'La photo ne doit pas depasser 2 Mo.';
        } else {
            $dossier = '../uploads/';
            $nom_fichier = uniqid('photo_') . '.' . $extension;

            if (!is_dir($dossier))
                mkdir($dossier, 0755, true);

            if ($membre['photo'] && file_exists($dossier . $membre['photo'])) {
                unlink($dossier . $membre['photo']);
            }

            move_uploaded_file($_FILES['photo']['tmp_name'], $dossier . $nom_fichier);

            $stmt = $pdo->prepare('UPDATE membre SET photo = ? WHERE id_membre = ?');
            $stmt->execute([$nom_fichier, $id_membre]);

            $succes = 'Photo mise a jour avec succes.';

            $stmt = $pdo->prepare('SELECT * FROM membre WHERE id_membre = ?');
            $stmt->execute([$id_membre]);
            $membre = $stmt->fetch();
        }
    }
}

include '../views/mon_compte.html.php';