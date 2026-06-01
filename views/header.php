<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/style.css">
</head>
<body>

<div class="topbar">

    <div class="topbar-left">

        <a class="logo" href="/index.php">
            <h1>Mini Twitter</h1>
        </a>

    </div>

    <div class="topbar-right">

        <?php if (isset($_SESSION['id_membre'])): ?>

            <a class="nav-btn" href="/controllers/feed.php">
                Feed
            </a>

            <a
                class="nav-btn"
                href="/controllers/profil.php?id=<?php echo $_SESSION['id_membre']; ?>"
            >
                Mon profil
            </a>

            <a
                class="nav-btn"
                href ="/controllers/mon_compte.php?id=<?php echo $_SESSION['id_membre']; ?>"
            >
                Mon compte
            </a>

            <a class="nav-btn logout-btn" href="/controllers/logout.php">
                Déconnexion
            </a>

        <?php else: ?>

            <a class="nav-btn" href="/controllers/login.php">
                Connexion
            </a>

            <a class="nav-btn" href="/controllers/register.php">
                Inscription
            </a>

        <?php endif; ?>

    </div>

</div>