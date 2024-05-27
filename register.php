<?php
include ("dbh.inc.php");

if (isset($_POST['registerbtn'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = md5($password);

    // Check if email exists
    $checkEmailStmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
    $checkEmailStmt->bindParam(':email', $email);
    $checkEmailStmt->execute();

    if ($checkEmailStmt->rowCount() == 1) {
        echo "<script>alert('Email address exists!'); window.location.href = 'login.php';</script>";
    } else {
        // Insert new user
        $insertStmt = $pdo->prepare("INSERT INTO user (email, username, password) VALUES (:email, :username, :password)");
        $insertStmt->bindParam(':email', $email);
        $insertStmt->bindParam(':username', $username);
        $insertStmt->bindParam(':password', $password);

        if ($insertStmt->execute()) {
            header("location: login.php");
        } else {
            echo "Error: " . $pdo->errorInfo()[2];
        }
    }
}

if (isset($_POST['loginbtn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $passwordhash = md5($password);

    // Check username and password
    $loginStmt = $pdo->prepare("SELECT * FROM user WHERE username = :username AND password = :password");
    $loginStmt->bindParam(':username', $username);
    $loginStmt->bindParam(':password', $passwordhash);
    $loginStmt->execute();

    if ($loginStmt->rowCount() == 1) {
        $row = $loginStmt->fetch(PDO::FETCH_ASSOC);
        session_start();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        if (isset($_POST['remember'])) {
            setcookie('uname', $username, time() + 60 * 60);
            setcookie('pass', $password, time() + 60 * 60);
        }

        header("location: index.php");
    } else {
        echo "<script>alert('Invalid username or password'); window.location.href = 'login.php';</script>";
    }
}
?>