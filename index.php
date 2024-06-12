<?php
include("dbh.inc.php");
include("session.php");

try {
    $stmt = $pdo->prepare("SELECT game.id, game.game_name, game.game_desc, game.img, game.price, user.credits 
        FROM game
        LEFT JOIN user ON game.user_id = user.id
    ");
    $stmt->execute();
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $gamestmt = $pdo->prepare("SELECT DISTINCT game_id FROM game_items");
    $gamestmt->execute();
    $game_ids = $gamestmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($game_ids as $game_id) {
        $stockstmt = $pdo->prepare("SELECT SUM(items.stock) as stock_sum FROM items INNER JOIN game_items ON items.id = game_items.item_id WHERE game_items.game_id = :game_id");
        $stockstmt->bindParam(':game_id', $game_id);
        $stockstmt->execute();
        $stock_sum = $stockstmt->fetch(PDO::FETCH_ASSOC)['stock_sum'];

        if ($stock_sum == 0) {
            $deletestmt = $pdo->prepare("DELETE FROM game WHERE id = :game_id");
            $deletestmt->bindParam(':game_id', $game_id);
            $deletestmt->execute();
            header("Refresh:1");
        }
    }
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gacha Game</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="listing.css">
</head>
<body>
    <div class="menu_bar">
        <a href="index.php" class="logo"><h3>Gacha<span>Fam.</span></h3></a>
        <ul>
            <li>Your credits: <?php echo htmlspecialchars($_SESSION['user_credits'] ); ?></li>
            <li><a href="#" id="profile">Welcome, <span style="color:red"><?php echo htmlspecialchars($username); ?></span></a></li>
            <li><a href="credit.php">Add Credit</a></li>
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
            </a>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>
