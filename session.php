<?php
session_start();
if(!isset($_SESSION['username'])){
    header("location: login.php");
} else{
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
}
?>