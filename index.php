<?php
require_once 'connexion.php';
session_start();
 
$pdo = getConnexion();
 
include 'views/index.html.php';
