<?php
session_start();

// INITIALIZE VARIABLES
// ERROR MSGS
$errMsg = '';
$emailErr = '';
$passwordErr = '';

// SUCCESS MSGS
$passwordSuccessMsg = '';
$emailSuccessMsg = '';
$usernameSuccessMsg = '';

// PLAYER DATA
$current_username = '';
$current_email = '';
$current_cakes = 0;
$current_currency = 0;

    require_once __DIR__ . '/php/config.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        require __DIR__ . '/php/modify.php';
    }

    $clicks = isset($_POST['clicks']) ? $_POST['clicks'] : 0;
    $currency = isset($_POST['currency']) ? $_POST['currency'] : 0;

    if(isset($_SESSION['user_id'])) {
        try {
            $dsn = "sqlite:$db";
            $pdo = new \PDO($dsn);

            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // LOAD FULL GAME STATE
            $stmt = $pdo->prepare("
                SELECT cakes, currency, multiplier, clickPower, cps, bonus
                FROM player_save
                WHERE id = :user_id
            ");

            $stmt->execute([':user_id' => $_SESSION['user_id']]);
            $player_data = $stmt->fetch(\PDO::FETCH_ASSOC);

            if($player_data) {
                $current_cakes = (int)$player_data['cakes'];
                $current_currency = (int)$player_data['currency'];
                $current_multiplier = (int)$player_data['multiplier'];
                $current_clickPower = (int)$player_data['clickPower'];
                $current_cps = (int)$player_data['cps'];
                $current_bonus = (int)$player_data['bonus'];
            }
            else {
                $current_cakes = 0;
                $current_currency = 0;
                $current_multiplier = 1;
                $current_clickPower = 1;
                $current_cps = 0;
                $current_bonus = 0;
            }

            $userStmt = $pdo->prepare("SELECT email, username FROM users WHERE id = :user_id");
            $userStmt->execute([':user_id' => $_SESSION['user_id']]);
            $user_data = $userStmt->fetch(\PDO::FETCH_ASSOC);

            if($user_data) {
                $current_username = htmlspecialchars($user_data['username']);
                $current_email = htmlspecialchars($user_data['email']);
            } else {
                echo "User data not found.";
            }

        } catch (\PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
        }
    } else {
        header("Location: index.php");
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
        <!-- NAVIGATION OF BASE FEATURES-->
        <div class="column1"> 
            <div id="nav">
                <ul>
                    <li><a href="index.php"><button>Home</button></a></li>
                    <li><a href="sell_cakes.php"><button>Sell</button></a></li>
                    <li><a href="upgrades.php"><button>Upgrades</button></a></li>
                    <li><a href="prestige.php"><button>Prestige</button></a></li>
                    <li><a href="settings.php"><button id = "settingsButton">Settings</button></a></li>
                    <!-- <li><a href="pets.html"><button>Pets</button></a></li>
                    <li><a href="inventory.html"><button>Inventory</button></a> </li> -->
                </ul>
            </div>
        </div>

        <!-- ADDITIONAL FEATURES TO BE ADDED-->
        <!-- <a href="shop.html"><button>Shop</button></a> -->

        <div class="column2">
             <div id="settingsSect">
                <h1>Settings</h1>

                <!-- ACCOUNT DETAILS -->
                <h2>Account Details</h2>
                <p>Current Username: <?php echo $current_username ?></p>
                <p>Current Email: <?php echo $current_email ?></p>

                <!-- MODIFY ACCOUNT -->
                <form method="POST">
                    <h3>Change Username</h3>
                        <label for="newUsername">New Username:</label>
                        <input type="text" id="newUsername" name="newUsername">
                        <label for="confirmUsername">Confirm Username:</label>
                        <input type="text" id="confirmUsername" name="confirmUsername"><br>
                        <p class = "error"><?php echo $errMsg ?></p>
                        <p class = "success"><?php echo $usernameSuccessMsg ?></p>

                    <h3>Change Email</h3>
                        <label for="newEmail">New Email:</label>
                        <input type="text" id="newEmail" name="newEmail">
                        <label for="confirmEmail">Confirm Email:</label>
                        <input type="text" id="confirmEmail" name="confirmEmail"><br>
                        <p class = "error"><?php echo $emailErr ?></p>
                        <p class = "success"><?php echo $emailSuccessMsg ?></p>

                    <h3>Change Password</h3>
                        <label for="newPassword">New Password:</label>
                        <input type="password" id="newPassword" name="newPassword">
                        <label for="confirmPassword">Confirm Password:</label>
                        <input type="password" id="confirmPassword" name="confirmPassword"><br>
                        <p class = "error"><?php echo $passwordErr ?></p>
                        <p class = "success"><?php echo $passwordSuccessMsg ?></p>
                    <button>Apply Changes</button>
                </form>
             </div>
        </div>


        <!-- GAME STATS -->
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
                        <li>Click Power: <?php echo $current_clickPower ?></li>
                        <li>Multiplier Bonus: <?php echo $current_multiplier?></li>
                        <li>Total Cakes Per Click: <?php echo $current_clickPower * $current_multiplier?></li>
                        <!-- <li>Cake Type: <span id="cakeDetails"></span></li> -->
                    </ul>

                <h2>PROGRESS</h2>
                    <ul>
                        <li>Prestige Multiplier: {}</li>
                        <li>Current Prestige Level: {} </li>
                    </ul>

                <button>SHARE</button>
            </div>
        </div>

        <!-- SCRIPTS -->
        <!-- CAKE DETAILS -->
        <!-- <script src="logic/Cake.js"></script> -->


    </body>
</html>