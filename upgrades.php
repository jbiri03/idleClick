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
        header("Location: index.php");
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
            <!-- UPGRADES -->
             <div id="upgradeSect">
                <h1>Upgrades</h1>
                <ul>
                    <div class ="upgradeButtons">
                        <li><button id="upgrade1">Upgrade 1</button></li>
                        <span id="upgrade1Cost">Cost: $100 | Effect: +1 per click | Level: <span id="upgrade1Level">0</span></span>
                    </div>
                    
                    <li><button id="upgrade2">Upgrade 2</button></li>
                    <li><button id="upgrade3">Upgrade 3</button></li>
                </ul>
             </div>
        </div>


        <!-- GAME STATS -->
        <div class="column3">
            <div id="stats">
                <h1>STATISTICS</h1>

                <h2>BALANCES</h2>
                    <ul>
                        <li>Cakes: <span id="cakeStat"><?php echo $current_cakes; ?></span></li>
                        <li>Cash: $<span id="money"><?php echo $current_currency; ?></span></li>
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
        <!-- CAKE DETAILS -->
        <script src="logic/Cake.js"></script>

        <!-- UPGRADE SCRIPT -->
        <script src="logic/upgrades.js"></script>

    </body>
</html>