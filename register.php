<?php
include("connect.php");
if(isset($_POST['registerbtn'])){
    $email=$_POST['email'];
    $username=$_POST['username'];
    $password=$_POST['password'];
    $password=md5($password);

        $checkEmail="SELECT * From user where email = '$email' ";
        $result=$conn->query($checkEmail);
        if($result->num_rows>0){
            echo "Email Address already exists!";
        }
        else {
            $insertQuery="INSERT INTO user(email, username, password)
                        VALUES ('$email', '$username', '$password')";
                if($conn->query($insertQuery)==TRUE){
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
    $password=md5($password);

    $sql="SELECT * From user where username='$username' and password='$password'";
    $result=$conn->query($sql);
    if($result->num_rows>0){
        session_start();
        $row=$result->fetch_assoc();
        $_SESSION['username']=$row['username'];
        header("location: index.php");
        exit();
    }
    else{
        echo "Invalid username or password";
    }
}
?>