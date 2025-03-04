<?php 
require 'config.php';

session_start();

if(isset($_POST['login'])){

    $phoneNumber = trim($_POST['phone']);

    $sql = "SELECT * FROM `client` WHERE telCl = :tel";
    $stmt = $pdo-> prepare($sql);
    $stmt-> bindParam(':tel', $phoneNumber);
    $stmt -> execute();
    $results = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!empty($results)){

        $_SESSION['id'] = $results['idClient'];
        $_SESSION['firstName'] = $results['prenomCl'];
        $_SESSION['lastName'] = $results['nomCl'];
        $_SESSION['phoneNumber'] = $results['telCl'];
        $_SESSION['isLoggin'] = true;

        header('Location:index.php');

    }else{
        echo('This Phone Number is Not Available Please Sign Up');
    }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Red+Hat+Text:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">

</head>
<body>

    <div class="loginFormBody">

        <h2 class="formTitle">Welcome To SoliRestaurant</h2>
            
            <div class="form-box" id="login-form">
                <h3>Enter Your Phone To Logining in</h3>
                <form method="post">
                    <input type="text" name="phone" placeholder="Phone Number" required><br>
                    <button type="submit" name="login">Login</button>
                </form>
                
                <a href="register.php">Dont Have Acoount ? Register</a>
            </div>
       
    </div>

    
</body>
</html>