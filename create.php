<!DOCTYPE html>
<?php 
include("session.php"); 
include ("dbh.inc.php");
try {
    $stmt = $pdo->prepare("SELECT id, user_id, name, img, stock FROM items WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="create.css">
    <script>
        function showItemForm(){
            document.getElementById('itemFields').style.display = 'block';
            document.getElementById('initialFields').style.display = 'none';
            document.getElementById('stockDisplay').style.display = 'none';
            document.getElementById('itemSelect').style.display = 'none';
        }
        function showGameForm(){
            document.getElementById('itemFields').style.display = 'none';
            document.getElementById('initialFields').style.display = 'block';
            document.getElementById('stockDisplay').style.display = 'none';
            document.getElementById('itemSelect').style.display = 'none';
        }
        function showStockForm(){
            document.getElementById('itemFields').style.display = 'none';
            document.getElementById('initialFields').style.display = 'none';
            document.getElementById('stockDisplay').style.display = 'block';
            document.getElementById('itemSelect').style.display = 'none';
        }
        function showGameItemForm(){
            document.getElementById('itemFields').style.display = 'none';
            document.getElementById('initialFields').style.display = 'none';
            document.getElementById('stockDisplay').style.display = 'none';
            document.getElementById('itemSelect').style.display = 'block';
        }
    </script>
</head>
<body>
    <div class="menu_bar">
        <h1 class="logo">Gacha<span>Fam.</span></h1>
        <ul>
            <li><a href="#" id="profile">Welcome, <span style="color:red"><?php echo ($username); ?></span></a></li>
            <li><a href="create.php">Create listing</a></li>
            <li><a href="cases.html">Cases</a></li>
            <li><a href="cart.html">Cart</a></li>
            <li><a href="#">Profile</a></li>
        </ul>
    </div>      
    <div class="container">
        <form id="createForm" action="formhandler.inc.php" method="post" enctype="multipart/form-data">
            <div id="initialFields">
                <h1>Create Game</h1>
                <div class="entryarea">
                    <input type="text" name="Name" placeholder="Name of product" required>
                </div>
                <div class="entryarea">
                    <input type="file" name="img" accept=".jpg,.jpeg,.png" placeholder="Image" required>
                </div>
                <div class="entryarea">
                    <input type="text" name="desc" placeholder="Description" required>
                </div>
                <div class="entryarea">
                    <input type="number" name="price" placeholder="Price" required>
                </div>
                <button type="button" onclick="showGameItemForm()">Submit</button>
                <div class="updatestock">
                    <p>Willing to upload items? <a href="#" onclick="showItemForm()">Items</a></p>
                </div>
                <div class="updatestock">
                    <p>Willing to update stock? <a href="#" onclick="showStockForm()">Update stock</a></p>
                </div>
            </div>
            <div class="item" id="itemSelect" style="display:none;">
                <input type="hidden" id="game_id" name="game_id">
            <h1>Select items:</h1>
            <?php foreach ($items as $item): ?>
                <?php if($item['stock'] != 0): ?>
                    <img src="<?php echo htmlspecialchars($item['img']); ?>" alt="Item image">
                    <h2><?php echo htmlspecialchars($item['name']); ?></h2>
                    <p>ID: <?php echo htmlspecialchars($item['id']); ?></p>
                    <input type="checkbox" name="selectedItem[]" value="<?php echo htmlspecialchars($item['id']); ?>">
                    <input type="text" name="probabilities[<?php echo htmlspecialchars($item['id']); ?>]">
                <?php endif; ?>
            <?php endforeach; ?>
            <input type="submit" name="createGame" value="Select">
            <h1>Out of stock items:</h1>
            <p>Willing to update stock? <a href="#" onclick="showStockForm()">Update stock</a></p>
            <?php foreach ($items as $item): ?>
                <?php if($item['stock'] == 0): ?>
                    <img src="<?php echo htmlspecialchars($item['img']); ?>" alt="Item image">
                    <h2><?php echo htmlspecialchars($item['name']); ?></h2>
                    <p>ID: <?php echo htmlspecialchars($item['id']); ?></p>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        </form>
        <form id="createForm" action="formhandler.inc.php" method="post" enctype="multipart/form-data">
            <div id="itemFields" style="display:none;">
                <h2>Items</h2>
                <div class="entryarea">
                    <input type="text" name="itemsName[]" placeholder="Item" required>
                    <input type="file" name="itemsImg[]" accept=".jpg,.jpeg,.png" required>
                </div>
                <button type="submit" name="itemsUpload">Submit</button>
                <div class="updatestock">
                    <p>Willing to create game? <a href="#" onclick="showGameForm()">Create game</a></p>
                </div>
                <div class="updatestock">
                    <p>Willing to update stock? <a href="#" onclick="showStockForm()">Update stock</a></p>
                </div>
            </div>
        </form>
        <form id="createForm" action="formhandler.inc.php" method="post" enctype="multipart/form-data">
            <div id="stockDisplay" style="display:none;">
            <h1>Available Items</h1>
                <?php foreach ($items as $item): ?>
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <img src="upload/<?php echo htmlspecialchars($item['img']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <p><?php echo htmlspecialchars($item['stock']); ?></p>
                <?php endforeach; ?>
                <h1>Stock Update</h1>
                <div class="entryarea">
                    <label for="item_id_update">Select an item:</label>
                    <select id="item_id_update" name="id">
                    <?php 
                    foreach ($items as $item) {
                        echo "<option value=\"{$item['id']}\">{$item['name']}</option>";
                    }
                    ?>
                    </select>
                </div>
                <br><br>
                <div class="entryarea">
                    <label for="quantity">Enter quantity:</label>
                    <input type="number" id="quantity" name="quantity">
                </div>
                <br><br>
                <button type="submit" name="stockUpdate">Submit</button>
                <div class="updatestock">
                    <p>Willing to create game? <a href="#" onclick="showGameForm()">Create game</a></p>
                </div>
                <div class="updatestock">
                    <p>Willing to upload items? <a href="#" onclick="showItemForm()">Items</a></p>
                </div>
            </div>
            </form>
    </div>
</body>
</html>
