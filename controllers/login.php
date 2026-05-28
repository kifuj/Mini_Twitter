<?php
require_once "../connexion.php";
session_start();

if (isset($_SESSION["id_membre"])) {
    header("Location: ../index.php");
    exit;
}

$pdo = getConnexion();

include "../views/login.html.php";