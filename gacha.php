<?php
include("dbh.inc.php");
include("session.php");
include("lastPrize.php");
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
            $item_name = $row['item_name'];
            $item_img[] = $row['item_img'];
            $probabilities[] = $row['probability'];
            $stock_sum += $row['item_stock'];
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
    include "calc_probability.php";
    $rolledItem = handleGachaRoll($pdo, $game_id_from_url, $quantity);
    $_SESSION['rolledItem'] = $rolledItem;
    foreach ($_SESSION['rolledItem'] as $prize){
        foreach ($prize as $item){
            $lastPrizeID = handlePrize($pdo, $user_id, $item['item_id']);
        }
    }
    if($quantity > $stock_sum && $stock_sum != 0){
        $_SESSION['error'] = 'Error: Quantity of roll cannot be greater than sum of stock.';
    }
    if($stock_sum == 0){
        $_SESSION['error'] = 'Error: No stock available.';
    }
    header("Location: gacha.php?id=" . urlencode($game_id_from_url));
    $_SESSION['lastPrizeID'] = $lastPrizeID;
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>box</title>
    <link rel="stylesheet" href="product_page.css">
    <link rel="stylesheet" href="style.css">
    <style>.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}</style>
</head>
<body>
    <p>Congrats <?php echo $lastPrizeWinner;?> had won <?php echo $lastPrizeItemID;?>
    <div class="menu_bar">
        <a href="index.php" class="logo"><h3>Gacha<span>Fam.</span></h3></a>
        <ul>
        <li><a href="#" id="profile">Welcome, <span style="color:red"><?php echo ("$username")?></span></a></li>
            <li><a href="create.php">Create listing</a></li>
            <li><a href="cases.html">Cases</a></li>
            <li><a href="cart.html">Cart</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="container">
        <div class="col-1">
            <img src="upload/<?php echo htmlspecialchars($game_img); ?>" alt="box" srcset="">
            <p>Created by: <a href="details.php?username=<?php echo urlencode($game_username); ?>"><?php echo htmlspecialchars($game_username); ?></a></p> 
        </div>
        <div class="col-2">
            <div class="product-container">
                <p class="product-name"><?php echo htmlspecialchars($game_name); ?></p>
                <p class="price">Price: <span class="priceValue"><?php echo htmlspecialchars($game_price); ?></span>$</p>
                <div class="btn">
                <form action="gacha.php?id=<?php echo urlencode($game_id);?>" method="post">
                    <button class="btn1 btn-decrement">-</button>
                    <input type="text" id="quantity" class="btn-input" name="quantity" value="1">
                    <button class="btn1 btn-increment">+</button>
                    <input type="hidden" name="game_id" value="<?php echo htmlspecialchars($game_id); ?>">
                    <div class="purchase">
                        <button id="purchase" name="roll">buy</button>
                    </div>
                </form>
                    <div class="total-amount">
                        <h1>total-amount</h1>
                        <p class="total-price" id="tPrice">
                            <span id="totalPrice"> <?php echo htmlspecialchars($game_price); ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="product_img-container">
                <div class="product_img">
                <?php foreach ($item_img as $i => $img): ?>
                <img src="upload/<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($img); ?>">
                <p><?php echo ($probabilities[$i]); ?></p>
            <?php endforeach; ?>
            </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (isset($_SESSION['error'])): ?>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p><?php echo addslashes($_SESSION['error']); ?></p>
        </div>
    </div>
    <script>
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];
        modal.style.display = "block";
        span.onclick = function() {
            modal.style.display = "none";
            window.location.href='index.php';
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                window.location.href='index.php';
            }
        }
    </script>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<script src="gacha.js"></script>
</body>
</html>
