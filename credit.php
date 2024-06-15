<?php
include("dbh.inc.php");
include("session.php");
include("updateCredit.php");
include("alert.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount'])) {
    $amount = (int)$_POST['amount'];

    if (updateUserCredits($pdo, $user_id, $amount)) {
        // Fetch updated credits
        $updatedUser = fetchUserCredits($pdo, $user_id);
    
        if ($updatedUser) {
            $_SESSION['user_credits'] = $updatedUser['credits'];
            $_SESSION['success_message'] = "Credits updated successfully!";
        } else {
            $_SESSION['error_message'] = "Error fetching updated credits.";
            }
        } else {
            $_SESSION['error_message'] = "Error updating record.";
        }
        header('Location: credit.php');
        exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Credits</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
    <div class="menu_bar">
        <a href="index.php" class="logo"><h3>Gacha<span>Fam.</span></h3></a>
        <ul>
        <li>Your credits: <?php echo htmlspecialchars($_SESSION['user_credits'] ); ?></li>
            <li><a href="profile.php" id="profile">Welcome, <span style="color:red"><?php echo htmlspecialchars($username); ?></span></a></li>
            <li><a href="credit.php">Add Credit</a></li>
            <li><a href="create.php">Create game</a></li>
            <li><a href="prize.php">History</a></li>
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