<?php
include("dbh.inc.php");
include("session.php");
try{
    $stmt = $pdo->prepare(
        "SELECT winner.username as winner_name, owner.username as owner_name, user_items.item_id as item_id, items.name as item_name, items.img as item_img
        FROM user_items
        LEFT JOIN items ON user_items.item_id = items.id
        LEFT JOIN user as winner ON user_items.user_id = winner.id
        LEFT JOIN user as owner ON items.user_id = owner.id
        WHERE user_items.user_id = :user_id
    ");
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
    <title>Gacha</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="prize.css">
    <script>
        function toggleView(view) {
            document.getElementById('buyer').style.display = view === 'buyer' ? 'block' : 'none';
            document.getElementById('seller').style.display = view === 'seller' ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleView('buyer');
        });
    </script>
</head>
<body>
    <div class="menu_bar">
        <a href="index.php" class="logo"><h3>Gacha<span>Fam.</span></h3></a>
        <ul>
            <li><a href="#" id="profile">Welcome, <span style="color:red"><?php echo htmlspecialchars($username); ?></span></a></li>
            <li><a href="create.php">Create game</a></li>
            <li><a href="cases.html">Cases</a></li>
            <li><a href="cart.html">Cart</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div id="buyer" class="buyer">
        <div class="prize-history">
            <h2>Items Won</h2>
            <h3>Here are the items you've won. Contact the owner via email to arrange delivery</h3>
            <p>Are you a game owner? <a href="#" class="button" onclick="toggleView('seller')">Owner</a></p>
            <?php foreach ($row as $history): ?>
                <div class="container">
                    <div class="prize">
                        <img src="upload/<?php echo htmlspecialchars($history['item_img']); ?>" alt="<?php echo htmlspecialchars($history['item_name']); ?>">
                        <p>Item: <?php echo htmlspecialchars($history['item_name']); ?></p>
                        <p>Owner: <a href="details.php?username=<?php echo urlencode($history['owner_name']); ?>"><?php echo htmlspecialchars($history['owner_name']); ?></a></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div id="seller" class="seller">
        <div class="prize-history">
            <h2>Items Listed and Winners</h2>
            <h3>Below are the items you've listed and their respective winners</h3>
            <p>Do you won the game? <a href="#" class="button" onclick="toggleView('buyer')">Winner</a></p>
            <?php foreach ($row as $history): ?>
                <div class="container">
                    <div class="prize">
                        <img src="upload/<?php echo htmlspecialchars($history['item_img']); ?>" alt="<?php echo htmlspecialchars($history['item_name']); ?>">
                        <p>Item: <?php echo htmlspecialchars($history['item_name']); ?></p>
                        <p>Winner: <?php echo htmlspecialchars($history['winner_name']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>