<?php
require 'config.php';
session_start();

if (!isset($_SESSION['isLoggin']) || !$_SESSION['isLoggin']) {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Your cart is empty.");
}

if (isset($_POST['update_quantity'])) {
    $plat_id = $_POST['plat_id'];
    $new_quantity = (int) $_POST['quantity'];

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $plat_id) {
            $item['quantity'] = max(1, $new_quantity);
            break;
        }
    }
    unset($item);
}

if (isset($_POST['remove_from_cart'])) {
    $plat_id = $_POST['plat_id'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $plat_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

$totalPrice = 0;
foreach ($_SESSION['cart'] as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

if (isset($_POST['validate_order'])) {

    try {
        $pdo->beginTransaction();

        $sql = "SELECT MAX(idCmd) AS lastId FROM commande";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $lastId = $stmt->fetchColumn();

        if ($lastId) {
            $idCmd = $lastId + 1;
        } else {
            $idCmd = 1;
        }

        $sql = "INSERT INTO commande (idCmd, dateCmd, Statut, idCl) VALUES (:idCmd, NOW(), 'en attente', :idCl)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idCmd', $idCmd);
        $stmt->bindParam(':idCl', $_SESSION['id']);
        $stmt->execute();

        foreach ($_SESSION['cart'] as $item) {
            $sql = "INSERT INTO commande_plat (idPlat, idCmd, qte) VALUES (:idPlat, :idCmd, :qte)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':idPlat', $item['id']);
            $stmt->bindParam(':idCmd', $idCmd);
            $stmt->bindParam(':qte', $item['quantity']);
            $stmt->execute();
        }

        $pdo->commit();

        unset($_SESSION['cart']);


    } catch (Exception $e) {
        $pdo->rollBack();
        echo "حدث خطأ أثناء تأكيد الطلب: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>

    <div class="mainSection">
        <h2 class="cartTitle">My Cart</h2>

        <div class="cartContainer">
            <table border="1">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <tr>
                                <td><img src="<?= htmlspecialchars($item['image']) ?>" width="50"></td>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td><?= number_format($item['price'], 2) ?>$</td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="plat_id" value="<?= $item['id'] ?>">
                                        <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1">
                                        <button type="submit" name="update_quantity">Update</button>
                                    </form>
                                </td>
                                <td><?= number_format($item['price'] * $item['quantity'], 2) ?>$</td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="plat_id" value="<?= $item['id'] ?>">
                                        <button type="submit" name="remove_from_cart">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">Your cart is empty.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <br>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3>Total Price: <?= number_format($totalPrice, 2) ?>$</h3>
                <form method="post">
                    <button type="submit" name="validate_order">Validate Order</button>
                </form>
            </div>

        </div>
    </div>

</body>
</html>
