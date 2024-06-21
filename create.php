
<?php 
include("session.php"); 
include ("dbh.inc.php");
include ("alert.php");
try {
    $stmt = $pdo->prepare("SELECT id, user_id, name, img, stock FROM items WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="create.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function showItemForm() {
            document.getElementById('itemFields').style.display = 'block';
            document.getElementById('initialFields').style.display = 'none';
            document.getElementById('stockDisplay').style.display = 'none';
            document.getElementById('itemSelect').style.display = 'none';
        }

        function showGameForm() {
            document.getElementById('itemFields').style.display = 'none';
            document.getElementById('initialFields').style.display = 'block';
            document.getElementById('stockDisplay').style.display = 'none';
            document.getElementById('itemSelect').style.display = 'none';
        }

        function showStockForm() {
            document.getElementById('itemFields').style.display = 'none';
            document.getElementById('initialFields').style.display = 'none';
            document.getElementById('stockDisplay').style.display = 'block';
            document.getElementById('itemSelect').style.display = 'none';
        }

        function showGameItemForm() {
            document.getElementById('itemFields').style.display = 'none';
            document.getElementById('initialFields').style.display = 'none';
            document.getElementById('stockDisplay').style.display = 'none';
            document.getElementById('itemSelect').style.display = 'block';
        }

        function previewImage(input, imagePreviewId) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(imagePreviewId).src = e.target.result;
                    document.getElementById(imagePreviewId).style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</head>
<body>
    <div class="menu_bar">
        <a href="index.php" class="logo"><h3>Gacha<span>Fam.</span></h3></a>
        <ul>
            <li><a href="profile.php" id="profile">Welcome, <span style="color:red"><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></span></a></li>
            <li class="credits-display">&#128178 <span class="credits-amount"><?php echo htmlspecialchars($_SESSION['user_credits'], ENT_QUOTES, 'UTF-8'); ?></span></li>
            <li><a href="credit.php">Add Credit</a></li>
            <li><a href="create.php">Create game</a></li>
            <li><a href="prize.php">History</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="page-container">
        <div class="container">
            <form id="createForm" action="formhandler.inc.php" method="post" enctype="multipart/form-data">
                <div id="initialFields">
                    <h1>Create Game</h1>
                    <div class="entryarea">
                        <label for="productName">Name of product</label>
                        <input type="text" id="productName" name="Name" required>
                    </div>
                    <div class="entryarea">
                        <label for="productImage">Image</label>
                        <input type="file" id="productImage" name="img" accept=".jpg,.jpeg,.png" required onchange="previewImage(this, 'imagePreview')">
                        <img id="imagePreview" style="display:none; width: 100px; height: 100px; margin-top: 10px;">
                    </div>
                    <div class="entryarea">
                        <label for="productDescription">Description</label>
                        <input type="text" id="productDescription" name="desc"  required>
                    </div>
                    <div class="entryarea">
                        <label for="productPrice">Price</label>
                        <input type="number" id="productPrice" name="price" required>
                    </div>
                    <button type="button" onclick="showGameItemForm()">Submit</button>
                    <div class="additional-options">
                        <p>Willing to upload items? <a href="#" onclick="showItemForm()">Items</a></p>
                        <p>Willing to update stock? <a href="#" onclick="showStockForm()">Update stock</a></p>
                    </div>
                </div>
                    <div class="item" id="itemSelect" style="display:none;">
                        <input type="hidden" id="game_id" name="game_id">
                        <h1>Select items:</h1>
                        <div class="item-row">
                            <?php foreach ($items as $item): ?>
                            <?php if($item['stock'] != 0): ?>
                                <div class="item-detail">
                                    <img src="upload/<?php echo htmlspecialchars($item['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="Item image">
                                    <h2><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                                    <p>Stock: <?php echo htmlspecialchars($item['stock'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    <div class="checkbox-container">
                                        <input type="checkbox" id="item_<?php echo htmlspecialchars($item['id']); ?>" name="selectedItem[]" value="<?php echo htmlspecialchars($item['id']); ?>">
                                        <label for="item_<?php echo htmlspecialchars($item['id']); ?>"></label>
                                    </div>
                                    <input type="text" placeholder="Probabilities" name="probabilities[<?php echo htmlspecialchars($item['id']); ?>]">
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="select-button">
                        <input type="submit" name="createGame" value="Select">
                    </div>
                    <div class="additional-options">
                        <p>Willing to update stock? <a href="#" onclick="showStockForm()">Update stock</a></p>
                    </div>
                    <h1>Out of stock items:</h1>
                    <div class="item-row">
                        <?php foreach ($items as $item): ?>
                            <?php if($item['stock'] == 0): ?>
                                <div class="item-detail">
                                    <img src="upload/<?php echo htmlspecialchars($item['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="Item image">
                                    <h2><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
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
                        <div class="additional-options">
                            <p>Willing to create game? <a href="#" onclick="showGameForm()">Create game</a></p>
                            <p>Willing to update stock? <a href="#" onclick="showStockForm()">Update stock</a></p>
                        </div>
                    </div>
                </form>
                <form id="createForm" action="formhandler.inc.php" method="post" enctype="multipart/form-data">
                    <div id="stockDisplay" style="display:none;">
                        <h1>Stock Update</h1>
                        <div class="entryarea">
                            <label for="item_id_update">Select a product:</label>
                            <select id="item_id_update" name="id" >
                                <?php 
                                foreach ($items as $item) {
                                    echo "<option value=\"{$item['id']}\">" . htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') . "</option>";
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
                        <div class="additional-options">
                            <p>Willing to create game? <a href="#" onclick="showGameForm()">Create game</a></p>
                            <p>Willing to upload items? <a href="#" onclick="showItemForm()">Items</a></p>
                        </div>
                        <div class="item-row">
                            <?php foreach ($items as $item): ?>
                            <div class="item-detail">
                                <h3><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                <img src="upload/<?php echo htmlspecialchars($item['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                <p>Stock: <?php echo htmlspecialchars($item['stock'], ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
    </html>