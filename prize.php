<?php
include("dbh.inc.php");
include("session.php");
try {
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
    $itemsWon = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch items sold by the user
    $stmt = $pdo->prepare(
        "SELECT winner.username as winner_name, owner.username as owner_name, user_items.item_id as item_id, items.name as item_name, items.img as item_img
        FROM user_items
        LEFT JOIN items ON user_items.item_id = items.id
        LEFT JOIN user as winner ON user_items.user_id = winner.id
        LEFT JOIN user as owner ON items.user_id = owner.id
        WHERE items.user_id = :user_id
    ");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $itemsOwner = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
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
                <li><a href="profile.php" id="profile">Welcome, <span style="color:red"><?php echo htmlspecialchars($username); ?></span></a></li>
                  <li class="credits-display">&#128178 <span class="credits-amount"><?php echo htmlspecialchars($_SESSION['user_credits'] ); ?></span></li>
                <li><a href="credit.php">Add Credit</a></li>
                <li><a href="create.php">Create game</a></li>
                <li><a href="prize.php">History</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
                        <div id="buyer" class="buyer">
                    <div class="prize-history">
                        <h2>Items Won</h2>
                        <h3>Congrats! Here are the items you've won</h3>
                        <h3>Contact the owner via email to arrange delivery</h3>
                        <p>Are you game owner? <a href="#" class="button" onclick="toggleView('seller')">Owner</a></p>
                        
                        <table>
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Item Name</th>
                                    <th>Owner</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($itemsWon as $bHistory): ?>
                                    <tr>
                                        
                                        <td><img src="upload/<?php echo htmlspecialchars($bHistory['item_img']); ?>" alt="<?php echo htmlspecialchars($bHistory['item_name']); ?>"></td>
                                        <td><?php echo htmlspecialchars($bHistory['item_name']); ?></td>
                                        <td><a href="details.php?username=<?php echo urlencode($bHistory['owner_name']); ?>"><?php echo htmlspecialchars($bHistory['owner_name']); ?></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                    </div>
                </div>

        <div id="seller" class="seller">
            <div class="prize-history">
                <h2>Items and Winners</h2>
                <h3>Here are the winners of your game.</h3>
                <p>Did you win a prize? <a href="#" class="button" onclick="toggleView('buyer')">Winner</a></p>
                
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Item Name</th>
                            <th>Winner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itemsOwner as $sHistory): ?>
                            <tr>
                                <td><img src="upload/<?php echo htmlspecialchars($sHistory['item_img']); ?>" alt="<?php echo htmlspecialchars($sHistory['item_name']); ?>"></td>
                                <td><?php echo htmlspecialchars($sHistory['item_name']); ?></td>
                                <td><a href="details.php?username=<?php echo urlencode($sHistory['winner_name']); ?>"><?php echo htmlspecialchars($sHistory['winner_name']); ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
            </div>
        </div>

</body>
</html>