<?php
session_start();

require_once __DIR__ . '/php/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = (int)$_SESSION['user_id'];

// DEFAULT GAME STATE
$current_cakes = 0;
$current_currency = 0;
$current_multiplier = 1;
$current_clickPower = 1;
$current_cps = 0;
$current_bonus = 0;
$current_prestige_multiplier = 1;
$current_prestige_points = 0;
$current_prestige_level = 0;

$purchased_upgrades = [];

try {
    $dsn = "sqlite:$db";
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // LOAD PLAYER SAVE
    $stmt = $pdo->prepare("
        SELECT 
            cakes,
            currency,
            multiplier,
            clickPower,
            cps,
            bonus,
            prestige_multiplier,
            prestige_points,
            prestige_level
        FROM player_save
        WHERE id = :user_id
        LIMIT 1
    ");
    $stmt->execute([':user_id' => $userId]);
    $player_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($player_data) {
        $current_cakes = (int)($player_data['cakes'] ?? 0);
        $current_currency = (int)($player_data['currency'] ?? 0);
        $current_multiplier = (int)($player_data['multiplier'] ?? 1);
        $current_clickPower = (int)($player_data['clickPower'] ?? 1);
        $current_cps = (int)($player_data['cps'] ?? 0);
        $current_bonus = (int)($player_data['bonus'] ?? 0);
        $current_prestige_multiplier = (float)($player_data['prestige_multiplier'] ?? 1);
        $current_prestige_points = (int)($player_data['prestige_points'] ?? 0);
        $current_prestige_level = (int)($player_data['prestige_level'] ?? 0);
    }

    // LOAD PURCHASED UPGRADES
    $stmt2 = $pdo->prepare("
        SELECT upgrade_name, purchased
        FROM player_upgrades
        WHERE user_id = :user_id
    ");
    $stmt2->execute([':user_id' => $userId]);

    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        $upgradeName = $row['upgrade_name'] ?? '';
        $purchased_upgrades[$upgradeName] = (int)($row['purchased'] ?? 0);
    }

} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
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
                    <li><a href="prestige.php"><button id = "prestigeButton">Prestige</button></a></li>
                    <li><a href="leaderboard.php"><button id = "leaderboard">Leaderboard</button></a></li>
                    <li><a href="settings.php"><button id = "settingsButton">Settings</button></a></li>
                </ul>
            </div>
        </div>


        <div class="column2">

            <!-- SELL -->
            <div id="sellSect">
                <p>Cakes: <span id = cakeCount data-cake><?php echo $current_cakes; ?></span></p>
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
                        <li>Cakes: <span id="cakeStat" data-cake><?php echo $current_cakes; ?></span></li>
                        <li>Cash: $<span id="currency"><?php echo $current_currency; ?></span></li>
                    </ul>

                <h2>PRODUCTION</h2>
                    <ul>
                        <li>Auto-Bake Rate: <?php echo $current_cps?></li>
                        <li>Click Power: <?php echo $current_clickPower ?></li>
                        <li>Multiplier Bonus: <?php echo $current_multiplier?></li>
                        <li>Total Cakes Per Click: <?php echo $current_clickPower * $current_multiplier * $current_prestige_multiplier?></li>
                        <!-- <li>Cake Type: <span id="cakeDetails"></span></li> -->
                    </ul>

                <h2>PROGRESS</h2>
                    <ul>
                        <li>Prestige Multiplier: x<?php echo $current_prestige_multiplier; ?></li>
                        <li>Current Prestige Level: <?php echo $current_prestige_level; ?></li>
                    </ul>

                <div id="upgradeData" style="display:none;"
                    data-multiplier="<?php echo $current_multiplier; ?>"
                    data-clickpower="<?php echo $current_clickPower; ?>"
                    data-cps="<?php echo $current_cps; ?>"
                    data-bonus="<?php echo $current_bonus; ?>">
                </div>


            </div>
        </div>
        <!-- HIDDEN GAME STATE FOR JS -->
    <span id="multiplierStat" style="display:none;"><?php echo $current_multiplier; ?></span>
    <span id="clickPowerStat" style="display:none;"><?php echo $current_clickPower; ?></span>
    <span id="cpsStat" style="display:none;"><?php echo $current_cps; ?></span>
    <span id="bonusStat" style="display:none;"><?php echo $current_bonus; ?></span>

        <!-- SCRIPTS -->

        <!-- SELL SCRIPT -->
        <script src="logic/sell.js"></script>
        
    </body>
</html>