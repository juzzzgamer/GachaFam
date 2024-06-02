<?php
include("dbh.inc.php");
include("session.php");
try {
    $stmt = $pdo->prepare("SELECT game.game_name AS game_name,
     game.id AS game_id, game.img AS game_img,
     items.name AS item_name, 
     items.id AS item_id, items.img AS item_img 
     FROM game_items 
     LEFT JOIN game ON game_items.game_id = game.id 
     LEFT JOIN items ON game_items.item_id = items.id;");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
<title>box</title>
<link rel="stylesheet" href="product_page.css">
<link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="menu_bar">
        <h1 class="logo">Gacha<span>Fam.</span></a></h1>
        <ul>
        <li><a href="#" id="profile">Welcome, <span style="color:red"><?php echo ("$username")?></span></a></li>
            <li><a href="create.php">Create listing</a></li>
            <li><a href="cases.html">Cases</a></li>
            <li><a href="cart.html">Cart</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="container">
        <div class="col-1">
            <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="box" srcset="">
        </div>
        <div class="col-2">
            <div class="product-container">
                <p class="product-name">box</p>
                <p class="price">Price 5</p>
                <div class="btn">
                    <button class="btn1 btn-decrement">-</button>
                    <input type="text" id="quantity" class="btn-input" value="1">
                    <button class="btn1 btn-increment">+</button>
                    <div class="purchase">
                        <button id="purchase">buy</button>
                    </div>

                    <div class="total-amount">
                        <h1>total-amount</h1>
                        <p class="total-price">
                            <span id="price">
                                5 
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="product_img-container">
                <div class="product_img">
                    <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                    <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                    <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                    <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                    <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                    <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                    <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                    <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                    <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                    <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                </div>
            </div>
        </div>
    </div>
    <script src="gacha.js"></script>
</body>
</html>
