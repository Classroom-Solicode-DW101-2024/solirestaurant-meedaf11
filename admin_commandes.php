<?php
require 'config.php';

if (isset($_POST['ChangeStatutBtn'])) {
    if (!empty($_POST['changeStatus'])) {
        $statutValue = $_POST['changeStatus'];
        $idCmdValue = $_POST['idCmdFromChange'];
        $idClValue = $_POST['idClFromChange'];

        $sqlSelect = "UPDATE commande SET statut = :selected WHERE idCl = :idClient AND idCmd = :idCommande;";
        $stmtSelect = $pdo->prepare($sqlSelect);

        $stmtSelect->bindParam(':selected', $statutValue);
        $stmtSelect->bindParam(':idCommande', $idCmdValue);
        $stmtSelect->bindParam(':idClient', $idClValue);

        $stmtSelect->execute();
    }
}

$sql = "SELECT c.idCmd, c.idCl, c.dateCmd, c.statut, cl.nomCl, cl.prenomCl FROM commande c JOIN client cl ON c.idCl = cl.idClient";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// دالة لاسترجاع اسم العميل بناءً على ID
function searchForClientById($idClient) {
    global $pdo;
    $sql = "SELECT * FROM client WHERE idClient = :idClient";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idClient', $idClient);
    $stmt->execute();
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    return $client['nomCl'] . ' ' . $client['prenomCl'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Commandes</title>
    <link rel="stylesheet" href="admin_commandes.css">
</head>
<body>

    <div class="commandsTableContainer">
        <h2>All Commandes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Command</th>
                    <th>Client</th>
                    <th>Date/Hour</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td><?= $commande['idCmd'] ?></td>
                        <td><?= searchForClientById($commande['idCl']) ?></td>
                        <td><?= $commande['dateCmd'] ?></td>
                        <td><?= $commande['statut'] ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="idCmdFromChange" value="<?= $commande['idCmd'] ?>">
                                <input type="hidden" name="idClFromChange" value="<?= $commande['idCl'] ?>">
                                <select name="changeStatus">
                                    <option value="en attente" <?= ($commande['statut'] === 'en attente') ? 'selected' : '' ?>>en attente</option>
                                    <option value="en cours" <?= ($commande['statut'] === 'en cours') ? 'selected' : '' ?>>en cours</option>
                                    <option value="expédiée" <?= ($commande['statut'] === 'expédiée') ? 'selected' : '' ?>>expédiée</option>
                                    <option value="livrée" <?= ($commande['statut'] === 'livrée') ? 'selected' : '' ?>>livrée</option>
                                    <option value="annulée" <?= ($commande['statut'] === 'annulée') ? 'selected' : '' ?>>annulée</option>
                                </select>
                                <button name="ChangeStatutBtn">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<!-- إضافة تنسيق CSS -->

