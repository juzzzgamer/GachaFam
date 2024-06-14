<?php
include "dbh.inc.php";
include "stock_update.php";
function getRandomItem($items) {
    global $pdo;
    $totalProbability = 0;
    foreach ($items as $item) {
        $current_stock = getCurrentStock($pdo, $item['item_id']);
        if ($current_stock > 0) {
            $totalProbability += $item['probability'];
        }
    }
    $randomValue = mt_rand() / mt_getrandmax() * $totalProbability;
    $cumulativeProbability = 0;
    foreach ($items as $item) {
        $current_stock = getCurrentStock($pdo, $item['item_id']);
        if ($current_stock > 0) {
            $cumulativeProbability += $item['probability'];
            if ($randomValue <= $cumulativeProbability) {
                return $item;
            }
        }
    }
    return null;
}
function getMultipleItem($items, $quantity) {
    global $pdo;
    $result = array();
    for ($i = 0; $i < $quantity; $i++) {
        while (true) {
            $item = getRandomItem($items);
            if ($item === null) {
                break;
            }
            $current_stock = getCurrentStock($pdo, $item['item_id']);
            if ($current_stock > 0) {
                $result[] = $item;
                deductStock($pdo, $item['item_id'], 1);
                break;
            }
        }
    }
    return $result;
}
function handlePrize($pdo, $user_id, $item_id){
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO user_items (user_id, item_id) VALUES (:user_id, :item_id)");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
        $stmt->execute();
        $lastPrizeID = $pdo->lastInsertId(); 
        
        $pdo->commit();
        return $lastPrizeID;
    } catch (PDOException $e) {
        $pdo->rollBack();
        return ['error' => 'Query failed: ' . $e->getMessage()];
    }
}
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
                    $rolledItem[] = getMultipleItem($items, $quantity);
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
?>