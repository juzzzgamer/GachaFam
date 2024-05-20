<?php
include ("connect.php");
#$game_id = 1; // Assuming a specific game ID for this example

$sql = "SELECT id, name, rarity FROM items";
$stmt = mysqli_prepare($conn, $sql);
#$stmt->bind_param("i", $game_id);
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Define rarity probabilities
$rarity_probabilities = [
    'uncommon' => 0.1,
    'medium' => 0.3,
    'common' => 0.6
];

$total_probability = 0;
foreach ($items as &$item) {
    $rarity = $item['rarity'];
    $item['probability'] = $rarity_probabilities[$rarity];
    $total_probability += $rarity_probabilities[$rarity];
}

// Adjust individual item probabilities to ensure they sum up to 1
foreach ($items as &$item) {
    $item['probability'] /= $total_probability;
}

$conn->close();


?>