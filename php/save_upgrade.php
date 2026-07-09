<?php
session_start();
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id'])) {
    exit("Not logged in");
}

$user_id = $_SESSION['user_id'];
$upgrade_name = $_POST['upgrade_name'] ?? '';

try {
    $pdo = new PDO("sqlite:$db");

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

    echo "Upgrade saved";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
