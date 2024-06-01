<?php
session_start();
if(!isset($_SESSION['username']) && (!isset($_SESSION['user_id']))){
    header("location: login.php");
} else{
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
}
?>