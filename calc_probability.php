<?php
function getItems($pdo) {
    $stmt = $pdo->prepare("SELECT id, name, rarity FROM items");
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    $rarity_probabilities = [
        'epic' => 0.04,
        'rare' => 0.16,
        'common' => 0.8
    ];

    $rarity_counts = [
        'epic' => 0,
        'rare' => 0,
        'common' => 0
    ];

    // count item rarity
    foreach ($items as $item) {
        $rarity = $item['rarity'];
        $rarity_counts[$rarity]++;
    }
    // calculate item probability
    foreach ($items as &$item) {
        $item['probability'] = $rarity_probabilities[$item['rarity']] / $rarity_counts[$item['rarity']];
    }
    return $items;
}
?>