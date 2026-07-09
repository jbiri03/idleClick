<?php
session_start();
require_once __DIR__ . '/config.php';

try {
    $dsn = "sqlite:$db";
    $pdo = new \PDO($dsn);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $clicks     = $_POST['clicks']     ?? 0;
        $currency   = $_POST['currency']   ?? 0;
        $multiplier = $_POST['multiplier'] ?? 1;
        $clickPower = $_POST['clickPower'] ?? 1;
        $cps        = $_POST['cps']        ?? 0;
        $bonus      = $_POST['bonus']      ?? 0;

        echo "Game saved successfully. Cakes: " . htmlspecialchars($clicks);

        $stmt = $pdo->prepare("
            UPDATE player_save 
            SET cakes      = :clicks,
                currency   = :currency,
                multiplier = :multiplier,
                clickPower = :clickPower,
                cps        = :cps,
                bonus      = :bonus
            WHERE id = :user_id
        ");

        $stmt->execute([
            ':user_id'    => $_SESSION['user_id'] ?? null,
            ':clicks'     => $clicks,
            ':currency'   => $currency,
            ':multiplier' => $multiplier,
            ':clickPower' => $clickPower,
            ':cps'        => $cps,
            ':bonus'      => $bonus
        ]);

        // If no row updated, insert new one
        if ($stmt->rowCount() === 0) {
            $insertStmt = $pdo->prepare("
                INSERT INTO player_save (id, cakes, currency, multiplier, clickPower, cps, bonus)
                VALUES (:user_id, :clicks, :currency, :multiplier, :clickPower, :cps, :bonus)
            ");

            $insertStmt->execute([
                ':user_id'    => $_SESSION['user_id'] ?? null,
                ':clicks'     => $clicks,
                ':currency'   => $currency,
                ':multiplier' => $multiplier,
                ':clickPower' => $clickPower,
                ':cps'        => $cps,
                ':bonus'      => $bonus
            ]);
        }

    } else {
        echo "No data received.";
    }

} catch (\PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>
