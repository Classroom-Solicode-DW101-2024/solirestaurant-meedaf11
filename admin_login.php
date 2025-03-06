<?php

session_start();


if (isset($_SESSION['isAdminLogin']) && $_SESSION['isAdminLogin'] === true) {
    header('Location: admin.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $username = $_POST['username'];
    $password = $_POST['password'];

 
    if ($username === 'admin' && $password === 'admin') {
       
        $_SESSION['isAdminLogin'] = true;
        header('Location: admin.php'); 
        exit;
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin_login.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error_message)): ?>
            <p style="color:red;"><?= $error_message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="input-container">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="input-container">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
