<?php
$emailErr = "";
$usernameErr = "";
$passwordErr = "";
$confirmErr = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require 'connect.php';
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
            <!-- EMAIL -->
            <div id="emailInput">       
                <li><label for="email">Email:</label></li> 
                <li><input type="text" id="email" name="email"><span>*<?= htmlspecialchars($emailErr) ?></span></li> 

            </div> 
            <!-- USERNAME --> 
            <div id="usernameInput">       
                <li><label for="username">Username:</label></li> 
                <li><input type="text" id="username" name="username"><span>*<?= htmlspecialchars($usernameErr) ?></span></li>  
            </div> 
            <!-- PASSWORD -->
            <div id="passwordInput">
                <li><label for="password">Password:</label></li>
                <li><input type="password" id="password" name="password"><span>*<?= htmlspecialchars($passwordErr) ?></span></li>
            </div>

            <!-- CONFIRM PASSWORD -->
            <div id="passwordConfirm">
                <li><label for="confirm">Confirm Password:</label></li>
                <li><input type="password" id="confirm" name="confirm"><span>*<?= htmlspecialchars($confirmErr) ?></span></li>
            </div>

            <!-- SUBMIT BUTTON -->
            <button name="button">Submit</button>

            <!-- PASSWORD REQUIREMENTS -->
            <div id="message">
                <h3>Password must contain the following:</h3>
                <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                <p id="number" class="invalid">A <b>number</b></p>
                <p id="length" class="invalid">Minimum <b>8 characters</b></p>
            </div>
        </ul>
    </form>
    <!-- SCRIPTS -->
     <!-- PASSWORD VALIDATION -->
    <script src="../logic/form.js"></script>
</body>
</html>