<?php
    session_start();

    //INITIALIZE VARIABLES
    $current_cakes = 0;
    $current_currency = 0;

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
            }

        } catch (\PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
        }
    } else {
        header("Location: index.php");
    }

        // LOAD PURCHASED UPGRADES
    $purchased_upgrades = [];

    $stmt2 = $pdo->prepare("
        SELECT upgrade_name, purchased
        FROM player_upgrades
        WHERE user_id = :user_id
    ");

    $stmt2->execute([':user_id' => $_SESSION['user_id']]);

    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        $purchased_upgrades[$row['upgrade_name']] = $row['purchased'];
    }

    $Sugar1Purchased = $purchased_upgrades["More Sugar I"] ?? 0;
    $Click1Purchased = $purchased_upgrades["Stronger Clicks I"] ?? 0;


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
                    <li><a href="upgrades.php"><button>Upgrades</button></a></li>
                    <li><a href="prestige.html"><button>Prestige</button></a></li>
                    <li><a href="settings.php"><button id = "settingsButton">Settings</button></a></li>
                    <!-- <li><a href="pets.html"><button>Pets</button></a></li>
                    <li><a href="inventory.html"><button>Inventory</button></a> </li> -->
                </ul>
            </div>
        </div>

        <!-- ADDITIONAL FEATURES TO BE ADDED-->
        <!-- <a href="shop.html"><button>Shop</button></a> -->

        <div class="column2">
            <!-- UPGRADES -->
             <div id="upgradeSect">
                <h1>Upgrades</h1>
                <!-- SEARCH BAR -->
                <input type="text" placeholder="Search..">
                    <div id = "upgradeTable">
                        <table>
                            <tr>
                                <th>Upgrade</th>
                                <th>Effect</th>
                                <th>Cost</th>
                            </tr>
                            <tr>
                                <td><button id="upgradeSugar1" <?php if ($Sugar1Purchased) echo "disabled"; ?>> <?php echo $Sugar1Purchased ? "Purchased" : "More Sugar I"; ?></button></td>
                                <td>x2 Cakes Per Click</td>
                                <td>$3000</td>
                            </tr>
                            <tr>
                                <td><button id="upgradeClick1" <?php if ($Click1Purchased) echo "disabled"; ?>> <?php echo $Click1Purchased ? "Purchased" : "Stronger Clicks I"; ?></button></td>
                                <td>+5 Cakes Per click</td>
                                <td>$10000</td>
                            </tr>
                        </table>
                    </div>
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

        <!-- UPGRADES SCRIPT -->
        <script type="module" src="logic/game.js"></script>
        <script type="module" src="logic/upgradesPage.js"></script>

    </body>
</html>