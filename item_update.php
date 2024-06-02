<?php
include "dbh.inc.php";
include "session.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['game_id'], $_POST['selectedItem'], $_POST['probabilities']) && !empty($_POST['selectedItem']) && !empty($_POST['probabilities'])) {
        $game_id = $_POST['game_id'];
        $selectedItem = $_POST['selectedItem']; // This will be an array of item IDs
        $probabilities = $_POST['probabilities'];

        $cummulativeProbability = 0;
        try {
            $pdo->beginTransaction();

            // Loop through the selected item IDs and insert them into the database
            foreach ($selectedItem as $item_id) {
                $probability = isset($probabilities[$item_id]) ? $probabilities[$item_id] : 0;
                if (!is_numeric($probability)) {
                    echo "<script>alert('Invalid probability value for item ID: $item_id'); window.location.href = 'gameItem.php';</script>";
                    exit;
                }
                $cummulativeProbability += $probability;
                if ($cummulativeProbability > 1){
                    echo "<script>alert('Total probability can only be in range 0 - 1'); window.location.href = 'gameItem.php';</script>";
                    exit;
                }else{
                    $stmt = $pdo->prepare("INSERT INTO game_items (game_id, item_id, probability) VALUES (:game_id, :item_id, :probability)");
                    $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
                    $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
                    $stmt->bindParam(':probability', $probability, PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
            $pdo->commit();
            echo "<script>alert('Items successfully added to the game.'); window.location.href = 'gameItem.php';</script>";
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "<script>alert('Failed to add items to the game: " . $e->getMessage() . "'); window.location.href = 'gameItem.php';</script>";
        }
    } else {
        echo "<script>alert('Please select a game and at least one item.'); window.location.href = 'gameItem.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request method.'); window.location.href = 'gameItem.php';</script>";
}