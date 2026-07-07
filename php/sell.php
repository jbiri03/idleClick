<?php
session_start();
require_once __DIR__ . '/config.php';

try {
    $dsn = "sqlite:$db";
    $pdo = new \PDO($dsn);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $currency = $_POST['currency'];
        $newCakeCount = $_POST['newCakeCount'] ?? 0; // Get the new cake count from the POST data
        echo "Currency updated successfully. Amount: " . htmlspecialchars($currency);
        echo " Cake count received: " . htmlspecialchars($newCakeCount);
        $stmt = $pdo->prepare("UPDATE player_save SET cakes = :cakeCount, currency = :currency WHERE id = :user_id");
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'] ?? null,
            ':cakeCount' => $newCakeCount,
            ':currency' => $currency,
        ]);
    }
    else {
        echo "No data received.";
    }

} catch (\PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>