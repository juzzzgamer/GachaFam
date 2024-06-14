<?php 
function updateUserCredits($pdo, $userId, $amount) {
    try {
    $stmt = $pdo->prepare("UPDATE user SET credits = credits + ? WHERE id = ?");
    return $stmt->execute([$amount, $userId]);
    }catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

function fetchUserCredits($pdo, $userId) {
    try {
    $stmt = $pdo->prepare("SELECT credits FROM user WHERE id = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
    }catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
?>