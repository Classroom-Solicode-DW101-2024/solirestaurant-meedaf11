<?php
require 'config.php';


$sql = "SELECT idClient, nomCl, prenomCl, telCl FROM client";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Clients</title>
    <link rel="stylesheet" href="admin_clients.css"> 
</head>
<body>

    <div class="clientsTableContainer">
        <h2>All Clients</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Client</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Tel</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= $client['idClient'] ?></td>
                        <td><?= $client['nomCl'] ?></td>
                        <td><?= $client['prenomCl'] ?></td>
                        <td><?= $client['telCl'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
