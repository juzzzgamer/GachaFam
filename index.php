<?php
include("dbh.inc.php");
include("session.php");
try {
    $stmt = $pdo->prepare("SELECT id, game_name, game_desc, img, price FROM game");
    $stmt->execute();
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <a href="index.php" class="logo"><h3>Gacha<span>Fam.</span></h3></a>
        <ul>
        <li><a href="#" id="profile">Welcome, <span style="color:red"><?php echo ("$username")?></span></a></li>
            <li><a href="create.php">Create game</a></li>
            <li><a href="cases.html">Cases</a></li>
            <li><a href="cart.html">Cart</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    
    <section class="SPECIAL">
        <h2>SPECIAL</h2>
        <div class="box-container">
            <?php foreach ($games as $game): ?>
            <a href="gacha.php?id=<?php echo urlencode($game['id']); ?>" class="box-link" style="text-decoration: none;">
            <div class="box">
                <img src="upload/<?php echo htmlspecialchars($game['img']); ?>" alt="<?php echo htmlspecialchars($game['game_name']); ?>">
                <h3><?php echo htmlspecialchars($game['game_name']); ?></h3>
                <p><?php echo htmlspecialchars($game['price']); ?>$</p>
                <p><?php echo htmlspecialchars($game['game_desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>