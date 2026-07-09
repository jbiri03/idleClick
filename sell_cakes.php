<?php
session_start();
require_once __DIR__ . '/php/config.php';

$current_cakes = 0;
$current_currency = 0;
$current_multiplier = 1;
$current_clickPower = 1;
$current_cps = 0;
$current_bonus = 0;

if (isset($_SESSION['user_id'])) {
    try {
        $dsn = "sqlite:$db";
        $pdo = new \PDO($dsn);

        $stmt = $pdo->prepare("
            SELECT cakes, currency, multiplier, clickPower, cps, bonus
            FROM player_save
            WHERE id = :user_id
        ");

        $stmt->execute([':user_id' => $_SESSION['user_id']]);
        $player_data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($player_data) {
            $current_cakes = (int)$player_data['cakes'];
            $current_currency = (int)$player_data['currency'];
            $current_multiplier = (float)$player_data['multiplier'];
            $current_clickPower = (int)$player_data['clickPower'];
            $current_cps = (int)$player_data['cps'];
            $current_bonus = (int)$player_data['bonus'];
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
                    <li><a href="index.php"><button id = "homeButton">Home</button></a></li>
                    <li><a href="sell_cakes.php"><button id = "sellButton">Sell</button></a></li>
                    <li><a href="upgrades.php"><button id = "upgradesButton">Upgrades</button></a></li>
                    <li><a href="prestige.html"><button id = "prestigeButton">Prestige</button></a></li>
                    <li><a href="settings.php"><button id = "settingsButton">Settings</button></a></li>
                    <!-- <li><a href="pets.html"><button id = "petsButton">Pets</button></a></li>
                    <li><a href="inventory.html"><button id = "inventoryButton">Inventory</button></a> </li> -->
                </ul>
            </div>
        </div>

        <!-- ADDITIONAL FEATURES TO BE ADDED-->
        <!-- <a href="shop.html"><button>Shop</button></a> -->

        <div class="column2">

            <!-- SELL -->
            <div id="sellSect">
                <p>Cakes: <span id = cakeCount><?php echo $current_cakes; ?></span></p>
                <p>Current Market Price: $<span id="cakePrice">0</span></p>
                <button id="sell-button">Sell Cakes</button>
            </div>
        </div>


        <!-- GAME STATS -->
        <div class="column3">
            <div id="stats">
                <h1>STATISTICS</h1>

                <h2>BALANCES</h2>
                    <ul>
                        <li>Cakes: <span id="cakeStat"><?php echo $current_cakes; ?></span></li>
                        <li>Cash: $<span id="currency"><?php echo $current_currency; ?></span></li>
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
                <div id="upgradeData" style="display:none;"
                    data-multiplier="<?php echo $current_multiplier; ?>"
                    data-clickpower="<?php echo $current_clickPower; ?>"
                    data-cps="<?php echo $current_cps; ?>"
                    data-bonus="<?php echo $current_bonus; ?>">
                </div>


            </div>
        </div>

        <!-- SCRIPTS -->
        <!-- CAKE DETAILS -->
        <script src="logic/Cake.js"></script>

        <!-- SELL SCRIPT -->
        <script src="logic/sell.js"></script>
    </body>
</html>