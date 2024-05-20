<?php
include("dbh.inc.php");

try {
    $stmt = $pdo->prepare("SELECT Name_of_product, descc, img, price FROM users");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gacha</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="listing.css">
</head>
<body>
    <div class="menu_bar">
        <h1 class="logo">Gacha<span>Fam.</span></h1>
        <ul>
            <li><a href="create.php">Create listing</a></li>
            <li><a href="cases.html">Cases</a></li>
            <li><a href="cart.html">Cart</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    
    <section class="SPECIAL">
        <h2>SPECIAL</h2>
        <div class="box-container">
            <?php foreach ($products as $product): ?>
            <div class="box">
                <img src="uploads/<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['Name_of_product']); ?>">
                <h3><?php echo htmlspecialchars($product['Name_of_product']); ?></h3>
                <p><?php echo htmlspecialchars($product['price']); ?></p>
                <p><?php echo htmlspecialchars($product['descc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <section class="best-deals">
        <h2>BEST DEALS</h2>
        <div class="box-container">
            <?php foreach ($products as $product): ?>
            <div class="box">
                <img src="uploads/<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['Name_of_product']); ?>">
                <h3><?php echo htmlspecialchars($product['Name_of_product']); ?></h3>
                <p><?php echo htmlspecialchars($product['price']); ?></p>
                <p><?php echo htmlspecialchars($product['descc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <section class="random">
        <h2>RANDOM</h2>
        <div class="box-container">
            <?php foreach ($products as $product): ?>
            <div class="box">
                <img src="uploads/<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['Name_of_product']); ?>">
                <h3><?php echo htmlspecialchars($product['Name_of_product']); ?></h3>
                <p><?php echo htmlspecialchars($product['price']); ?></p>
                <p><?php echo htmlspecialchars($product['descc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>