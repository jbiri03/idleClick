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
            prestige_points
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

// PURCHASE FLAGS
$Click1Purchased = $purchased_upgrades["Stronger Clicks I"] ?? 0;
$Click2Purchased = $purchased_upgrades["Stronger Clicks II"] ?? 0;
$Click3Purchased = $purchased_upgrades["Stronger Clicks III"] ?? 0;

$Sugar1Purchased = $purchased_upgrades["More Sugar I"] ?? 0;
$Sugar2Purchased = $purchased_upgrades["More Sugar II"] ?? 0;
$Sugar3Purchased = $purchased_upgrades["More Sugar III"] ?? 0;

$Baker1Purchased = $purchased_upgrades["Basic Auto Baker I"] ?? 0;
$Baker2Purchased = $purchased_upgrades["Basic Auto Baker II"] ?? 0;
$Baker3Purchased = $purchased_upgrades["Basic Auto Baker III"] ?? 0;

function upgradeButtonText($isPurchased, $defaultText) {
    return $isPurchased ? "Purchased" : $defaultText;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cake Idle Clicker - Upgrades</title>
    <link rel="stylesheet" href="Styles/index.css">
</head>
<body>

<div class="column1">
    <div id="nav">
        <ul>
            <li><a href="index.php"><button type="button">Home</button></a></li>
            <li><a href="sell_cakes.php"><button type="button">Sell</button></a></li>
            <li><a href="upgrades.php"><button type="button">Upgrades</button></a></li>
            <li><a href="prestige.php"><button type="button">Prestige</button></a></li>
            <li><a href="leaderboard.php"><button id = "leaderboard">Leaderboard</button></a></li>
            <li><a href="settings.php"><button type="button" id="settingsButton">Settings</button></a></li>
        </ul>
    </div>
</div>

<div class="column2">
    <div id="upgradeSect">
        <h1>Upgrades</h1>

        <div id="upgradeSearchContainer">
            <input
                type="text"
                id="upgradeSearch"
                placeholder="Search upgrades..."
                autocomplete="off"
            >
        </div>

        <div id="upgradeTable">
            <table>
                <thead>
                    <tr>
                        <th>Upgrade</th>
                        <th>Effect</th>
                        <th>Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <button id="upgradeClick1" <?= $Click1Purchased ? 'disabled' : '' ?>>
                                <?= htmlspecialchars(upgradeButtonText($Click1Purchased, "Stronger Clicks I"), ENT_QUOTES, 'UTF-8') ?>
                            </button>
                        </td>
                        <td>+4 Cakes Per Click</td>
                        <td>$1000</td>
                    </tr>
                    <tr>
                        <td>
                            <button id="upgradeClick2" <?= $Click2Purchased ? 'disabled' : '' ?>>
                                <?= htmlspecialchars(upgradeButtonText($Click2Purchased, "Stronger Clicks II"), ENT_QUOTES, 'UTF-8') ?>
                            </button>
                        </td>
                        <td>+5 Cakes Per Click</td>
                        <td>$10000</td>
                    </tr>
                    <tr>
                        <td>
                            <button id="upgradeClick3" <?= $Click3Purchased ? 'disabled' : '' ?>>
                                <?= htmlspecialchars(upgradeButtonText($Click3Purchased, "Stronger Clicks III"), ENT_QUOTES, 'UTF-8') ?>
                            </button>
                        </td>
                        <td>+10 Cakes Per Click</td>
                        <td>$100000</td>
                    </tr>

                    <tr>
                        <td>
                            <button id="sugarMult1" <?= $Sugar1Purchased ? 'disabled' : '' ?>>
                                <?= htmlspecialchars(upgradeButtonText($Sugar1Purchased, "More Sugar I"), ENT_QUOTES, 'UTF-8') ?>
                            </button>
                        </td>
                        <td>x2 Cakes Per Click</td>
                        <td>$3000</td>
                    </tr>
                    <tr>
                        <td>
                            <button id="sugarMult2" <?= $Sugar2Purchased ? 'disabled' : '' ?>>
                                <?= htmlspecialchars(upgradeButtonText($Sugar2Purchased, "More Sugar II"), ENT_QUOTES, 'UTF-8') ?>
                            </button>
                        </td>
                        <td>x2 Cakes Per Click</td>
                        <td>$30000</td>
                    </tr>
                    <tr>
                        <td>
                            <button id="sugarMult3" <?= $Sugar3Purchased ? 'disabled' : '' ?>>
                                <?= htmlspecialchars(upgradeButtonText($Sugar3Purchased, "More Sugar III"), ENT_QUOTES, 'UTF-8') ?>
                            </button>
                        </td>
                        <td>x2 Cakes Per Click</td>
                        <td>$300000</td>
                    </tr>

                    <tr>
                        <td>
                            <button id="autoBake1" <?= $Baker1Purchased ? 'disabled' : '' ?>>
                                <?= htmlspecialchars(upgradeButtonText($Baker1Purchased, "Basic Auto Baker I"), ENT_QUOTES, 'UTF-8') ?>
                            </button>
                        </td>
                        <td>Autoclicker: +1 Cake/sec</td>
                        <td>$5000</td>
                    </tr>
                    <tr>
                        <td>
                            <button id="autoBake2" <?= $Baker2Purchased ? 'disabled' : '' ?>>
                                <?= htmlspecialchars(upgradeButtonText($Baker2Purchased, "Basic Auto Baker II"), ENT_QUOTES, 'UTF-8') ?>
                            </button>
                        </td>
                        <td>Autoclicker: +9 Cakes/sec</td>
                        <td>$50000</td>
                    </tr>
                    <tr>
                        <td>
                            <button id="autoBake3" <?= $Baker3Purchased ? 'disabled' : '' ?>>
                                <?= htmlspecialchars(upgradeButtonText($Baker3Purchased, "Basic Auto Baker III"), ENT_QUOTES, 'UTF-8') ?>
                            </button>
                        </td>
                        <td>Autoclicker: +25 Cakes/sec</td>
                        <td>$500000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="column3">
    <div id="stats">
        <h1>STATISTICS</h1>

        <h2>BALANCES</h2>
        <ul>
            <li>Cakes: <span id="cakeStat" data-cake><?= $current_cakes ?></span></li>
            <li>Cash: $<span id="money"><?= $current_currency ?></span></li>
        </ul>

        <h2>PRODUCTION</h2>
        <ul id="productionStats">
            <li>Auto-Bake Rate: <span id="autoBakeRateText"><?= $current_cps ?></span></li>
            <li>Click Power: <span id="clickPowerText"><?= $current_clickPower ?></span></li>
            <li>Multiplier Bonus: <span id="multiplierText"><?= $current_multiplier ?></span></li>
            <li>Total Cakes Per Click: <span id="totalClickText"><?= $current_clickPower * $current_multiplier * $current_prestige_multiplier ?></span></li>
        </ul>

        <h2>PROGRESS</h2>
        <ul>
            <li>Prestige Multiplier: x<span id="prestigeMultiplierText"><?= $current_prestige_multiplier ?></span></li>
            <li>Current Prestige Level: <span id="prestigePointsText"><?= $current_prestige_points ?></span></li>
        </ul>
    </div>
</div>

<!-- HIDDEN GAME STATE FOR JS -->
<span id="multiplierStat" style="display:none;"><?= $current_multiplier ?></span>
<span id="clickPowerStat" style="display:none;"><?= $current_clickPower ?></span>
<span id="cpsStat" style="display:none;"><?= $current_cps ?></span>
<span id="bonusStat" style="display:none;"><?= $current_bonus ?></span>
<span id="prestigeMultiplierStat" style="display:none;"><?= $current_prestige_multiplier ?></span>
<span id="prestigePointsStat" style="display:none;"><?= $current_prestige_points ?></span>

<script type="module" src="logic/upgradesPage.js"></script>
</body>
</html>