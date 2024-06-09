<?php
session_start();
include("dbh.inc.php"); // Include your database connection

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit;
} else {
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    
    // Fetch user credits
    try {
        $stmt = $pdo->prepare("SELECT credits FROM user WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_credits'] = $user ? $user['credits'] : 0;
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}
?>
