<?php
include("dbh.inc.php");
include("session.php");
//https://youtu.be/0YLJ0uO6n8I?si=33G8Srdg_YMQTOJp
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
            <li><a href="profile.php" id="profile">Welcome, <span style="color:red"><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></span></a></li>
            <li class="credits-display">&#128178 <span class="credits-amount"><?php echo htmlspecialchars($_SESSION['user_credits'], ENT_QUOTES, 'UTF-8'); ?></span></li>
            <li><a href="credit.php">Add Credit</a></li>
            <li><a href="create.php">Create game</a></li>
            <li><a href="prize.php">History</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <section class="SPECIAL">
        <h2>GAMES</h2>
        <div class="box-container">
            <?php foreach ($games as $game): ?>
                <?php if ($stock_sum != 0) :?>
                <a href="gacha.php?id=<?php echo urlencode($game['id']); ?>" class="box-link" style="text-decoration: none;">
                <div class="box">
                <img src="upload/<?php echo htmlspecialchars($game['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($game['game_name'], ENT_QUOTES, 'UTF-8'); ?>">
                <h3><?php echo htmlspecialchars($game['game_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <p><?php echo htmlspecialchars($game['price'], ENT_QUOTES, 'UTF-8'); ?>$</p>
                <p><?php echo htmlspecialchars($game['game_desc'], ENT_QUOTES, 'UTF-8'); ?></p>
           
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>