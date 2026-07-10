<?php
session_start();
$errMsg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require 'match.php';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cake Idle Clicker</title>
    <link rel="stylesheet" href="../Styles/login.css">
</head> 
<body>
    <!-- LOGIN FORM -->
    <form id="loginForm" method="POST" action="">
        <ul>
            <?php 
                if (isset($_SESSION['success_message'])): 
            ?>
            <div class="success-message">
                <?php 
                    echo $_SESSION['success_message']; 
                    unset($_SESSION['success_message']);
                ?>
            </div>
            <?php endif; ?>

            <!-- CREATE ACCOUNT -->
            <a href="new_user.php">Don't have an account? Register here.</a>
            
            <!-- USERNAME -->
            <div id="usernameInput">       
                <li><label for="username">Username:</label></li>
                <li><input type="text" id="username" name="username"></li>
            </div> 
            <!-- PASSWORD -->
            <div id="passwordInput">
                <li><label for="password">Password:</label></li>
                <li><input type="password" id="password" name="password"></li>
            </div>
            <!-- SUBMIT BUTTON -->
            <button>SUBMIT</button>
            <li><span><?= htmlspecialchars($errMsg) ?></span></li>
        </ul>
    </form>
</body>
</html>