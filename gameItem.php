<?php
include("dbh.inc.php");
include("session.php");
try {
    $gamestmt = $pdo->prepare("SELECT id, user_id, game_name, img FROM game WHERE user_id = :user_id");
    $gamestmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $gamestmt->execute();
    $games = $gamestmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
try {
    $itemstmt = $pdo->prepare("SELECT id, user_id, name, img FROM items WHERE user_id = :user_id");
    $itemstmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $itemstmt->execute();
    $items = $itemstmt->fetchAll(PDO::FETCH_ASSOC);
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
<script>
    function showItems(gameId) {
        document.getElementById('game_id').value = gameId; // Set the hidden input value
        document.getElementById('itemSelect').style.display = 'block';
        document.getElementById('gameSelect').style.display = 'none';
    }
</script>
</head>
<body>
    <div class="menu_bar">
        <h1 class="logo">Gacha<span>Fam.</span></h1>
        <ul>
        <li><a href="#" id="profile">Welcome, <span style="color:red"><?php echo ("$username")?></span></a></li>
            <li><a href="create.php">Create listing</a></li>
            <li><a href="cases.html">Cases</a></li>
            <li><a href="cart.html">Cart</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <form action="item_update.php" method="post">
        <div class="game" id="gameSelect">
            <h1>Select game</h1>
        <?php foreach ($games as $game): ?>
            <a href="#" onclick="showItems('<?php echo htmlspecialchars($game['id']); ?>');" style="text-decoration: none;">
            <img src="<?php echo htmlspecialchars($game['img']); ?>" alt="Game image">
            <h2><?php echo htmlspecialchars($game['game_name']); ?></h2>
            <p>ID: <?php echo htmlspecialchars($game['id']); ?></p>
        </a>
        <?php endforeach; ?>
        </div>
    </form>
        <form action="item_update.php" method="post">
        <div class="item" id="itemSelect" style="display:none;">
        <input type="hidden" id="game_id" name="game_id">
        <h1>Select items:</h1>
            <?php foreach ($items as $item): ?>
                    <img src="<?php echo htmlspecialchars($item['img']); ?>" alt="Item image">
                    <h2><?php echo htmlspecialchars($item['name']); ?></h2>
                    <p>ID: <?php echo htmlspecialchars($item['id']); ?></p>
                    <input type="checkbox" name="selectedItem[]" value="<?php echo htmlspecialchars($item['id']); ?>">
                    <input type="text" name="probabilities[<?php echo htmlspecialchars($item['id']); ?>]">
            <?php endforeach; ?>
            <input type="submit" value="Select">
        </div>
        </form>
        <div>
</body>
</html>