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

try {
    $pdo = new PDO("sqlite:$db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        SELECT cakes, currency, multiplier, clickPower, cps, bonus,
               prestige_multiplier, prestige_points, prestige_level
        FROM player_save
        WHERE id = :user_id
        LIMIT 1
    ");
    $stmt->execute([':user_id' => $userId]);
    $player_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($player_data) {
        $current_cakes               = (int)$player_data['cakes'];
        $current_currency            = (int)$player_data['currency'];
        $current_multiplier          = (int)$player_data['multiplier'];
        $current_clickPower          = (int)$player_data['clickPower'];
        $current_cps                 = (int)$player_data['cps'];
        $current_bonus               = (int)$player_data['bonus'];
        $current_prestige_multiplier = (float)$player_data['prestige_multiplier'];
        $current_prestige_points     = (int)$player_data['prestige_points'];
        $current_prestige_level      = (int)$player_data['prestige_level'];
    }

} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cake Idle Clicker — Leaderboard</title>
    <link rel="stylesheet" href="Styles/index.css">
</head>
<body>

<div class="column1">
    <div id="nav">
        <ul>
            <li><a href="index.php"><button>Home</button></a></li>
            <li><a href="sell_cakes.php"><button>Sell</button></a></li>
            <li><a href="upgrades.php"><button>Upgrades</button></a></li>
            <li><a href="prestige.php"><button>Prestige</button></a></li>
            <li><a href="leaderboard.php"><button class="active">Leaderboard</button></a></li>
            <li><a href="settings.php"><button>Settings</button></a></li>
        </ul>
    </div>
</div>

<div class="column2">
    <h1 class="leaderboard-title">🏆 Global Leaderboard</h1>

    <div class="report-buttons">
        <button id="topCurrencyBtn">💰 Top Earners</button>
        <button id="topCakesBtn">🎂 Master Bakers</button>
        <button id="topPrestigeBtn">🏆 Prestige Legends</button>
    </div>

    <table class="leaderboard-table">
        <thead>
            <tr id="leaderboardHeaderRow">
                <th>Username</th>
                <th>Currency</th>
            </tr>
        </thead>
        <tbody id="leaderboardResults">
            <!-- JS will populate this -->
        </tbody>
    </table>
</div>

<div class="column3">
    <div id="stats">
        <h1>STATISTICS</h1>

        <h2>BALANCES</h2>
        <ul>
            <li>Cakes: <span><?php echo $current_cakes; ?></span></li>
            <li>Cash: $<span><?php echo $current_currency; ?></span></li>
        </ul>

        <h2>PRODUCTION</h2>
        <ul>
            <li>Auto-Bake Rate: <?php echo $current_cps; ?></li>
            <li>Click Power: <?php echo $current_clickPower; ?></li>
            <li>Multiplier Bonus: <?php echo $current_multiplier; ?></li>
            <li>Total Cakes Per Click: <?php echo $current_clickPower * $current_multiplier * $current_prestige_multiplier; ?></li>
        </ul>

        <h2>PROGRESS</h2>
        <ul>
            <li>Prestige Multiplier: x<?php echo $current_prestige_multiplier; ?></li>
            <li>Prestige Level: <?php echo $current_prestige_level; ?></li>
        </ul>
    </div>
</div>

<script src="logic/leaderboard.js"></script>
</body>
</html>