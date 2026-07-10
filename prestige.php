<?php
session_start();

//INITIALIZE VARIABLES
$current_cakes = 0;
$current_currency = 0;
$current_multiplier = 1;
$current_clickPower = 1;
$current_cps = 0;
$current_bonus = 0;

require_once __DIR__ . '/php/config.php';

if(isset($_SESSION['user_id'])) {
    try {
        $dsn = "sqlite:$db";
        $pdo = new \PDO($dsn);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // LOAD FULL GAME STATE
        $stmt = $pdo->prepare("
            SELECT cakes, currency, multiplier, clickPower, cps, bonus, prestige_multiplier, prestige_level, prestige_points
            FROM player_save
            WHERE id = :user_id
        ");
        $stmt->execute([':user_id' => $_SESSION['user_id']]);
        $player_data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($player_data) {
            $current_cakes = (int)$player_data['cakes'];
            $current_currency = (int)$player_data['currency'];
            $current_multiplier = (int)$player_data['multiplier'];
            $current_clickPower = (int)$player_data['clickPower'];
            $current_cps = (int)$player_data['cps'];
            $current_bonus = (int)$player_data['bonus'];

            $current_prestige_multiplier = (float)$player_data['prestige_multiplier'];
            $current_prestige_level = (int)$player_data['prestige_level'];
            $current_prestige_points = (int)$player_data['prestige_points'];
        }

        else {
            $current_cakes = 0;
            $current_currency = 0;
            $current_multiplier = 1;
            $current_clickPower = 1;
            $current_cps = 0;
            $current_bonus = 0;
            $current_prestige_multiplier = 1;
            $current_prestige_level = 0;
        }

    } catch (\PDOException $e) {
        echo "Database connection failed: " . $e->getMessage();
    }
} else {
    header("Location: index.php");
    exit();
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

//UPGRADE VARIABLES
$Click1Purchased = $purchased_upgrades["Stronger Clicks I"] ?? 0;
$Click2Purchased = $purchased_upgrades["Stronger Clicks II"] ?? 0;
$Click3Purchased = $purchased_upgrades["Stronger Clicks III"] ?? 0;

$Sugar1Purchased = $purchased_upgrades["More Sugar I"] ?? 0;
$Sugar2Purchased = $purchased_upgrades["More Sugar II"] ?? 0;
$Sugar3Purchased = $purchased_upgrades["More Sugar III"] ?? 0;

$Baker1Purchased = $purchased_upgrades["Basic Auto Baker I"] ?? 0;
$Baker2Purchased = $purchased_upgrades["Basic Auto Baker II"] ?? 0;
$Baker3Purchased = $purchased_upgrades["Basic Auto Baker III"] ?? 0;

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

<!-- NAVIGATION -->
<div class="column1"> 
    <div id="nav">
        <ul>
            <li><a href="index.php"><button>Home</button></a></li>
            <li><a href="sell_cakes.php"><button>Sell</button></a></li>
            <li><a href="upgrades.php"><button>Upgrades</button></a></li>
            <li><a href="prestige.php"><button>Prestige</button></a></li>
            <li><a href="settings.php"><button id="settingsButton">Settings</button></a></li>
        </ul>
    </div>
</div>

<div class="column2">
    <div class="prestige-container">

        <h1>Prestige</h1>

        <p class="prestige-info">
            Total Cakes Baked: <strong id="totalCakesBaked">0</strong>
        </p>

        <p class="prestige-info">
            Prestige Points Available: <strong id="prestigePoints"><?php echo $current_cakes/1000?></strong>
        </p>

        <p class="prestige-info">
            New Prestige Multiplier: <strong id="newPrestigeMultiplier">x1</strong>
        </p>

        <button id="prestigeButton" class="prestige-btn">
            Prestige & Reset Progress
        </button>

        <p class="prestige-warning">
            Prestiging will reset your cakes, cash, upgrades, and production.<br>
            Your prestige multiplier is permanent.
        </p>

    </div>

</div>

<!-- GAME STATS -->
<div class="column3">
    <div id="stats">
        <h1>STATISTICS</h1>

        <h2>BALANCES</h2>
        <ul>
            <li>Cakes: <span id="cakeStat" data-cake><?php echo $current_cakes; ?></span></li>
            <li>Cash: $<span id="money"><?php echo $current_currency; ?></span></li>
        </ul>

        <h2>PRODUCTION</h2>
        <ul>
            <li>Auto-Bake Rate: <?php echo $current_cps?></li>
            <li>Click Power: <?php echo $current_clickPower ?></li>
            <li>Multiplier Bonus: <?php echo $current_multiplier?></li>
            <li>Total Cakes Per Click: <?php echo $current_clickPower * $current_multiplier?></li>
        </ul>

        <h2>PROGRESS</h2>
        <ul>
            <li>Prestige Multiplier: x<?php echo $current_prestige_multiplier; ?></li>
            <li>Current Prestige Level: <?php echo $current_prestige_level; ?></li>
        </ul>
    </div>
</div>

<!-- HIDDEN GAME STATE FOR JS -->
<span id="multiplierStat" style="display:none;"><?php echo $current_multiplier; ?></span>
<span id="clickPowerStat" style="display:none;"><?php echo $current_clickPower; ?></span>
<span id="cpsStat" style="display:none;"><?php echo $current_cps; ?></span>
<span id="bonusStat" style="display:none;"><?php echo $current_bonus; ?></span>
<span id="prestigeMultiplierStat" style="display:none;"><?php echo $current_prestige_multiplier; ?></span>
<span id="prestigePointsStat" style="display:none;"><?php echo $current_prestige_points; ?></span>



<!-- SCRIPTS -->
<!-- <script src="logic/Cake.js"></script> -->
<script type="module" src="logic/game.js"></script>

<script type="module" src="logic/prestige.js"></script>


</body>
</html>
