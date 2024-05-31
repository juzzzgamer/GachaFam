<?php
require_once "dbh.inc.php"; 
include "session.php";

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check for required fields
    if (isset($_POST["Name"], $_POST["desc"], $_POST["price"], $_FILES["img"], $_POST["common"], $_POST["rare"], $_POST["epic"], $_SESSION['user_id'])) {

        $productName = $_POST["Name"];
        $listingDesc = $_POST["desc"];
        $price = $_POST["price"];
        $user_id = $_SESSION['user_id'];
        $common_names = $_POST['common'];
        $rare_names = $_POST['rare'];
        $epic_name = $_POST['epic'];

        try {
            // Handle main product image upload
            $main_img_name = handleFileUpload($_FILES["img"]);

            // Start transaction
            $pdo->beginTransaction();

            // Insert main listing
            $query = "INSERT INTO listings (user_id, product_name, listing_desc, img, price) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$user_id, $productName, $listingDesc, $main_img_name, $price]);

            // Get last inserted listing id
            $listing_id = $pdo->lastInsertId();

            // Handle common items
            $common_imgs = handleMultipleFileUploads($_FILES["commonImg"]);
            foreach ($common_names as $i => $common_name) {
                $common_img_name = $common_imgs[$i] ?? null;
                if ($common_img_name) {
                    $query = "INSERT INTO items (game_id, item_name, img, rarity) VALUES (?, ?, ?, ?)";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([$listing_id, $common_name, $common_img_name, 'common']);
                }
            }

            // Handle rare items
            $rare_imgs = handleMultipleFileUploads($_FILES["rareImg"]);
            foreach ($rare_names as $i => $rare_name) {
                $rare_img_name = $rare_imgs[$i] ?? null;
                if ($rare_img_name) {
                    $query = "INSERT INTO items (game_id, item_name, img, rarity) VALUES (?, ?, ?, ?)";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([$listing_id, $rare_name, $rare_img_name, 'rare']);
                }
            }

            // Handle epic item
            $epic_img_name = handleFileUpload($_FILES["epicImg"]);
            $query = "INSERT INTO items (game_id, item_name, img, rarity) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$listing_id, $epic_name, $epic_img_name, 'epic']);

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
} else {
    // Redirect if not a POST request
    header("Location: create.php");
    exit();
}
?>
