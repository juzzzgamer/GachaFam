<!DOCTYPE html>
<?php include("session.php"); ?>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="create.css">
    <script>
        function showItemForm() {
            document.getElementById('initialFields').style.display = 'none';
            document.getElementById('itemFields').style.display = 'block';
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
                <h1>Create Listing</h1>
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
                <button type="button" onclick="showItemForm()">Next</button>
            </div>

            <div id="itemFields" style="display:none;">
                <h2>Common Items</h2>
                <div class="entryarea">
                    <input type="text" name="common[]" placeholder="Common item name 1" required>
                    <input type="file" name="commonImg[]" accept=".jpg,.jpeg,.png" required>
                </div>
                <div class="entryarea">
                    <input type="text" name="common[]" placeholder="Common item name 2" required>
                    <input type="file" name="commonImg[]" accept=".jpg,.jpeg,.png" required>
                </div>
                <div class="entryarea">
                    <input type="text" name="common[]" placeholder="Common item name 3" required>
                    <input type="file" name="commonImg[]" accept=".jpg,.jpeg,.png" required>
                </div>
                <div class="entryarea">
                    <input type="text" name="common[]" placeholder="Common item name 4" required>
                    <input type="file" name="commonImg[]" accept=".jpg,.jpeg,.png" required>
                </div>
                <div class="entryarea">
                    <input type="text" name="common[]" placeholder="Common item name 5" required>
                    <input type="file" name="commonImg[]" accept=".jpg,.jpeg,.png" required>
                </div>

                <h2>Rare Items</h2>
                <div class="entryarea">
                    <input type="text" name="rare[]" placeholder="Rare item name 1" required>
                    <input type="file" name="rareImg[]" accept=".jpg,.jpeg,.png" required>
                </div>
                <div class="entryarea">
                    <input type="text" name="rare[]" placeholder="Rare item name 2" required>
                    <input type="file" name="rareImg[]" accept=".jpg,.jpeg,.png" required>
                </div>

                <h2>Epic Item</h2>
                <div class="entryarea">
                    <input type="text" name="epic" placeholder="Epic item name" required>
                    <input type="file" name="epicImg" accept=".jpg,.jpeg,.png" required>
                </div>

                <button type="submit" name="submit">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>
