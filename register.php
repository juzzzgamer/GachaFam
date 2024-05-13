<?php
include("connect.php");
if(isset($_POST['registerbtn'])){
    $email=$_POST['email'];
    $username=$_POST['username'];
    $password=$_POST['password'];
    $password=md5($password);

        $checkEmail="SELECT * From user where email = '$email'";
        $result=mysqli_query($conn, $checkEmail);
        if(mysqli_num_rows($result) == 1){
            echo "<script>alert('Email address exists!'); window.location.href = 'login.php';</script>";
        }
        else {
            $insertQuery="INSERT INTO user(email, username, password)
                        VALUES ('$email', '$username', '$password')";
                if(mysqli_query($insertQuery)==TRUE){
                    header("location: login.php");
                }
                else{
                    echo "Error:".$conn->error;
                }
        }
}
if(isset($_POST['loginbtn'])){
    $username=$_POST['username'];
    $password=$_POST['password'];
    $passwordhash=md5($password);

    $sql="SELECT * From user where username='$username' and password='$passwordhash'";
    $result=mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 1){
        $remember=$_POST['remember'];
        $row=$result->fetch_assoc();
        session_start();
        $_SESSION['username']=$row['username'];
        if(isset($remember)){
            setcookie('uname', $username, time()+60*60);
            setcookie('pass', $password, time()+60*60);
        }
        header("location: index.php");
    }
    else{
        echo "<script>alert('Invalid username or password'); window.location.href = 'login.php';</script>";
    }
}
?>