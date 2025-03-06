<?php
session_start();

require 'config.php';

if (!isset($_SESSION['isAdminLogin']) || $_SESSION['isAdminLogin'] !== true) {
    header('Location: admin_login.php');
    exit;
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-container">
    
    <aside class="sidebar">
        <h2>Tableau de Bord</h2>
        <ul>
            <li><a href="?section=statistiques">📊 Statistiques</a></li>
            <li><a href="?section=clients">👥 Clients</a></li>
            <li><a href="?section=commandes">📦 Commandes</a></li>
        </ul>

        <form method="POST" action="" class="logout-form">
            <button type="submit" name="logout" class="logout-btn">Logout</button>
        </form>
    </aside>

    <main class="content">
        <?php
        if (isset($_GET['section'])) {
            $section = $_GET['section'];
            
            if ($section === "clients") {
                include "admin_clients.php";
            } elseif ($section === "commandes") {
                include "admin_commandes.php";
            } elseif ($section === "statistiques") {
                include "admin_statistiques.php";
            } else {  
                include "admin_statistiques.php";
            }
        } else {
            include "admin_statistiques.php";
        }
        ?>
    </main>

</div>

</body>
</html>
