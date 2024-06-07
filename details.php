<?php 
include "session.php";
include "dbh.inc.php";
$username_id_from_url = isset($_GET['username']) ? $_GET['username'] : null;
if($username_id_from_url !== null){
    try {
    $stmt = $pdo->prepare("SELECT username, email FROM user;");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($results){
        foreach ($results as $row){
            if ($row['username'] == $username_id_from_url) {
           // $selected_rows[] = $row;  // Store the matching row in the array
            $username = $row['username'];
            $email = $row['email'];
        }
        }
    }else {
        echo "No results found.";
    }
    }catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Details Page</title>
</head>
<body>
    <h1>Details</h1>
    <div>
        <!-- Your details content goes here -->
        <p>Username: <?php echo $username; ?></p>
        <p>Email: <?php echo $email; ?></p>
    </div>
</body>
</html>