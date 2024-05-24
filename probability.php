<?php
include ("session.php");
include ("calc_probability.php");
include ("dbh.inc.php");
$items = getItems($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GachaFam</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
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
        <button id="rollButton">Roll</button>
        <div id="result"></div>
        <div id="items">
            <h2>Items</h2>
            <ul>
                <?php foreach ($items as $item): ?>
                    <li>
                        <?php echo $item['name']; ?> 
                    (<?php echo $item['rarity']; ?>) 
                    Probability - <?php echo $item['probability']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <script>
        const items = <?php echo json_encode($items); ?>;

        document.getElementById('rollButton').addEventListener('click', rollGacha);

        function rollGacha() {
            const resultElement = document.getElementById('result');
            const rolledItem = getRandomItem(items);
            resultElement.textContent = `You rolled: ${rolledItem.name} (${rolledItem.rarity})`;
        }

        function getRandomItem(items) {
            const randomValue = Math.random();
            let cumulativeProbability = 0;

            for (const item of items) {
                cumulativeProbability += item.probability;
                if (randomValue <= cumulativeProbability) {
                    return item;
                }
            }

            return items[0];
        }
    </script>
</body>
</html>