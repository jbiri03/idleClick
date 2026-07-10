<?php
session_start();
require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Not logged in'
    ]);
    exit();
}

$user_id = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid request method'
    ]);
    exit();
}

try {
    $pdo = new PDO("sqlite:$db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Load existing save first so missing POST fields don't overwrite valid data
    $stmt = $pdo->prepare("
        SELECT
            cakes,
            currency,
            multiplier,
            clickPower,
            cps,
            bonus,
            prestige_points,
            prestige_multiplier,
            prestige_level
        FROM player_save
        WHERE id = :user_id
        LIMIT 1
    ");
    $stmt->execute([':user_id' => $user_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

    $clicks = isset($_POST['clicks'])
        ? (float)$_POST['clicks']
        : (float)($existing['cakes'] ?? 0);

    $currency = isset($_POST['currency'])
        ? (float)$_POST['currency']
        : (float)($existing['currency'] ?? 0);

    $multiplier = isset($_POST['multiplier'])
        ? (float)$_POST['multiplier']
        : (float)($existing['multiplier'] ?? 1);

    $clickPower = isset($_POST['clickPower'])
        ? (float)$_POST['clickPower']
        : (float)($existing['clickPower'] ?? 1);

    $cps = isset($_POST['cps'])
        ? (float)$_POST['cps']
        : (float)($existing['cps'] ?? 0);

    $bonus = isset($_POST['bonus'])
        ? (float)$_POST['bonus']
        : (float)($existing['bonus'] ?? 0);

    $prestigePoints = isset($_POST['prestige_points'])
        ? (int)$_POST['prestige_points']
        : (int)($existing['prestige_points'] ?? 0);

    $prestigeMultiplier = isset($_POST['prestige_multiplier'])
        ? (float)$_POST['prestige_multiplier']
        : (float)($existing['prestige_multiplier'] ?? 1);

    $prestigeLevel = isset($_POST['prestige_level'])
        ? (int)$_POST['prestige_level']
        : (int)($existing['prestige_level'] ?? 0);

    $upsert = $pdo->prepare("
        INSERT INTO player_save (
            id,
            cakes,
            currency,
            multiplier,
            clickPower,
            cps,
            bonus,
            prestige_points,
            prestige_multiplier,
            prestige_level
        )
        VALUES (
            :user_id,
            :clicks,
            :currency,
            :multiplier,
            :clickPower,
            :cps,
            :bonus,
            :prestige_points,
            :prestige_multiplier,
            :prestige_level
        )
        ON CONFLICT(id) DO UPDATE SET
            cakes = excluded.cakes,
            currency = excluded.currency,
            multiplier = excluded.multiplier,
            clickPower = excluded.clickPower,
            cps = excluded.cps,
            bonus = excluded.bonus,
            prestige_points = excluded.prestige_points,
            prestige_multiplier = excluded.prestige_multiplier,
            prestige_level = excluded.prestige_level
    ");

    $upsert->execute([
        ':user_id' => $user_id,
        ':clicks' => $clicks,
        ':currency' => $currency,
        ':multiplier' => $multiplier,
        ':clickPower' => $clickPower,
        ':cps' => $cps,
        ':bonus' => $bonus,
        ':prestige_points' => $prestigePoints,
        ':prestige_multiplier' => $prestigeMultiplier,
        ':prestige_level' => $prestigeLevel
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Game saved successfully'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>