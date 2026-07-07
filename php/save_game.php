<?php
session_start();
require_once __DIR__ . '/config.php';

try {
    $dsn = "sqlite:$db";
    $pdo = new \PDO($dsn);


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $clicks = $_POST['clicks'];
        echo "Game saved successfully. Cakes: " . htmlspecialchars($clicks);
        $stmt = $pdo->prepare("UPDATE player_save SET cakes = :clicks WHERE id = :user_id");
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