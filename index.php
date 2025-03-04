<?php

require 'config.php';
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if($_SESSION['isLoggin']){
    
}else{
    header('Location:login.php');
}

if(isset($_POST['logout'])){

    session_unset();
    header('Location:login.php');
}



if (isset($_POST['add_to_cart'])) {


   

    $plat = [
        'id' => $_POST['plat_id'],
        'name' => $_POST['plat_name'],
        'price' => $_POST['plat_price'],
        'image' => $_POST['plat_image'],
        'quantity' => $_POST['quantity']
    ];

    $_SESSION['cart'][] = $plat;

    header("Location: index.php");
    exit();


}

if (isset($_POST['remove_from_cart'])) {
    $plat_id = $_POST['plat_id'];

    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $plat_id) {
            unset($_SESSION['cart'][$key]); 
            break;
        }
    }
    header("Location: index.php");
    exit();
}





$cuisinType = [];
$platCategories = [];
$filter = [];
$filterByType =[];
$filterByCategorie = [];


$sql = "SELECT * FROM plat";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$plats = $stmt->fetchAll(PDO::FETCH_ASSOC);



$platsToDisplay = $plats; 

if(isset($_POST['Search'])){

    if(!empty($_POST['type']) && !empty($_POST['categorie'])){

        foreach($plats as $plat){

            if($plat['categoriePlat'] === $_POST['categorie'] && $plat['TypeCuisine'] === $_POST['type']){

                
                $filter[] = $plat;
                


            }

        }



    }else if(!empty($_POST['type'])){

        foreach($plats as $plat){

            if($plat['TypeCuisine'] === $_POST['type']){

                
                $filterByType[] = $plat;
                

            }

        }


    }else if(!empty($_POST['categorie'])){


        foreach($plats as $plat){

            if($plat['categoriePlat'] === $_POST['categorie']){

                $filterByCategorie[] = $plat;
                
            }

        }
    }

    if(!empty($filter)){
        $platsToDisplay = $filter;
    } elseif (!empty($filterByType)) {
        $platsToDisplay = $filterByType;
    } elseif (!empty($filterByCategorie)) {
        $platsToDisplay = $filterByCategorie;
    }

}

$headerCategories = [];
$headertypes = [];

foreach($platsToDisplay as $plat){

    if (!in_array($plat['TypeCuisine'], $cuisinType)) {
        $cuisinType[] = $plat['TypeCuisine'];
    }

    if (!in_array($plat['categoriePlat'], $platCategories)) {
        $platCategories[] = $plat['categoriePlat'];
    }
    

}

foreach($plats as $plat){

    if (!in_array($plat['TypeCuisine'], $headertypes)) {
        $headertypes[] = $plat['TypeCuisine'];
    }

    if (!in_array($plat['categoriePlat'], $headerCategories)) {
        $headerCategories[] = $plat['categoriePlat'];
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

<header>

<div class="logo">
    <img src="img/solirestauLogo.png" alt="">
    <span>SoliRestaurant</span>
</div>

<div class="search">

    <form method="post">

        <select name="type" id="cuisineType">
            <option value="" selected disabled>Choose Cuisine Type</option>
            <?php foreach ($headertypes as $type): ?>

                <option value="<?=$type?>"><?=$type?></option>

            <?php endforeach; ?>    

        </select>

        <select name="categorie" id="categorie">
            <option value="" selected disabled>Choose Categorie</option>
            <?php foreach ($headerCategories as $category): ?>

                <option value="<?=$category?>"><?=$category?></option>

            <?php endforeach; ?>  
        </select>

        <input type="submit" value="Search" name="Search" id="searchBtn">
        <input type="submit" value="Reset" name="Reset" id="resetBtn">

    </form>

</div>


<form method="post" class="logcartForm">
    

<a href="cart.php"><img src="img/addToCart.png" alt=""></a>
<button name="logout">Logout</button>
</form>



</header>

<div class="mainSection">

        <div class="dark"></div>

        <h1>Searching For Plats <br> Made Easy.</h1>


    </div>

<?php foreach ($cuisinType as $type): ?>

    <div class="byCuisine">

        <h3><?=$type?></h3>

        <div class="CartsContainer">


        <?php foreach ($platsToDisplay as $plat): ?>


            <?php if ($plat['TypeCuisine'] == $type): ?>

                <?php
                $isInCart = false;
                foreach ($_SESSION['cart'] as $item) {
                    if ($item['id'] == $plat['idPlat']) {
                        $isInCart = true;
                        break;
                    }
                }
            ?>

            <div class="cart">

                <img src="<?=$plat['image']?>" alt="">                                                                                                                                                                                                                                                                                                                                  
                <h3><?=$plat['nomPlat']?></h3>
                <div class="platDetails">
    
                    <p><?=$plat['categoriePlat']?></p>
                    <span><?=$plat['prix']?>$</span>
    
    
                </div>
    
                <form method="post" id="commandeForm">
                    <input type="hidden" name="plat_id" value="<?=$plat['idPlat']?>">
                    <input type="hidden" name="plat_name" value="<?=$plat['nomPlat']?>">
                    <input type="hidden" name="plat_price" value="<?=$plat['prix']?>">
                    <input type="hidden" name="plat_image" value="<?=$plat['image']?>">
                    <input type="hidden" name="quantity" value="1">
                    <?php if ($isInCart): ?>
                        <button type="submit" name="remove_from_cart">Remove</button>
                    <?php else: ?>
                        <button type="submit" name="add_to_cart">Commande</button>
                    <?php endif; ?>
                </form>
                
            </div>

            <?php endif; ?> 


        <?php endforeach; ?>    

            
            

        </div>

    </div>

<?php endforeach; ?>
    
</body>
</html>