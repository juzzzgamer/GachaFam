<?php
require_once 'dbh.inc.php';
include "session.php";

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $email = htmlspecialchars($_POST['email']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email format';
        } else {
            $currentInfoQuery = "SELECT email FROM user WHERE id = ?";
            $currentInfoStmt = $pdo->prepare($currentInfoQuery);
            $currentInfoStmt->execute([$user_id]);
            $currentInfo = $currentInfoStmt->fetch(PDO::FETCH_ASSOC);
        
            if ($currentInfo['email'] === $email) {
                $error = 'No changes were made as the new information is the same as the current information.';
            } else {
                $query = "UPDATE user SET email = ? WHERE id = ?";
                $stmt = $pdo->prepare($query);
        
                if ($stmt->execute([$email, $user_id])) {
                    header('Location: login.php?success=profile_updated');
                } else {
                    $error = 'Failed to update profile.';
                }
            }
        }
    } elseif (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        $query = "SELECT password FROM user WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$user_id]);
        $result = $stmt->fetch();

        if (md5($currentPassword) === $result['password']) {
            if ($newPassword === $confirmPassword) {
                if (md5($newPassword) === $result['password']) {
                    $error = 'New password cannot be the same as the current password.';
                } else {
                    $newPasswordHash = md5($newPassword);
                    $updateQuery = "UPDATE user SET password = ? WHERE id = ?";
                    $updateStmt = $pdo->prepare($updateQuery);
                    if ($updateStmt->execute([$newPasswordHash, $user_id])) {
                        session_unset(); 
                        session_destroy(); 
            
                        header('Location: login.php?success=password_changed');
                    }else {
                    $error = 'Failed to update password.';
                }
            }
        }else {
            $error = 'New passwords do not match.';
        }
    }else {
            $error = 'Current password is incorrect.';
        }
    }
}

$query = "SELECT username, email FROM user WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="style.css">    
</head>
<body>
<div class="menu_bar">
        <a href="index.php" class="logo"><h3>Gacha<span>Fam.</span></h3></a>
        <ul>
            <li><a href="profile.php" id="profile">Welcome, <span style="color:red"><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></span></a></li>
            <li class="credits-display">&#128178 <span class="credits-amount"><?php echo htmlspecialchars($_SESSION['user_credits'], ENT_QUOTES, 'UTF-8'); ?></span></li>
            <li><a href="credit.php">Add Credit</a></li>
            <li><a href="create.php">Create game</a></li>
            <li><a href="prize.php">History</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <?php if ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color: green;"><?= $success ?></p>
    <?php endif; ?>
    <div id="editProfile">
    <h2>Edit Profile</h2>
    <a href="javascript:history.back()" class="back-button"> <-Back</a>
    <form method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= $user['email'] ?>" required><br>
        <input type="submit" name="update_profile" value="Update Profile"><br>
        <a href="#" id="updatePasswordLink">Update password?</a>
    </form>
    </div>
    <div id="changePassword" style="display:none;">
    <h2>Change Password</h2>
    <a href="javascript:history.back()" class="back-button"> <-Back</a>
    <form method="post">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <input type="submit" name="change_password" value="Change Password">
        <a href="#" id="editProfileLink">Edit Profile?</a>
    </form>
    </div>
    <script>
        const editProfile = document.getElementById("editProfile");
        const editPassword = document.getElementById("changePassword");
        const updatePasswordLink = document.getElementById("updatePasswordLink");
        const editProfileLink = document.getElementById("editProfileLink");
        updatePasswordLink.addEventListener('click', function(){
            editProfile.style.display="none";
            editPassword.style.display="block";
        })
        editProfileLink.addEventListener('click', function(){
            editPassword.style.display="none";
            editProfile.style.display="block";
        })
    </script>
</body>
</html>