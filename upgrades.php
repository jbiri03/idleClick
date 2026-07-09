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
            SELECT cakes, currency, multiplier, clickPower, cps, bonus
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
        }
        else {
            $current_cakes = 0;
            $current_currency = 0;
            $current_multiplier = 1;
            $current_clickPower = 1;
            $current_cps = 0;
            $current_bonus = 0;
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
            <li><a href="prestige.html"><button>Prestige</button></a></li>
            <li><a href="settings.php"><button id="settingsButton">Settings</button></a></li>
        </ul>
    </div>
</div>

<div class="column2">
    <div id="upgradeSect">
        <h1>Upgrades</h1>

        <input type="text" placeholder="Search..">

        <div id="upgradeTable">
            <table>
                <tr>
                    <th>Upgrade</th>
                    <th>Effect</th>
                    <th>Cost</th>
                </tr>
                <tr>
                    <!-- CLICK UPGRADES -->
                    <td>
                        <button id="upgradeClick1" <?php if ($Click1Purchased) echo "disabled"; ?>>
                            <?php echo $Click1Purchased ? "Purchased" : "Stronger Clicks I"; ?>
                        </button>
                    </td>
                    <td>+4 Cakes Per Click</td>
                    <td>$1000</td>
                </tr>
                <tr>
                    <td>
                        <button id="upgradeClick2" <?php if ($Click2Purchased) echo "disabled"; ?>>
                            <?php echo $Click2Purchased ? "Purchased" : "Stronger Clicks II"; ?>
                        </button>
                    </td>
                    <td>+5 Cakes Per Click</td>
                    <td>$10000</td>
                </tr>
                <tr>
                    <td>
                        <button id="upgradeClick3" <?php if ($Click3Purchased) echo "disabled"; ?>>
                            <?php echo $Click3Purchased ? "Purchased" : "Stronger Clicks III"; ?>
                        </button>
                    </td>
                    <td>+10 Cakes Per Click</td>
                    <td>$100000</td>
                </tr>
                <!-- MULTIPLIER UPGRADES -->
                <tr>
                    <td>
                        <button id="sugarMult1" <?php if ($Sugar1Purchased) echo "disabled"; ?>>
                            <?php echo $Sugar1Purchased ? "Purchased" : "More Sugar I"; ?>
                        </button>
                    </td>
                    <td>x2 Cakes Per Click</td>
                    <td>$3000</td>
                </tr> 
                <tr>
                    <td>
                        <button id="sugarMult2" <?php if ($Sugar2Purchased) echo "disabled"; ?>>
                            <?php echo $Sugar2Purchased ? "Purchased" : "More Sugar II"; ?>
                        </button>
                    </td>
                    <td>x2 Cakes Per Click</td>
                    <td>$30000</td>
                </tr>
                <tr>
                    <td>
                        <button id="sugarMult3" <?php if ($Sugar3Purchased) echo "disabled"; ?>>
                            <?php echo $Sugar3Purchased ? "Purchased" : "More Sugar III"; ?>
                        </button>
                    </td>
                    <td>x2 Cakes Per Click</td>
                    <td>$300000</td>
                </tr>
                <tr>
                    <td>
                        <button id="autoBake1" <?php if ($Baker1Purchased) echo "disabled"; ?>>
                            <?php echo $Baker1Purchased ? "Purchased" : "Basic Auto Baker I"; ?>
                        </button>
                    </td>
                    <td>Autoclicker: +1 Cake/sec</td>
                    <td>$5000</td>
                </tr>
                <tr>
                    <td>
                        <button id="autoBake2" <?php if ($Baker2Purchased) echo "disabled"; ?>>
                            <?php echo $Baker2Purchased ? "Purchased" : "Basic Auto Baker II"; ?>
                        </button>
                    </td>
                    <td>Autoclicker: +10 Cakes/sec</td>
                    <td>$50000</td>
                </tr>
                <tr>
                    <td>
                        <button id="autoBake3" <?php if ($Baker3Purchased) echo "disabled"; ?>>
                            <?php echo $Baker3Purchased ? "Purchased" : "Basic Auto Baker III"; ?>
                        </button>
                    </td>
                    <td>Autoclicker: +25 Cakes/sec</td>
                    <td>$50000</td>
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
            <li>Cakes: <span id="cakeStat" data-cake><?php echo $current_cakes; ?></span></li>
            <li>Cash: $<span id="money"><?php echo $current_currency; ?></span></li>
        </ul>

        <h2>PRODUCTION</h2>
        <ul>
            <li>Auto-Bake Rate: <?php echo $current_cps?></li>
            <li>Click Power: <?php echo $current_clickPower ?></li>
            <li>Multiplier Bonus: <?php echo $current_multiplier?></li>
            <li>Total Cakes Per Click: <?php echo $current_clickPower * $current_multiplier?></li>
            <!-- <li>Cake Type: <span id="cakeDetails"></span></li> -->
        </ul>

        <h2>PROGRESS</h2>
        <ul>
            <li>Prestige Multiplier: {}</li>
            <li>Current Prestige Level: {} </li>
        </ul>

        <button>SHARE</button>
    </div>
</div>

<!-- HIDDEN GAME STATE FOR JS -->
<span id="multiplierStat" style="display:none;"><?php echo $current_multiplier; ?></span>
<span id="clickPowerStat" style="display:none;"><?php echo $current_clickPower; ?></span>
<span id="cpsStat" style="display:none;"><?php echo $current_cps; ?></span>
<span id="bonusStat" style="display:none;"><?php echo $current_bonus; ?></span>

<!-- SCRIPTS -->
<!-- <script src="logic/Cake.js"></script> -->
<script type="module" src="logic/upgradesPage.js"></script>
<script type="module" src="logic/game.js"></script>


</body>
</html>
