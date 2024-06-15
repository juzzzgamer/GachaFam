<?php
include("dbh.inc.php");
include("session.php");
include("updateCredit.php");
include ("calc_probability.php");
include ("alert.php");

$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
$game_id_from_url = isset($_GET['id']) ? $_GET['id'] : null;

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
            $item_names[] = $row['item_name']; 
            $item_imgs[] = $row['item_img'];
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['roll'])) {
    if (!$quantity) {
        return;
    }

    $totalPrice = $game_price * $quantity;
    if ($quantity > $stock_sum) {
        $_SESSION['error_message'] = 'Error: Quantity of roll cannot be greater than sum of stock.';
    } elseif ($stock_sum == 0) {
        $_SESSION['error_message'] = 'Error: No stock available.';
    } elseif ($_SESSION['user_credits'] < $totalPrice) {
        $_SESSION['error_message'] = 'Error: Insufficient credit.';
    } else {
        $rolledItem = handleGachaRoll($pdo, $game_id_from_url, $quantity);

        $stmt = $pdo->prepare("UPDATE user SET credits = credits - ? WHERE id = ?");
        $stmt->execute([$totalPrice, $user_id]);

        $stmt = $pdo->prepare("UPDATE user SET credits = credits + ? WHERE id = ?");
        $stmt->execute([$totalPrice, $seller_id]);

        $_SESSION['rolledItem'] = $rolledItem;
        foreach ($_SESSION['rolledItem'] as $prize){
            foreach ($prize as $item){
                handlePrize($pdo, $user_id, $item['item_id']);
            }
        }

        $userCredits = fetchUserCredits($pdo, $user_id);
        if ($userCredits !== null) {
            $_SESSION['user_credits'] = $userCredits['credits'];
        } else {
            $_SESSION['error_message'] = 'Failed to fetch user credits.';
        }
    }
    header("Location: gacha.php?id=" . urlencode($game_id_from_url));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gacha Game</title>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="product_page.css">
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
<div class="menu_bar">
        <a href="index.php" class="logo"><h3>Gacha<span>Fam.</span></h3></a>
        <ul>
        <li><a href="profile.php" id="profile">Welcome, <span style="color:red"><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></span></a></li>
            <li class="credits-display">&#128178 <span class="credits-amount" c><?php echo htmlspecialchars($_SESSION['user_credits'], ENT_QUOTES, 'UTF-8'); ?></span></li>
            <li><a href="credit.php">Add Credit</a></li>
            <li><a href="create.php">Create game</a></li>
            <li><a href="prize.php">History</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="container">
        <div class="col-1">
            <img src="upload/<?php echo htmlspecialchars($game_img, ENT_QUOTES, 'UTF-8'); ?>" alt="box">
            <p>Created by: <a href="details.php?username=<?php echo urlencode($game_username); ?>"><?php echo htmlspecialchars($game_username, ENT_QUOTES, 'UTF-8'); ?></a></p> 
        </div>
        <div class="col-2">
            <div class="product-container">
                <p class="product-name"><?php echo htmlspecialchars($game_name, ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="price">Price: <span class="priceValue"><?php echo htmlspecialchars($game_price, ENT_QUOTES, 'UTF-8'); ?></span>$</p>
                <div class="btn">
                    <form action="gacha.php?id=<?php echo urlencode($game_id); ?>" method="post">
                        <div class="btn-group">
                            <button class="btn1 btn-decrement">-</button>
                            <input type="text" id="quantity" class="btn-input" name="quantity" value="1">
                            <button class="btn1 btn-increment">+</button>
                        </div>
                        <input type="hidden" name="game_id" value="<?php echo htmlspecialchars($game_id); ?>">
                        <div class="total-amount">
                        
                        <p class="total-price" id="tPrice">
                            <span id="totalPrice"><a>$</a> <?php echo htmlspecialchars($game_price, ENT_QUOTES, 'UTF-8'); ?></span>
                        </p>

                    </div>
                        <div class="purchase">
                            <button id="purchase" name="roll" onclick="showWinnerForm()"><a>BUY</a></button>
                        </div>
                    </form>
                   
                </div>
            </div>
            <div class="product_img-container">
                <div class="product_img">
                    <?php foreach ($item_imgs as $i => $img): ?>
                        <div class="item">
                            <h3><?php echo htmlspecialchars($item_names[$i], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <img src="upload/<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>">
                            <p><?php echo htmlspecialchars(number_format($probabilities[$i] * 100, 2), ENT_QUOTES, 'UTF-8') . '%'; ?></p>
            </div>
        <?php endforeach ?>
    </div>
 
</div>


        </div>
    </div>
        <div class="winner" id="winnerpage" data-show-popup="<?php echo !empty($_SESSION['rolledItem']) ? 'true' : 'false'; ?>">
            <button class="close-btn" onclick="closePopup()">✖</button>
            <?php if (!empty($_SESSION['rolledItem'])): ?>
                <h2>Congrats You Won</h2>
                <div class="winner-items">
                    <?php foreach ($_SESSION['rolledItem'] as $prizes): ?>
                        <?php foreach ($prizes as $prize) : ?>
                        <div class="winner-item">
                            <img src="upload/<?php echo htmlspecialchars($prize['item_img'], ENT_QUOTES, 'UTF-8'); ?>" alt="Item image">
                            <h3><?php echo htmlspecialchars($prize['item_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        </div>  
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    <canvas id="confettiCanvas" class="confetti-canvas"></canvas>
  
    <?php if (isset($_SESSION['error'])): ?>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p><?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    </div>
    <?php endif; ?>
<script src="gacha.js"></script>
</body>
</html>