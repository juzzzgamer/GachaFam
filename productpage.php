<!DOCTYPE html>
<html>
<head>
<title>box</title>
<link rel="stylesheet" href="product_page.css">
<link rel="stylesheet" href="style.css">
<<<<<<< HEAD

=======
>>>>>>> origin/mike
</head>
<body>
    <div class="menu_bar">
        <h1 class="logo">Gacha<span>Fam.</span></a></h1>
        <ul>
            <li><a href="file:///C:/Users/Yen%20Ming%20Jun/OneDrive/Desktop/mini%20it%20project.html/cases.html">Cases</a></li>
            <li><a href="file:///C:/Users/Yen%20Ming%20Jun/OneDrive/Desktop/mini%20it%20project.html/cart.html">Cart</a></li>
            <li><a href="file:///C:/Users/Yen%20Ming%20Jun/OneDrive/Desktop/New%20folder/WebApp/login.html">Account</a></li>
        </ul>
    </div>
<<<<<<< HEAD
    <div class="container">
        <div class="col-1">
            <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="box" srcset="">
        </div>
        <div class="col-2">
            <div class="product-container">
                <p class="product-name">box</p>
                <p class="price">Price 5</p>
                <div class="btn">
                    <button class="btn1 btn-decrement">-</button>
                    <input type="text" id="quantity" class="btn-input" value="1">
                    <button class="btn1 btn-increment">+</button>
                    <div class="purchase">
                        <button id="purchase">buy</button>
                    </div>

                    <div class="total-amount">
                        <h1>total-amount</h1>
                        <p class="total-price">
                            <span id="price">
                                5 
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="product_img">
                <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
                <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
            </div>
        </div>
    </div>
    <script src="productpage.js"></script>
=======
    <section class="box">
        <h2>box</h2>
        <div class="image">
            <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product 1">
        </div>
        <div class="detail">
            <h4>$$$</h4>
        </div>
        <div class="page_case_open">
            <div class="count-list">
                <span class="minus">-</span>
                <span class="num">01</span>
                <span class="plus">+</span>
            </div>
            <div class="purchase">
                <button id="purchase">buy</button>
            </div>
        </div>
    </section>
    <script>

        const plus = document.querySelector(".plus"),
        minus = document.querySelector(".minus"),
        num = document.querySelector(".num");

        let a = 1;

        plus.addEventListener('click',()=>{
            a++;
            a = (a<10) ? "0" + a : a;
            num.innerText = a;
            console.log(a);
        });

        minus.addEventListener('click',()=>{
            if(a>1){
                a--;
                a = (a<10) ? "0" + a : a;
                num.innerText=a;
            }
        });
    </script>
    <div class="box_contain"><h2>Box Contains</h2></div>
    <div class="product_img">
        <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
        <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
        <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
        <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
        <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
        <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
        <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
        <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
        <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
        <img src="https://egamersworld.com/uploads/blog/16655771848831.jpg" alt="Product_img">
    </div>
>>>>>>> origin/mike
</body>
</html>
