<?php
include ("session.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>gacha</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="listing.css">
</head>
<body>
    <div class="menu_bar">
        <h1 class="logo">Gacha<span>Fam.</span></a></h1>

        <ul>
            <li><a href="#" id="profile">Welcome, <span style="color:red"><?php echo ("$username")?></span></a></li>
            <li><a href="create.php">Create listing</a></li>
            <li><a href="file:///C:/Users/Yen%20Ming%20Jun/OneDrive/Desktop/mini%20it%20project.html/cases.html">Cases</a></li>
            <li><a href="file:///C:/Users/Yen%20Ming%20Jun/OneDrive/Desktop/mini%20it%20project.html/cart.html">Cart</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    
    <section class="SPECIAL">
        <h2>SPECIAL</h2>
        <div class="box-container">
          <div class="box">
            <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product 1">
            <h3>BOX 1</h3>
            <p>$$$</p>
        
          </div>
          <div class="box">
            <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product 2">
            <h3>BOX 2</h3>
            <p>$$$</p>
       
          </div>
          <div class="box">
            <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product 3">
            <h3>BOX 3</h3>
            <p>$$$</p>
        
          </div>
        </div>
      </section>
    
      <section class="best-deals">
        <h2>BEST DEALS</h2>
        <div class="box-container">
          <div class="box" id="hover">
            <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product 4">
            <h3>BOX 1</h3>
            <p>$$$</p>
    
          </div>
          <div class="box">
            <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product 5">
            <h3>BOX 2</h3>
            <p>$$$.</p>
       
          </div>
          <div class="box">
            <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product 6">
            <h3>BOX 3</h3>
            <p>$$$</p>
          
          </div>
        </div>
      </section>
    
      <section class="random">
        <h2>RANDOM</h2>
        <div class="box-container">
          <div class="box">
            <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product 7">
            <h3>BOX 1</h3>
            <p>$$$</p>
         
          </div>
          <div class="box">
            <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product 8">
            <h3>BOX 2</h3>
            <p>$$$</p>
          
          </div>
          <div class="box">
            <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product 9">
            <h3>BOX 3</h3>
            <p>$$$</p>
          
          </div>
        </div>
      </section>
    
</body>
</html>