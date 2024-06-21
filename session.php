<?php
session_start();
include("dbh.inc.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit;
} else {
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
}
?>