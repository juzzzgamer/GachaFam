<?php

require_once "dbh.inc.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["Name"]) && isset($_POST["desc"]) && isset($_POST["price"]) && isset($_FILES["img"])) {
        $Name = $_POST["Name"];
        $desc = $_POST["desc"];
        $price = $_POST["price"];


        if ($_FILES["img"]["error"] === 0) {
            $img_name = $_FILES["img"]["name"];
            $img_size = $_FILES["img"]["size"];
            $tmp_name = $_FILES["img"]["tmp_name"];
            $img_error = $_FILES["img"]["error"];

          
            if ($img_size > 125000) {
                echo "<script>alert('Sorry, your file is too large.');window.location.href = 'create.php';</script>";
                exit();
            }

           
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_exs = array("jpg", "jpeg", "png");

            if (!in_array($img_ex_lc, $allowed_exs)) {
                echo "<script>alert('You can't upload files of this type'); window.location.href = 'create.php';</script>";
                exit();
            }

        
            $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
            $img_upload_path = 'upload/' . $new_img_name;
            move_uploaded_file($tmp_name, $img_upload_path);

            try {
               
                $query = "INSERT INTO users (Name_of_product, descc, img, price) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$Name, $desc, $new_img_name, $price]);

           
                header("Location: create.php");
                exit();
            } catch (PDOException $e) {
                die("Query failed:" . $e->getMessage());
            }
        } else {
            echo "<script>alert('Unknown error occurred!');</script>";
            header("Location: create.php");
            exit();
        }
    } else {
        echo "One or more required fields are not set.";
    }
} else {
    header("Location: create.php");
    exit();
}
?>