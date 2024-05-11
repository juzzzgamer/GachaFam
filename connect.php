<?php

$host="localhost";
$user="root";
$pass="";
$db="login";

$conn=new mysqli($host, $user, $pass, $db, 3307);
if($conn->connect_error){
    echo "Failed to connect with DB".$conn->connect_error;
}
?>