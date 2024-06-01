<?php
require_once "dbh.inc.php"; 
include "session.php";
include "stock_update.php";
function handleFileUpload($file, $pathPrefix = 'upload/') {
    // File upload handling code
    $img_name = $file["name"];
    $img_size = $file["size"];
    $tmp_name = $file["tmp_name"];
    $img_error = $file["error"];

    if ($img_error !== 0) {
        throw new Exception('Unknown error occurred during file upload.');
    }

    if ($img_size > 125000) {
        throw new Exception('Sorry, your file is too large.');
    }

    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
    $img_ex_lc = strtolower($img_ex);
    $allowed_exs = array("jpg", "jpeg", "png");

    if (!in_array($img_ex_lc, $allowed_exs)) {
        throw new Exception('You can\'t upload files of this type.');
    }

    $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
    $img_upload_path = $pathPrefix . $new_img_name;
    move_uploaded_file($tmp_name, $img_upload_path);

    return $new_img_name;
}

function handleMultipleFileUploads($files, $pathPrefix = 'upload/') {
    $uploadedFiles = [];

    foreach ($files['name'] as $key => $name) {
        $file = [
            'name' => $files['name'][$key],
            'type' => $files['type'][$key],
            'tmp_name' => $files['tmp_name'][$key],
            'error' => $files['error'][$key],
            'size' => $files['size'][$key],
        ];

        try {
            $uploadedFileName = handleFileUpload($file, $pathPrefix);
            $uploadedFiles[] = $uploadedFileName;
        } catch (Exception $e) {
            // Handle errors for individual files
            echo "Error uploading file {$name}: " . $e->getMessage() . "<br>";
        }
    }

    return $uploadedFiles;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['itemsUpload'])) {
    // Check for required fields
    if (isset($_POST['itemsName'], $_FILES['itemsImg'], $user_id)) {
        $itemsName = $_POST['itemsName'];
        $itemsImg = $_FILES['itemsImg'];

        try {
            // Start transaction
            $pdo->beginTransaction();

            $item_img = handleMultipleFileUploads($_FILES["itemsImg"]);
            foreach ($itemsName as $i => $item_name) {
                $item_img_name = $item_img[$i] ?? null;
                if ($item_img_name) {
                    $query = "INSERT INTO items (user_id, name, img, stock) VALUES (?, ?, ?, ?)";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([$user_id, $item_name, $item_img_name, '0']);
                }
            }
            // Commit transaction
            $pdo->commit();
            header("Location: create.php");
            exit();
        } catch (Exception $e) {
            // Rollback transaction on exception
            $pdo->rollBack();
            echo "<script>alert('" . $e->getMessage() . "'); window.location.href = 'create.php';</script>";
            exit();
        }
    } else {
        // Handle case where required fields are not set
        echo "One or more required fields are not set.";
    }
}elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['stockUpdate'])) {
    if ($_POST['quantity']) {
        $itemID = $_POST['id'];
        $quantity = $_POST['quantity'];
        updateStock($pdo, $itemID, $quantity);
    }
}else {
    // Redirect if not a POST request
    header("Location: create.php");
    exit();
}
?>
