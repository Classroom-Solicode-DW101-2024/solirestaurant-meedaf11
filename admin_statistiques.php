<?php

if(isset($_POST['ChangeStatutBtn'])){

    if(!empty($_POST['changeStatus'])){

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

    $selectedDate = date('Y-m-d');
    if (isset($_POST['submitDate'])) {
        $selectedDate = $_POST['selectedDate'];
    }

    $sqlPlat = "SELECT p.idPlat, c.idCmd, cp.qte FROM commande_plat cp JOIN commande c ON c.idCmd = cp.idCmd 
                JOIN plat p ON p.idPlat = cp.idPlat
                WHERE DATE(c.dateCmd) = :selectedDate;";
    $stmtPlat = $pdo->prepare($sqlPlat);
    $stmtPlat->bindParam(':selectedDate', $selectedDate);
    $stmtPlat->execute();
    $plats = $stmtPlat->fetchAll(PDO::FETCH_ASSOC);


    function getTotalOrders(){

        global $pdo;
        $sql = "SELECT COUNT(idCmd) AS totalOrders FROM commande;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);

        return $count['totalOrders'];
    }

    function getTotalClients(){

        global $pdo;
        $sql = "SELECT COUNT(idClient) AS totalClient FROM client";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $clients = $stmt->fetch(PDO::FETCH_ASSOC);

        return $clients['totalClient'];
    }

    function getTotalPLats(){

        global $pdo;
        $sql = "SELECT COUNT(idPlat) AS totalPlats FROM plat;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $plats = $stmt->fetch(PDO::FETCH_ASSOC);

        return $plats['totalPlats'];
    }
    
    function getTotalCanceled(){

        global $pdo;
        $sql = "SELECT COUNT(idCmd) as totalCanceledOrders FROM commande WHERE Statut='annulée'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $canceledOrders = $stmt->fetch(PDO::FETCH_ASSOC);

        return $canceledOrders['totalCanceledOrders'];
    }


    function getTodayCommands(){

        global $pdo;
        $sql = "SELECT * FROM commande WHERE DATE(dateCmd) = CURDATE();";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $todayOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $todayOrders;


    }

    function searchForClientById($idClient){

        global $pdo;
        $sql = "SELECT * FROM client WHERE idClient = $idClient;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
       
        $clientFullName = $client['nomCl'] . ' ' .  $client['prenomCl'];


        return $clientFullName;

    }

    function searchForPlatById($idPlat){

        global $pdo;
        $sql = "SELECT nomPlat FROM plat WHERE idPlat =  $idPlat;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $plat = $stmt->fetch(PDO::FETCH_ASSOC);

        $NameOfPlat = $plat['nomPlat'];


        return $NameOfPlat;

    }



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="admin_statistiques.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Red+Hat+Text:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">

</head>
<body>

    <div class="StatisticsDashboard">
        <div class="statsCarts">
            <h3>Total Orders</h3>
            <span><?= getTotalOrders() ?></span>
        </div>
        <div class="statsCarts">
            <h3>Total Clients</h3>
            <span><?= getTotalClients() ?></span>
        </div>
        <div class="statsCarts">
            <h3>Total Plats</h3>
            <span><?= getTotalPLats() ?></span>
        </div>
        <div class="statsCarts">
            <h3>Canceled Orders</h3>
            <span><?= getTotalCanceled() ?></span>
        </div>
    </div>

    <div class="statisticsOrdersOfToday">

        <h2>The Orders Of Today</h2>
        <table>

            <tr>
                <th>Order Id</th>
                <th>Client</th>
                <th>Date/Hour</th>
                <th>Statut</th>
                <th>Edit</th>
            </tr>

            <?php foreach (getTodayCommands() as $order): ?>

                <tr>

                    <td><?= $order['idCmd']?></td>
                    <td><?= searchForClientById($order['idCl'])?></td>
                    <td><?= $order['dateCmd']?></td>
                    <td><?= $order['statut']?></td>
                    <td><div class="changeStatus">
                        <form method="POST">
                            <input type="hidden" name="idCmdFromChange" value="<?=$order['idCmd']?>">
                            <input type="hidden" name="idClFromChange" value="<?=$order['idCl']?>">
                            <select name="changeStatus" id="changeStatus">
                                <option value="en attente" <?= ($order['statut'] === 'en attente') ? 'selected' : '' ?>>en attente</option>
                                <option value="en cours" <?= ($order['statut'] === 'en cours') ? 'selected' : '' ?>>en cours</option>
                                <option value="expédiée" <?= ($order['statut'] === 'expédiée') ? 'selected' : '' ?>>expédiée</option>
                                <option value="livrée" <?= ($order['statut'] === 'livrée') ? 'selected' : '' ?>>livrée</option>
                                <option value="annulée" <?= ($order['statut'] === 'annulée') ? 'selected' : '' ?>>annulée</option>
                            </select>
                            <button name="ChangeStatutBtn">Update</button>
                        </form>
                    </div></td>
                </tr>

            <?php endforeach; ?>
        </table>

    </div>

    <div class="showPlatByDay">

        <h2>The Most Popular Dishes BY Day</h2>

            <form method="POST">
                
                <div class="dateContainer">
                    <label for="selectedDate">Choose The Date</label>
                    <input type="date" name="selectedDate" id="selectedDate" required>
                </div>
                
                <button id="submitDate" type="submit" name="submitDate">Show Dishes</button>
            </form>

            <?php if (isset($plats) && !empty($plats)): ?>
                <table>
                    <tr>
                        <th>Commande Id</th>
                        <th>Dishe Name</th>
                        <th>Quantite</th>
                    </tr>
                    <?php foreach ($plats as $plat): ?>
                        <tr>
                            <td><?= $plat['idCmd']  ?></td>
                            <td><?= searchForPlatById($plat['idPlat']) ?></td>
                            <td><?= $plat['qte'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Commande Id</th>
                        <th>Dishe Name</th>
                        <th>Quantite</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td><p>The Dishes Of This Day is not exist ??</p></tr></td>
                        <td></td>
                    </tr>
                    
                </table>
            <?php endif; ?>

    </div>

    
</body>
</html>