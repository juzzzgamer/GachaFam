<!DOCTYPE html>
<head>
    <title>upload</title>
    <link rel="stylesheet" href="create.css">
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <div class="menu_bar">
        <h1 class="logo">Gacha<span>Fam.</span></a></h1>

        <ul>
          <li><a href="create.php">Create listing</a></li>
            <li><a href="file:///C:/Users/Yen%20Ming%20Jun/OneDrive/Desktop/mini%20it%20project.html/cases.html">Cases</a></li>
            <li><a href="file:///C:/Users/Yen%20Ming%20Jun/OneDrive/Desktop/mini%20it%20project.html/cart.html">Cart</a></li>
            <li><a href="#">Profile</a></li>
        </ul>
    </div>
    <div class="container">
        <h1>Create Listing</h1>
        <form class="createBoard" action="formhandler.inc.php" method="post" enctype="multipart/form-data">
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
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>
</body>
</html>