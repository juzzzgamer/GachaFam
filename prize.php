<?php
include("dbh.inc.php");
include("session.php");
try{
    $stmt = $pdo->prepare("SELECT winner.username as winner_name, owner.username as owner_name, user_items.item_id as item_id, items.name as item_name, items.img as item_img
    FROM user_items
    LEFT JOIN items ON user_items.item_id = items.id
    LEFT JOIN user as winner ON user_items.user_id = winner.id
    LEFT JOIN user as owner ON items.user_id = owner.id
    WHERE user_items.user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);


}catch (PDOException $e) {
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
    <div class="prize-history">
    <h2>Prize History</h2>
    <h3>Contact owner to get your items via email </h3>
        <?php foreach ($row as $history): ?>
        <div class="prize">
            <p>Winner: <?php echo htmlspecialchars($history['winner_name']); ?></p>
            <p>Owner: <a href="details.php?username=<?php echo urlencode($history['owner_name']); ?>"><?php echo htmlspecialchars($history['owner_name']); ?></a></p> 
            <p>Item: <?php echo htmlspecialchars($history['item_name']); ?></p>
            <img src="upload/<?php echo htmlspecialchars($history['item_img']); ?>" alt="<?php echo htmlspecialchars($history['item_name']); ?>">
        </div>
         <?php endforeach; ?>
    </div>
</body>
</html>