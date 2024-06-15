<?php
session_start();
session_destroy();
if (isset($_GET['success'])) {
    if ($_GET['success'] === 'profile_updated') {
        echo '<script>alert("Profile updated successfully.");window.location.href="login.php";</script>';
    } elseif ($_GET['success'] === 'password_changed') {
        echo '<script>alert("Password changed successfully.");window.location.href="login.php";</script>';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            }
            body {
                display: flex;  
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-image: url("image.jpeg");
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }
            .container {
                width: 460px;
                background-color: green;
                color: white;
                border-radius : 10px;
                padding: 60px;
                background: transparent;
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0 0 15px rgba(0, 0, 0, .2);
                backdrop-filter: blur(20px);

                
            }
            h1 {
                font-size: 36px;
                text-align: center;
            }
            .inputbox {
                width: 100%;
                height: 50px;
                position: relative;
                margin: 20px 0px;
            }
            .inputbox input {
                color: white;
                font-size: 15px;
                width: 100%;
                height: 100%;
                background: transparent;
                border: none;
                outline: 1px solid white;
                border-radius: 60px;
                padding: 20px 45px 20px 20px;
            }
            .inputbox input::placeholder {
                color: white;
            }
            .inputbox i {
                position: absolute;
                right: 20px;
                top: 50%;
                transform: translateY(-50%);
                font-size: 20px;
            }
            .rmbforgot {
                display: flex;
                justify-content: space-between;
                font-size: 15px;
            }
            .rmbforgot label input {
                accent-color: white;
                margin-right: 3px;
            }
            .rmbforgot a {
                color: white;
                text-decoration: none;
            }
            .rmbforgot a:hover {
                text-decoration: underline;
            }
            .btn {
                width: 100%;
                height: 45px;
                outline: 1px solid white;
                border-radius: 60px;
                border: none;
                background: white;
                color: black;
                font-size: 15px;
                text-align: center;
                margin-top: 20px;
                cursor: pointer;
                font-weight: 500;
                font-size: 15px;
            }
            .register {
                text-align: center;
                padding-top: 10px;
                margin: 30px 0 0;
            }
            .register a{
                color: red;
                text-decoration: none;
                font-weight: 600;
            }
            .register a:hover{
                text-decoration: underline;
            }
            .login{
                text-align: center;
                padding-top: 10px;
                margin: 30px 0 0;
            }
            .login a{
                color: red;
                text-decoration: none;
                font-weight: 600;
            }
            .login a:hover{
                text-decoration: underline;
            }
            .tnc {
                font-size: 15px;
            }
            .tnc input {
                accent-color: white;
                margin-right: 3px;
            }
        </style>
    </head>
    <body>
        <div class="container" id="loginform">
        <form action="register.php" method="POST">
            <h1>Welcome to GachaFam!</h1>
                <div class="inputbox">
                <input type="text" name="username" placeholder="Username" required value=<?php if (isset($_COOKIE['uname'])) echo $_COOKIE['uname'];?>>
                    <i class='bx bx-user' ></i>
                </div>
                <div class="inputbox">
                <input type="password" name="password" placeholder="Password" required value=<?php if (isset($_COOKIE['pass'])) echo $_COOKIE['pass']?>>
                    <i class='bx bx-lock-alt'></i>
                </div>
                <div class="rmbforgot">
                <label><input type="checkbox" name="remember">Remember me</label>
                    <a href="#" id="forgotPassword">Forgot password?</a>
                 </div>
                 <input class ="btn" name="loginbtn" type="submit" value="Login">
                 <div class="register">
                     <p>Dont have an account? <a href="#" id="registerLink">Register</a></p>
                 </div>
        </form>
        </div>
        <div class="container" id="registerform"  style="display:none;">
            <form action="register.php" method="POST">
                <h1>Register</h1>
                <div class="inputbox">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bx-envelope'></i>
                </div>
                    <div class="inputbox">
                        <input type="text" name="username" placeholder="Username" required>
                        <i class='bx bx-user' ></i>
                    </div>
                    <div class="inputbox">
                        <input type="password" name="password" placeholder="Password" required>
                        <i class='bx bx-lock-alt'></i>
                    </div>
                    <input class ="btn" name="registerbtn" type="submit" value="Register">
                     <div class="login">
                         <p>Have an existing account? <a href="#" id="loginLink">Log In</a></p>
                     </div>
            </form>
            </div>
            <script src="script.js"></script>
    </body>
</html> 