<?php
session_start();
require_once __DIR__ . '/php/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = (int)$_SESSION['user_id'];

$username = "Unknown";
$email = "Unknown";
$current_cakes = 0;
$current_currency = 0;
$current_multiplier = 1;
$current_clickPower = 1;
$current_cps = 0;
$current_prestige_multiplier = 1;
$current_prestige_points = 0;
$current_prestige_level = 0;

try {
    $pdo = new PDO("sqlite:$db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        SELECT
            u.username,
            u.email,
            ps.cakes,
            ps.currency,
            ps.multiplier,
            ps.clickPower,
            ps.cps,
            ps.prestige_multiplier,
            ps.prestige_points,
            ps.prestige_level
        FROM player_save ps
        INNER JOIN users u ON ps.id = u.id
        WHERE ps.id = :user_id
        LIMIT 1
    ");
    $stmt->execute([':user_id' => $userId]);
    $player_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($player_data) {
        $username = $player_data['username'] ?? 'Unknown';
        $email = $player_data['email'] ?? 'Unknown';
        $current_cakes = (int)$player_data['cakes'];
        $current_currency = (int)$player_data['currency'];
        $current_multiplier = (int)$player_data['multiplier'];
        $current_clickPower = (int)$player_data['clickPower'];
        $current_cps = (int)$player_data['cps'];
        $current_prestige_multiplier = (float)$player_data['prestige_multiplier'];
        $current_prestige_points = (int)$player_data['prestige_points'];
        $current_prestige_level = (int)$player_data['prestige_level'];
    }

} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}

$generated_at = date('Y-m-d H:i:s');
$total_cakes_per_click = $current_clickPower * $current_multiplier * $current_prestige_multiplier;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Progress Report</title>
    <link rel="stylesheet" href="Styles/player_report.css">
</head>

<body class="player-report">

<div class="report-wrapper">

    <h1 class="report-title">Player Progress Report</h1>

    <div class="report-meta">
        <p><strong>Report Title:</strong> Current Player Progress Summary</p>
        <p><strong>Generated At:</strong> <?php echo htmlspecialchars($generated_at); ?></p>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    </div>

    <div class="report-table-wrapper">
        <table class="report-table">
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>Username</td><td><?php echo htmlspecialchars($username); ?></td></tr>
                <tr><td>Email</td><td><?php echo htmlspecialchars($email); ?></td></tr>
                <tr><td>Cakes</td><td><?php echo $current_cakes; ?></td></tr>
                <tr><td>Currency</td><td><?php echo $current_currency; ?></td></tr>
                <tr><td>Click Power</td><td><?php echo $current_clickPower; ?></td></tr>
                <tr><td>Multiplier</td><td><?php echo $current_multiplier; ?></td></tr>
                <tr><td>Auto-Bake Rate</td><td><?php echo $current_cps; ?></td></tr>
                <tr><td>Prestige Multiplier</td><td><?php echo $current_prestige_multiplier; ?></td></tr>
                <tr><td>Prestige Points</td><td><?php echo $current_prestige_points; ?></td></tr>
                <tr><td>Prestige Level</td><td><?php echo $current_prestige_level; ?></td></tr>
                <tr><td>Total Cakes Per Click</td><td><?php echo $total_cakes_per_click; ?></td></tr>
            </tbody>
        </table>
    </div>

    <div class="report-actions">
        <button class="orange-btn" onclick="window.location.href='index.php'">Back to Home</button>
        <button class="orange-btn" onclick="window.print()">Print / Save PDF</button>
    </div>

</div>

</body>
</html>
