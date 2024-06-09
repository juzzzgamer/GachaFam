<?php
include "dbh.inc.php";
include "stock_update.php";
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
    function getMultipleItem($items, $quantity) {
        $result = array();
        for ($i = 0; $i < $quantity; $i++) {
        $result[] = getRandomItem($items);
        }
        return $result;
    }
    
    // Roll the gacha when the form is submitted
    function handleGachaRoll($pdo, $game_id, $quantity) {
        $rolledItem = null;
        if ($game_id !== null) {
            try {
                $stmt = $pdo->prepare("SELECT items.name AS item_name, 
                                       items.id AS item_id, items.img AS item_img, items.stock AS item_stock, probability
                                       FROM game_items 
                                       LEFT JOIN items ON game_items.item_id = items.id 
                                       WHERE game_items.game_id = :game_id");
                $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
                $stmt->execute();
                $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                if ($items) {
                    if ($quantity) {
                        $rolledItem = [];
                        for ($i = 0; $i < $quantity; $i++) {
                            // Check if all items are out of stock
                            if (empty($items)) {
                                break;
                            }
                            // Roll an item
                            while (true) {
                                $prize = getRandomItem($items);
                                if ($prize !== null && $prize['item_stock'] == 0) {
                                    // Remove the item from the items array
                                    unset($items[array_search($prize, $items)]);
                                } else {
                                    if ($prize !== null) {
                                        $rolledItem[] = $prize;
                                        // Deduct the stock of the rolled item
                                        deductStock($pdo, $prize['item_id'], 1);
                                    }
                                  
                                    break;
                                }
                            }
                        }
                    }
                    return $rolledItem;
                  
                } else {
                    return ['error' => 'No items found for this game.'];
                }
            } catch (PDOException $e) {
                return ['error' => 'Query failed: ' . $e->getMessage()];
            }
        } else {
            return ['error' => 'Invalid game ID.'];
        }
    }    

/*    for ($i = 0; $i < count($probability); $i++){
        $sum += $probability[$i];
        if ($rand <= $sum){
            return $i;
        }
    }*/
?>