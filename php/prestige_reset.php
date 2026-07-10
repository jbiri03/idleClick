<?php
session_start();
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $pdo = new PDO("sqlite:$db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Load current game + prestige data
    $stmt = $pdo->prepare("
        SELECT cakes, prestige_points, prestige_multiplier, prestige_level
        FROM player_save
        WHERE id = :id
    ");
    $stmt->execute([':id' => $user_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        echo json_encode(["success" => false, "error" => "No save data found"]);
        exit();
    }

    $currentCakes        = (int)$data['cakes'];
    $prestigePoints      = (int)$data['prestige_points'];
    $prestigeMultiplier  = (float)$data['prestige_multiplier'];
    $prestigeLevel       = (int)$data['prestige_level'];

    // Prestige points earned from cakes
    $earnedPoints = floor($currentCakes / 1000);

    // Add to total prestige points
    $newPrestigePoints = $prestigePoints + $earnedPoints;

    // Prestige level increases by +1 each prestige
    $newPrestigeLevel = $prestigeLevel + 1;

    // New multiplier formula
    $newPrestigeMultiplier = 1.0 + ($newPrestigePoints * 0.1);

    // Reset game state (but keep prestige)
    $stmt2 = $pdo->prepare("
        UPDATE player_save
        SET cakes = 0,
            currency = 0,
            multiplier = 1,
            clickPower = 1,
            cps = 0,
            bonus = 0,
            prestige_points = :pp,
            prestige_multiplier = :pm,
            prestige_level = :pl
        WHERE id = :id
    ");

    $stmt2->execute([
        ':pp' => $newPrestigePoints,
        ':pm' => $newPrestigeMultiplier,
        ':pl' => $newPrestigeLevel,
        ':id' => $user_id
    ]);

    echo json_encode([
        "success" => true,
        "earnedPoints" => $earnedPoints,
        "newPrestigePoints" => $newPrestigePoints,
        "newPrestigeMultiplier" => $newPrestigeMultiplier,
        "newPrestigeLevel" => $newPrestigeLevel
    ]);

    $stmt3 = $pdo->prepare("
    DELETE FROM player_upgrades
    WHERE user_id = :id
    ");
    $stmt3->execute([':id' => $user_id]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
