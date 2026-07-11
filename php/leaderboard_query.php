<?php
require_once __DIR__ . '/config.php';

if (!isset($db) || empty($db)) {
    echo json_encode(["error" => "Database path not defined in config.php"]);
    exit;
}

header("Content-Type: application/json; charset=utf-8");

try {
    $pdo = new PDO("sqlite:$db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $type = $_GET['type'] ?? 'currency';

    switch ($type) {
        case 'cakes':
            $orderBy = 'ps.cakes DESC';
            break;
        case 'prestige':
            $orderBy = 'ps.prestige_level DESC';
            break;
        default:
            $orderBy = 'ps.currency DESC';
            break;
    }

    $stmt = $pdo->query("
        SELECT
            u.username,
            ps.cakes,
            ps.currency,
            ps.prestige_level
        FROM player_save AS ps
        INNER JOIN users AS u ON ps.id = u.id
        ORDER BY $orderBy
        LIMIT 10
    ");

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>