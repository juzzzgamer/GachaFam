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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            height: 100vh; /* Full viewport height */
            text-align: center; /* Center text horizontally */
        }

        .container {
            width: 400px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .details {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .details p {
            margin: 10px 0;
            color: #333;
            text-align: center; /* Center text horizontally */
        }

        .details p strong {
            font-weight: bold;
        }

        .back-link {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .back-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Details</h1>
        <div class="details">
            <p><strong>Username:</strong> <?php echo $username; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
        </div>
        <a href="prize.php" class="back-link">Back</a>
    </div>
</body>
</html>