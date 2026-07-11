<?php
session_start();
require_once __DIR__ . '/php/config.php';

// INITIALIZE VARIABLES
$errMsg = '';
$emailErr = '';
$passwordErr = '';
$accountErr = '';

$passwordSuccessMsg = '';
$emailSuccessMsg = '';
$usernameSuccessMsg = '';

$current_username = '';
$current_email = '';
$current_cakes = 0;
$current_currency = 0;
$current_multiplier = 1;
$current_clickPower = 1;
$current_cps = 0;
$current_bonus = 0;
$current_prestige_multiplier = 1;
$current_prestige_points = 0;
$current_prestige_level = 0;

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

try {
    $dsn = "sqlite:$db";
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $userId = (int)$_SESSION['user_id'];

    // HANDLE POST REQUESTS
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // DELETE ACCOUNT
        if (isset($_POST['deleteAccount'])) {
            $deleteConfirm = trim($_POST['deleteConfirm'] ?? '');

            if ($deleteConfirm !== 'DELETE') {
                $accountErr = "You must type DELETE exactly to confirm account deletion.";
            } else {
                $pdo->beginTransaction();

                try {
                    $stmt1 = $pdo->prepare("DELETE FROM player_upgrades WHERE user_id = :user_id");
                    $stmt1->execute([':user_id' => $userId]);

                    $stmt2 = $pdo->prepare("DELETE FROM player_save WHERE id = :user_id");
                    $stmt2->execute([':user_id' => $userId]);

                    $stmt3 = $pdo->prepare("DELETE FROM users WHERE id = :user_id");
                    $stmt3->execute([':user_id' => $userId]);

                    $pdo->commit();

                    $_SESSION = [];
                    if (ini_get("session.use_cookies")) {
                        $params = session_get_cookie_params();
                        setcookie(
                            session_name(),
                            '',
                            time() - 42000,
                            $params["path"],
                            $params["domain"],
                            $params["secure"],
                            $params["httponly"]
                        );
                    }

                    session_destroy();

                    header("Location: index.php");
                    exit;
                } catch (PDOException $e) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $accountErr = "Failed to delete account. Please try again.";
                }
            }
        }

        // APPLY ACCOUNT CHANGES
        elseif (isset($_POST['applyChanges'])) {
            require __DIR__ . '/php/modify.php';
        }
    }

    // LOAD FULL GAME STATE
    $stmt = $pdo->prepare("
        SELECT cakes, currency, multiplier, clickPower, cps, bonus, prestige_multiplier, prestige_points, prestige_level
        FROM player_save
        WHERE id = :user_id
    ");
    $stmt->execute([':user_id' => $userId]);
    $player_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($player_data) {
        $current_cakes = (int)$player_data['cakes'];
        $current_currency = (int)$player_data['currency'];
        $current_multiplier = (int)$player_data['multiplier'];
        $current_clickPower = (int)$player_data['clickPower'];
        $current_cps = (int)$player_data['cps'];
        $current_bonus = (int)$player_data['bonus'];
        $current_prestige_multiplier = (float)($player_data['prestige_multiplier'] ?? 1);
        $current_prestige_points = (int)($player_data['prestige_points'] ?? 0);
        $current_prestige_level = (int)($player_data['prestige_level'] ?? 0);
    }

    // LOAD USER DATA
    $userStmt = $pdo->prepare("SELECT email, username FROM users WHERE id = :user_id");
    $userStmt->execute([':user_id' => $userId]);
    $user_data = $userStmt->fetch(PDO::FETCH_ASSOC);

    if ($user_data) {
        $current_username = htmlspecialchars($user_data['username']);
        $current_email = htmlspecialchars($user_data['email']);
    } else {
        $errMsg = "User data not found.";
    }

} catch (PDOException $e) {
    $errMsg = "Database connection failed.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cake Idle Clicker</title>
    <link rel="stylesheet" href="Styles/index.css">
</head>
<body>
    <div class="column1"> 
        <div id="nav">
            <ul>
                <li><a href="index.php"><button type="button">Home</button></a></li>
                <li><a href="sell_cakes.php"><button type="button">Sell</button></a></li>
                <li><a href="upgrades.php"><button type="button">Upgrades</button></a></li>
                <li><a href="prestige.php"><button type="button">Prestige</button></a></li>
                <li><a href="leaderboard.php"><button type="button" id="leaderboard">Leaderboard</button></a></li>
                <li><a href="settings.php"><button type="button" id="settingsButton">Settings</button></a></li>
            </ul>
        </div>
    </div>

    <div class="column2">
        <div id="settingsSect">
            <h1>Settings</h1>

            <h2>Account Details</h2>
            <p>Current Username: <?php echo $current_username; ?></p>
            <p>Current Email: <?php echo $current_email; ?></p>

            <form method="POST">
                <h3>Change Username</h3>
                <label for="newUsername">New Username:</label>
                <input type="text" id="newUsername" name="newUsername">

                <label for="confirmUsername">Confirm Username:</label>
                <input type="text" id="confirmUsername" name="confirmUsername"><br>
                <p class="error"><?php echo $errMsg; ?></p>
                <p class="success"><?php echo $usernameSuccessMsg; ?></p>

                <h3>Change Email</h3>
                <label for="newEmail">New Email:</label>
                <input type="text" id="newEmail" name="newEmail">

                <label for="confirmEmail">Confirm Email:</label>
                <input type="text" id="confirmEmail" name="confirmEmail"><br>
                <p class="error"><?php echo $emailErr; ?></p>
                <p class="success"><?php echo $emailSuccessMsg; ?></p>

                <h3>Change Password</h3>
                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword">

                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword"><br>
                <p class="error"><?php echo $passwordErr; ?></p>
                <p class="success"><?php echo $passwordSuccessMsg; ?></p>

                <button type="submit" name="applyChanges" value="1">Apply Changes</button>
            </form>

            <hr>

            <h2>Danger Zone</h2>
            <p>Deleting your account will permanently remove your login and saved game data.</p>

            <form method="POST" onsubmit="return confirm('Are you sure you want to permanently delete your account? This cannot be undone.');">
                <label for="deleteConfirm">Type DELETE to confirm:</label>
                <input type="text" id="deleteConfirm" name="deleteConfirm" required>
                <button type="submit" name="deleteAccount" value="1" id="delete-button">Delete Account</button>
            </form>

            <p class="error"><?php echo $accountErr; ?></p>
        </div>
    </div>

    <div class="column3">
        <div id="stats">
            <h1>STATISTICS</h1>

            <h2>BALANCES</h2>
            <ul>
                <li>Cakes: <span id="cakeStat"><?php echo $current_cakes; ?></span></li>
                <li>Cash: $<span id="money"><?php echo $current_currency; ?></span></li>
            </ul>

            <h2>PRODUCTION</h2>
            <ul>
                <li>Auto-Bake Rate: <?php echo $current_cps; ?></li>
                <li>Click Power: <?php echo $current_clickPower; ?></li>
                <li>Multiplier Bonus: <?php echo $current_multiplier; ?></li>
                <li>Total Cakes Per Click: <?php echo $current_clickPower * $current_multiplier * $current_prestige_multiplier; ?></li>
            </ul>

            <h2>PROGRESS</h2>
            <ul>
                <li>Prestige Multiplier: x<?php echo $current_prestige_multiplier; ?></li>
                <li>Current Prestige Level: <?php echo $current_prestige_level; ?></li>
            </ul>
        </div>
    </div>

    <span id="multiplierStat" style="display:none;"><?php echo $current_multiplier; ?></span>
    <span id="clickPowerStat" style="display:none;"><?php echo $current_clickPower; ?></span>
    <span id="cpsStat" style="display:none;"><?php echo $current_cps; ?></span>
    <span id="bonusStat" style="display:none;"><?php echo $current_bonus; ?></span>
</body>
</html>