<?php
function updateStock($pdo, $itemID, $quantity) {
    try {
        // Prepare the SQL statement
        $sql = "UPDATE items SET stock = stock + :quantity WHERE id = :id";

        // Bind the parameters
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $itemID, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);   

        // Execute the statement
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Stock updated successfully.'); window.location.href = 'create.php';</script>";
        } else {
            echo "<script>alert('No rows were affected.'); window.location.href = 'create.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

// Usage example
?>