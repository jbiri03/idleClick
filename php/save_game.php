<?php
session_start();
require_once __DIR__ . '/config.php';

try {
    $dsn = "sqlite:$db";
    $pdo = new \PDO($dsn);


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $clicks = $_POST['clicks'];
        echo "Received data: " . htmlspecialchars($clicks);
        $stmt = $pdo->prepare("INSERT OR REPLACE INTO player_save (id, cakes) VALUES (:user_id, :clicks)");
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'] ?? null,
            ':clicks' => $clicks,
        ]);
    }
    else {
        echo "No data received.";
    }

} catch (\PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>