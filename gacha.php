<?php
include("dbh.inc.php");
include("session.php");

$game_id_from_url = isset($_GET['id']) ? $_GET['id'] : null;
$userCredits = $_SESSION['user_credits'];

if($game_id_from_url !== null){
    try {
    $stmt = $pdo->prepare("SELECT game.game_name AS game_name, game.user_id,
     game.id AS game_id, game.img AS game_img, game.price AS game_price,
     items.name AS item_name, 
     items.id AS item_id, items.img AS item_img, items.stock AS item_stock, probability, user.username AS username
     FROM game_items 
     LEFT JOIN game ON game_items.game_id = game.id 
     LEFT JOIN items ON game_items.item_id = items.id
     LEFT JOIN user ON game.user_id = user.id;");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stock_sum = 0;
    if($results){
        foreach ($results as $row){
            if ($row['game_id'] == $game_id_from_url) {
            $game_id = $row['game_id'];
            $game_name = $row['game_name'];
            $game_price = $row['game_price'];
            $game_username = $row['username'];
            $game_img = $row['game_img'];
            $item_id = $row['item_id'];
            $item_name = $row['item_name'];
            $item_img[] = $row['item_img'];
            $probabilities[] = $row['probability'];
            $stock_sum += $row['item_stock'];
            $seller_id = $row['user_id'];
            }
        }
    }else {
        echo "No results found.<script>window.location.href='index.php';</script>";
    }
    }catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

 if ($seller_id == $_SESSION['user_id']) {
                echo "<script>alert('You cannot purchase your own game items!'); window.location.href = 'index.php';</script>";
                exit;
            }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['roll'])) {
    include "calc_probability.php";
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $totalPrice = $game_price * $quantity;

    if ($userCredits >= $totalPrice) {
        $rolledItem = handleGachaRoll($pdo, $game_id_from_url, $quantity);

      
        $stmt = $pdo->prepare("UPDATE user SET credits = credits - ? WHERE id = ?");
        $stmt->execute([$totalPrice, $user_id]);

    
        $stmt = $pdo->prepare("UPDATE user SET credits = credits + ? WHERE id = ?");
        $stmt->execute([$totalPrice, $seller_id]);

       
        $_SESSION['rolledItem'] = $rolledItem;

     
        $_SESSION['user_credits'] -= $totalPrice;

        header("Location: gacha.php?id=" . urlencode($game_id_from_url));
        exit;
    } else {
        echo "<script>alert('Insufficient credits!');</script>";
    }

    
}


$rolledItems = isset($_SESSION['rolledItem']) ? $_SESSION['rolledItem'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gacha Game</title>
    <link rel="stylesheet" href="product_page.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
</head>
<body>
    <div class="menu_bar">
        <a href="index.php" class="logo"><h3>Gacha<span>Fam.</span></h3></a>
        <ul>
            <li>Your credits: <?php echo htmlspecialchars($_SESSION['user_credits'] ); ?></li>
            <li><a href="#" id="profile">Welcome, <span style="color:red"><?php echo htmlspecialchars($username); ?></span></a></li>
            <li><a href="credit.php">Add Credit</a></li>
            <li><a href="create.php">Create game</a></li>
            <li><a href="cases.html">Cases</a></li>
            <li><a href="cart.html">Cart</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="container">
        <div class="col-1">
            <img src="upload/<?php echo htmlspecialchars($game_img); ?>" alt="box">
            <p>Created by: <a href="details.php?username=<?php echo urlencode($game_username); ?>"><?php echo htmlspecialchars($game_username); ?></a></p> 
        </div>
        <div class="col-2">
            <div class="product-container">
                <p class="product-name"><?php echo htmlspecialchars($game_name); ?></p>
                <p class="price">Price: <span class="priceValue"><?php echo htmlspecialchars($game_price); ?></span>$</p>
                <div class="btn">
                    <form action="gacha.php?id=<?php echo urlencode($game_id); ?>" method="post">
                        <button class="btn1 btn-decrement">-</button>
                        <input type="text" id="quantity" class="btn-input" name="quantity" value="1">
                        <button class="btn1 btn-increment">+</button>
                        <input type="hidden" name="game_id" value="<?php echo htmlspecialchars($game_id); ?>">
                        <div class="purchase">
                            <button id="purchase" name="roll" onclick="showWinnerForm()">Buy</button>
                        </div>
                    </form>
                    <div class="total-amount">
                        <h1>Total Amount</h1>
                        <p class="total-price" id="tPrice">
                            <span id="totalPrice"> <?php echo htmlspecialchars($game_price); ?></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="product_img-container">
                <div class="product_img">
                    <?php foreach ($item_img as $i => $img): ?>
                    <img src="upload/<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($img); ?>">
                    <p><?php echo number_format($probabilities[$i] * 100, 2) . '%'; ?></p>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Winner popup -->
    <div class="winner" id="winnerpage" data-show-popup="<?php echo !empty($rolledItems) ? 'true' : 'false'; ?>">
        <button class="close-btn" onclick="closePopup()">✖</button>
        <?php if (!empty($rolledItems)): ?>
            <h2>Congrats You Won</h2>
            <div class="winner-items">
                <?php foreach ($rolledItems as $item): ?>
                    <div class="winner-item">
                        <img src="upload/<?php echo htmlspecialchars($item['item_img']); ?>" alt="Item image">
                        <p><?php echo htmlspecialchars($item['item_name']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php
            // Unset or destroy the rolledItem session after displaying it
            unset($_SESSION['rolledItem']);
            ?>
        <?php endif; ?>
    </div>

    <!-- Confetti canvas -->
    <canvas id="confettiCanvas" class="confetti-canvas"></canvas>

    <script src="gacha.js"></script>
</body>
</html>
