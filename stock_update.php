<?php
function increaseStock($pdo, $item_id, $quantity) {
    try {
        $sql = "UPDATE items SET stock = stock + :quantity WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $item_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);   
        $stmt->execute();

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
        $sql = "UPDATE items SET stock = stock - :quantity WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $item_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);   
        $stmt->execute();

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

function getCurrentStock($pdo, $item_id){
    try {
        $stmt = $pdo->prepare("SELECT stock FROM items WHERE id = :item_id");
        $stmt->bindParam(':item_id', $item_id);
        $stmt->execute();
        $updated_stock = $stmt->fetchColumn();

        return $updated_stock;
    }catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
?>