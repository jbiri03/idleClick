<?php
    session_start();
    
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cake Idle Clicker</title>
        <link rel="stylesheet" href="Styles/index.css">
    </head>
    <body>
        <!-- NAVIGATION OF BASE FEATURES-->
        <div class="column1"> 
            <div id="nav">
                <ul>
                    <li><a href="index.php"><button>Home</button></a></li>
                    <li><a href="sell.html"><button>Sell</button></a></li>
                    <li><a href="upgrades.html"><button>Upgrades</button></a></li>
                    <li><a href="pets.html"><button>Pets</button></a></li>
                    <li><a href="prestige.html"><button>Prestige</button></a></li>
                    <li><a href="inventory.html"><button>Inventory</button></a> </li>
                </ul>
            </div>
        </div>

        <!-- ADDITIONAL FEATURES TO BE ADDED-->
        <!-- <a href="shop.html"><button>Shop</button></a> -->

        <div class="column2">
            <!-- SIGN IN -->

            <div id="topBar">
                <button><a href="php/login.php">Login</a></button>
                <button>Daily Bonus</button>
                <button id="saveButton">Save Game</button>
                <?php 
                    if (isset($_SESSION['welcome_message'])): 
                ?>
                <div class="welcome-message">
                    <?php 
                        echo strtoupper($_SESSION['welcome_message']); 
                        //ID TESTING
                        echo $_SESSION['user_id'];
                        unset($_SESSION['welcome_message']);
                    ?>
                </div>
                    <?php endif; ?>
            </div>

            <!-- CURRENCY DISPLAY -->
            <div id="currency">   
                <ul>        
                    <li>Cakes: <span id="clickCount">0</span></li>
                    <li>Currency: $<span id="cash">0</span></li>
                </ul>
            </div>

            <!-- CLICKER IMAGE -->
            <div id="cake">
                <button id="clicker"><img src="Images/Cake_Placeholder.jpg" alt="Cake"></button>
            </div>
        </div>

 
        <!-- GAME STATS -->
        <div class="column3">
            <div id="stats">
                <h1>STATISTICS</h1>

                <h2>BALANCES</h2>
                    <ul>
                        <li>Cakes: <span id="cakeStat">0</span></li>
                        <li>Cash: $<span id="money">0</span></li>
                    </ul>

                <h2>PRODUCTION</h2>
                    <ul>
                        <li>Per Click: {}</li>
                        <li>Cake Type: <span id="cakeDetails"></span></li>
                    </ul>

                <h2>PROGRESS</h2>
                    <ul>
                        <li>Prestige Multiplier: {}</li>
                        <li>Current Prestige Level: {} </li>
                    </ul>

                <button>SHARE</button>
            </div>
        </div>

        <!-- SCRIPTS -->
        <!-- CLICK COUNTER -->
        <script src="logic/clicker.js"></script>

        <!-- CAKE DETAILS -->
         <script src="logic/Cake.js"></script>
    </body>
</html>