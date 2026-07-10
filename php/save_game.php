<?php
session_start();
require_once __DIR__ . '/config.php';

try {
    $dsn = "sqlite:$db";
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // GAME VALUES
        $clicks     = $_POST['clicks']     ?? 0;
        $currency   = $_POST['currency']   ?? 0;
        $multiplier = $_POST['multiplier'] ?? 1;
        $clickPower = $_POST['clickPower'] ?? 1;
        $cps        = $_POST['cps']        ?? 0;
        $bonus      = $_POST['bonus']      ?? 0;

        // PRESTIGE VALUES
        $prestigePoints     = $_POST['prestige_points']     ?? 0;
        $prestigeMultiplier = $_POST['prestige_multiplier'] ?? 1;

        $user_id = $_SESSION['user_id'] ?? null;

        if (!$user_id) {
            echo "No user ID found.";
            exit();
        }

        // UPDATE EXISTING SAVE
        $stmt = $pdo->prepare("
            UPDATE player_save 
            SET cakes              = :clicks,
                currency           = :currency,
                multiplier         = :multiplier,
                clickPower         = :clickPower,
                cps                = :cps,
                bonus              = :bonus,
                prestige_points    = :prestige_points,
                prestige_multiplier = :prestige_multiplier
            WHERE id = :user_id
        ");

        $stmt->execute([
            ':user_id'            => $user_id,
            ':clicks'             => $clicks,
            ':currency'           => $currency,
            ':multiplier'         => $multiplier,
            ':clickPower'         => $clickPower,
            ':cps'                => $cps,
            ':bonus'              => $bonus,
            ':prestige_points'    => $prestigePoints,
            ':prestige_multiplier'=> $prestigeMultiplier
        ]);

        // INSERT IF NO ROW UPDATED
        if ($stmt->rowCount() === 0) {

            $insertStmt = $pdo->prepare("
                INSERT INTO player_save 
                (id, cakes, currency, multiplier, clickPower, cps, bonus, prestige_points, prestige_multiplier)
                VALUES 
                (:user_id, :clicks, :currency, :multiplier, :clickPower, :cps, :bonus, :prestige_points, :prestige_multiplier)
            ");

            $insertStmt->execute([
                ':user_id'            => $user_id,
                ':clicks'             => $clicks,
                ':currency'           => $currency,
                ':multiplier'         => $multiplier,
                ':clickPower'         => $clickPower,
                ':cps'                => $cps,
                ':bonus'              => $bonus,
                ':prestige_points'    => $prestigePoints,
                ':prestige_multiplier'=> $prestigeMultiplier
            ]);
        }

        echo "Game saved successfully.";

    } else {
        echo "No data received.";
    }

} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>
