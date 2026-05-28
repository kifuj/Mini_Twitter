
<?php
require_once 'connexion.php';
session_start();
 
$pdo = getConnexion();
 
// Récupérer le nombre total de membres
$total = $pdo->query('SELECT COUNT(*) FROM membre')->fetchColumn();
 
// Récupérer les 4 derniers membres inscrits
$stmt = $pdo->query('SELECT id_membre, identifiant FROM membre ORDER BY id_membre DESC LIMIT 4');
$derniers_membres = $stmt->fetchAll();
 
include 'views/index.html.php';
