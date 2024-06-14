<?php
include("dbh.inc.php");
include("session.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$userCredits = $_SESSION['user_credits'];
$message = '';

try {
    $stmt = $pdo->prepare("SELECT id, credits FROM user WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $userCredits = $user['credits'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $amount = (int)$_POST['amount'];

            $stmt = $pdo->prepare("UPDATE user SET credits = credits + ? WHERE id = ?");
            if ($stmt->execute([$amount, $user_id])) {
                // Update user credits in session
                $_SESSION['user_credits'] += $amount;
                $message = "Credits added successfully!";
            } else {
                $message = "Error updating record.";
            }
        }
    } else {
        $message = "User not found.";
    }
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Credits</title>
    <link rel="stylesheet" href="credit.css">
</head>
<body>
    <div class="container">
        <h1>Add Credits</h1>
        <!-- Display success message here -->
        <?php if (!empty($message)): ?>
            <div class="alert success" id="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="credits-display">
                <span>Your Credits:</span>
                <span><?php echo htmlspecialchars($_SESSION['user_credits']); ?></span>
            </div>
            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" required>
            <input type="submit" value="Add Credits">
        </form>
        <a href="index.php">Back to Dashboard</a>
    </div>
</body>
</html>