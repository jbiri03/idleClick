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
$upgrade_name = trim($_POST['upgrade_name'] ?? '');

if ($upgrade_name === '') {
    echo json_encode([
        'success' => false,
        'error' => 'Missing upgrade name'
    ]);
    exit();
}

try {
    $pdo = new PDO("sqlite:$db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        INSERT INTO player_upgrades (user_id, upgrade_name, purchased)
        VALUES (:user_id, :upgrade_name, 1)
        ON CONFLICT(user_id, upgrade_name)
        DO UPDATE SET purchased = 1
    ");

    $stmt->execute([
        ':user_id' => $user_id,
        ':upgrade_name' => $upgrade_name
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Upgrade saved'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>