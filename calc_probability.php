<?php
include "dbh.inc.php";
    function getRandomItem($items) {
        $randomValue = mt_rand() / mt_getrandmax(); // Generate a random float between 0 and 1
        $cumulativeProbability = 0;
    
        foreach ($items as $item) {
            $cumulativeProbability += $item['probability'];
            if ($randomValue <= $cumulativeProbability) {
                return $item;
            }
        }
    
        return $items[0]; // Default return in case of an error
    }
    
    // Roll the gacha when the form is submitted
    $rolledItem = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $game_id_from_post = isset($_POST['game_id']) ? $_POST['game_id'] : null;

        if ($game_id_from_post !== null) {
            try {
                $stmt = $pdo->prepare("SELECT items.name AS item_name, 
                                       items.id AS item_id, items.img AS item_img, items.stock AS item_stock, probability
                                       FROM game_items 
                                       LEFT JOIN items ON game_items.item_id = items.id 
                                       WHERE game_items.game_id = :game_id");
                $stmt->bindParam(':game_id', $game_id_from_post, PDO::PARAM_INT);
                $stmt->execute();
                $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                if ($items) {
                    $rolledItem = getRandomItem($items);
                    echo json_encode($rolledItem); // Return the rolled item as JSON
                } else {
                    echo json_encode(['error' => 'No items found for this game.']);
                }
            } catch (PDOException $e) {
                echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
            }
        } 
        else {
            echo json_encode(['error' => 'Invalid game ID.']);
        }
    } else {
        echo json_encode(['error' => 'Invalid request method.']);
    }

/*    for ($i = 0; $i < count($probability); $i++){
        $sum += $probability[$i];
        if ($rand <= $sum){
            return $i;
        }
    }*/
?>