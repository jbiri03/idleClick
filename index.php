<?php
    session_start();
    require_once __DIR__ . '/php/config.php';

    $clicks = isset($_POST['clicks']) ? $_POST['clicks'] : 0;
    $currency = isset($_POST['currency']) ? $_POST['currency'] : 0;

    if(isset($_SESSION['user_id'])) {
        try {
            $dsn = "sqlite:$db";
            $pdo = new \PDO($dsn);

            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT cakes, currency FROM player_save WHERE id = :user_id");
            $stmt->execute([':user_id' => $_SESSION['user_id']]);
            $player_data = $stmt->fetch(\PDO::FETCH_ASSOC);

            if($player_data) {
                $current_cakes = (int)$player_data['cakes'];
                $current_currency = (int)$player_data['currency'];

                
            } else {
                echo "Player data not found.";
            }

        } catch (\PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
        }
    } else {
        echo "User not logged in.";
    }
    
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
                    <li><a href="sell_cakes.php"><button>Sell</button></a></li>
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
                    if (isset($_SESSION['username'])): 
                ?>
                <div class="welcome-message">
                    <?php 
                        echo 'WELCOME, ' . strtoupper($_SESSION['username']) . '!';
                        //ID TESTING
                        echo ' ID: ' . $_SESSION['user_id'];
                    ?>
                </div>
                    <?php endif; ?>
            </div>

            <!-- CURRENCY DISPLAY -->
            <div id="currency">   
                <ul>        
                    <li>Cakes: <span id="clickCount1"><?php echo isset($current_cakes) ? $current_cakes : 0; ?></span></li>
                    <li>Currency: $<span id="cash"><?php echo isset($current_currency) ? $current_currency : 0; ?></span></li>
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
                        <li>Cakes: <span id="clickCount2"><?php echo isset($current_cakes) ? $current_cakes : 0; ?></span></li>
                        <li>Cash: $<span id="money"><?php echo isset($current_currency) ? $current_currency : 0; ?></span></li>
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