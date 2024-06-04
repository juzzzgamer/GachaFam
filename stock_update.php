<?php
function increaseStock($pdo, $item_id, $quantity) {
    try {
        // Prepare the SQL statement
        $sql = "UPDATE items SET stock = stock + :quantity WHERE id = :id";

        // Bind the parameters
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $item_id, PDO::PARAM_INT);
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
function deductStock($pdo, $item_id, $quantity) {
    try {
        // Prepare the SQL statement
        $sql = "UPDATE items SET stock = stock - :quantity WHERE id = :id";

        // Bind the parameters
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $item_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);   

        // Execute the statement
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->rowCount() > 0) {
            echo "Stock updated successfully for item ID : $item_id." . "<br>";
        } else {
            $errorInfo = $stmt->errorInfo();
            echo "Failed to update stock for item ID: $item_id. Error: " . $errorInfo[2] . "<br>";
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
?>