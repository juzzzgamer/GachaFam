<?php
include("dbh.inc.php");
include("session.php");

try {
    $stmt = $pdo->prepare("SELECT id, credits FROM user WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $userCredits = $user['credits'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount'])) {
            $amount = (int)$_POST['amount'];

            $stmt = $pdo->prepare("UPDATE user SET credits = credits + ? WHERE id = ?");
            if ($stmt->execute([$amount, $user_id])) {
                // Fetch updated credits
                $stmt = $pdo->prepare("SELECT credits FROM user WHERE id = ?");
                $stmt->execute([$user_id]);
                $updatedUser = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($updatedUser) {
                    $_SESSION['user_credits'] = $updatedUser['credits'];
                    $_SESSION['message'] = "Credits updated successfully!";
                } else {
                    $_SESSION['message'] = "Error fetching updated credits.";
                }
            } else {
                $_SESSION['message'] = "Error updating record.";
            }
            header('Location: credit.php');
            exit();
        }
    }
} catch (Exception $e) {
    die("Query failed: " . $e->getMessage());
}

if (isset($_SESSION['message'])) {
    echo $_SESSION['message'];
    unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Credits</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="menu_bar">
        <a href="index.php" class="logo"><h3>Gacha<span>Fam.</span></h3></a>
        <ul>
            <li>Your credits: <?php echo htmlspecialchars($userCredits, ENT_QUOTES, 'UTF-8'); ?></li>
            <li><a href="#" id="profile">Welcome, <span style="color:red"><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></span></a></li>
            <li><a href="credit.php">Add Credit</a></li>
            <li><a href="create.php">Create game</a></li>
            <li><a href="cases.html">Cases</a></li>
            <li><a href="cart.html">Cart</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <form method="post" action="credit.php">
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" required>
        <input type="submit" value="Add Credits">
    </form>
    <a href="index.php">Back to Dashboard</a>
</body>
</html>
