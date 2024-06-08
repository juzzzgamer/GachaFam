<?php
require_once "dbh.inc.php"; 
include "session.php";
include "stock_update.php";
function handleFileUpload($file, $pathPrefix = 'upload/') {
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
            echo "Error uploading file {$name}: " . $e->getMessage() . "<br>";
        }
    }

    return $uploadedFiles;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['createGame'])){
    if (isset($_POST["Name"], $_POST['desc'], $_POST['price'], $_FILES['img'], $user_id, $_POST['selectedItem'], $_POST['probabilities']) && !empty($_POST['selectedItem']) && !empty($_POST['probabilities'])){
        $name = $_POST["Name"];
        $desc = $_POST["desc"];
        $price = $_POST["price"];
        $main_img_name = handleFileUpload($_FILES["img"]);  

        $selectedItem = $_POST['selectedItem'];
        $probabilities = $_POST['probabilities'];

        $cummulativeProbability = 0;
        try{
            $pdo->beginTransaction();
                $query = "INSERT INTO game (user_id, game_name, game_desc, img, price) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$user_id, $name, $desc, $main_img_name, $price]);
                $last_game_id = $pdo->lastInsertId();  

                foreach ($selectedItem as $item_id) {
                    $probability = isset($probabilities[$item_id]) ? $probabilities[$item_id] : 0;
                    if (!is_numeric($probability)) {
                        echo "<script>alert('Invalid probability value for item ID: $item_id'); window.location.href = 'create.php';</script>";
                        exit;
                    }
                    $cummulativeProbability += $probability;
                    if ($cummulativeProbability > 1){
                        echo "<script>alert('Total probability can only be in range 0 - 1'); window.location.href = 'create.php';</script>";
                        exit;
                    }else{
                        $stmt = $pdo->prepare("INSERT INTO game_items (game_id, item_id, probability) VALUES (:game_id, :item_id, :probability)");
                        $stmt->bindParam(':game_id', $last_game_id, PDO::PARAM_INT);
                        $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
                        $stmt->bindParam(':probability', $probability, PDO::PARAM_STR);
                        $stmt->execute();
                    }
                }
            $pdo->commit();
            echo "<script>alert('Game created. Items successfully added to the game.'); window.location.href = 'create.php';</script>";
            exit();
        }   catch (Exception $e) {
            $pdo->rollBack();
            echo "<script>alert('" . $e->getMessage() . "'); window.location.href = 'create.php';</script>";
            exit();
        } 
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['itemsUpload'])) {
    if (isset($_POST['itemsName'], $_FILES['itemsImg'], $user_id)) {
        $itemsName = $_POST['itemsName'];
        $itemsImg = $_FILES['itemsImg'];

        try {
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
            $pdo->commit();
            header("Location: create.php");
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<script>alert('" . $e->getMessage() . "'); window.location.href = 'create.php';</script>";
            exit();
        }
    } else {
        echo "One or more required fields are not set.";
    }
}elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['stockUpdate'])) {
    if ($_POST['quantity']) {
        $itemID = $_POST['id'];
        $quantity = $_POST['quantity'];
        increaseStock($pdo, $itemID, $quantity);
    }
}
else {
    header("Location: create.php");
    exit();
}
?>
