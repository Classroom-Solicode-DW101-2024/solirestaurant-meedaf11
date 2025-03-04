<?php

require 'config.php';
session_start();



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
                echo "<h2>Bienvenue dans l'administration</h2>";
            }
        } else {
            echo "<h2>Bienvenue dans l'administration</h2>";
        }
        ?>
    </main>

</div>

</body>
</html>
