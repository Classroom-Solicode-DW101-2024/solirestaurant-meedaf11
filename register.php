<?php

require 'config.php';

session_start();

if(isset($_POST['register'])){

    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $phoneNumber = trim($_POST['phone']);

    $sql = "SELECT * FROM `client` WHERE telCl = :tel";
    $stmt = $pdo-> prepare($sql);
    $stmt-> bindParam(':tel', $phoneNumber);
    $stmt -> execute();
    $results = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!empty($results)){
        echo('This Account Is Already Exist Please Login in or use new Phone Number');
    }else{

        $isIdAvailable = false;
        

        do{
            $user_id = mt_rand(1000000, 9999999);
            $id_sql = "SELECT * FROM `client` WHERE idClient = :id";
            $idStmt = $pdo-> prepare($id_sql);
            $idStmt-> bindParam(':id', $user_id);
            $idStmt -> execute();
            $idresults = $idStmt->fetch(PDO::FETCH_ASSOC);

            if(!empty($idresults)){
                $isIdAvailable = true;
            }

        }while($isIdAvailable);

        $insert_Sql = "INSERT INTO client values(:id,:nom,:prenom,:tel)";
        $insertStmt = $pdo->prepare($insert_Sql);
        $insertStmt-> bindParam(':id', $user_id);
        $insertStmt-> bindParam(':nom', $lastName);
        $insertStmt-> bindParam(':prenom', $firstName);
        $insertStmt-> bindParam(':tel', $phoneNumber);
        $insertStmt -> execute();

        $_SESSION['id'] = $user_id;
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
        $_SESSION['phoneNumber'] = $phoneNumber;
        $_SESSION['isLoggin'] = true;

        header('Location:index.php');


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
            
            <div class="form-box" id="register-form">
                <h3>Register</h3>
                <form method="post">
                    <input type="text" name="first_name" placeholder="First Name" required><br>
                    <input type="text" name="last_name" placeholder="Last Name" required><br>
                    <input type="tel" name="phone" placeholder="Phone Number" required><br>
                    <button type="submit" name="register">Register</button>
                </form>
                
                <a href="login.php">Have an Acoount ? Login</a>
            </div>
       
    </div>

    
</body>
</html>