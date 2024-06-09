<?php 
try {
    $stmt = $pdo->prepare("SELECT user_items.item_id, items.name as item_name, user.username as user_won FROM user_items 
    LEFT JOIN items ON user_items.item_id = items.id
    LEFT JOIN user ON user_items.user_id = user.id
    WHERE user_items.id = :lastPrizeID");
    $stmt->bindParam(':lastPrizeID', $_SESSION['lastPrizeID']);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $lastPrizeItemID = $row['item_name'];
    $lastPrizeWinner = $row['user_won'];
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>