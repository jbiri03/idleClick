<?php
    session_start();
    require_once __DIR__ . '/php/config.php';

    //INITIALIZE VARIABLES
    $current_cakes = 0;
    $current_currency = 0;

    $session_id = session_id();


    $clicks = isset($_POST['clicks']) ? $_POST['clicks'] : 0;
    $currency = isset($_POST['currency']) ? $_POST['currency'] : 0;

    if(isset($_SESSION['user_id'])) {
        try {
            $dsn = "sqlite:$db";
            $pdo = new \PDO($dsn);

            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("
                SELECT cakes, currency, multiplier, clickPower, cps, bonus, prestige_points, prestige_multiplier, prestige_level
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

                $current_prestige_points     = (int)$player_data['prestige_points'];
                $current_prestige_multiplier = (float)$player_data['prestige_multiplier'];
                $current_prestige_level      = (int)$player_data['prestige_level'];


                
            } else {
                $current_cakes = 0;
                $current_currency = 0;
                $current_multiplier = 1;
                $current_clickPower = 1;
                $current_cps = 0;
                $current_bonus = 0;
                $current_prestige_points     = 0;
                $current_prestige_multiplier = 1.0;
                $current_prestige_level      = 0;

            }

        } catch (\PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
        }
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
                    <li><a href="index.php"><button id = "homeButton">Home</button></a></li>
                    <li><a href="sell_cakes.php"><button id = "sellButton">Sell</button></a></li>
                    <li><a href="upgrades.php"><button id = "upgradesButton">Upgrades</button></a></li>
                    <li><a href="prestige.php"><button id = "prestigeButton">Prestige</button></a></li>
                    <li><a href="leaderboard.php"><button id = "leaderboard">Leaderboard</button></a></li>
                    <li><a href="settings.php"><button id = "settingsButton">Settings</button></a></li>
                </ul>
            </div>
        </div>

        <div class="column2">
            <!-- SIGN IN -->

            <div id="topBar">
                <a href="php/login.php"><button id = "loginButton">Login or Register</button></a>
                <!-- <button id = "dailyButton">Daily Bonus</button> -->
                <button id="saveButton">Save Game</button>
                <button id = "logOut">Log Out</button>
                <?php 
                    if (isset($_SESSION['username'])): 
                ?>
                <div class="welcome-message">
                    <?php 
                        echo 'WELCOME, ' . strtoupper($_SESSION['username']) . '!';
                        //ID TESTING
                        echo ' PLAYER ID: ' . $_SESSION['user_id'];
                    ?>
                </div>
                    <?php else: ?>
                        <div class = "login-message">
                            <p>&#8593 LOG IN OR CREATE ACCOUNT TO PLAY</p>
                        </div>
                
                    <?php endif; ?>
            </div>

            <!-- CURRENCY DISPLAY -->
            <div id="currency">   
                <ul>        
                    <li>Cakes: <span id="clickCount1" data-cake><?php echo isset($current_cakes) ? $current_cakes : 0; ?></span></li>
                    <li>Currency: $<span id="cash"><?php echo isset($current_currency) ? $current_currency : 0; ?></span></li>
                </ul>
            </div>

            <!-- CLICKER IMAGE -->
            <div id="autoBakeIndicator" style="display:none;">🎂 Auto‑Baking...</div>
            <div id="cake">
                <button id="clicker"><img src="Images/Tiramisu.png" alt="Cake"></button>
            </div>


        </div>

 
        <!-- GAME STATS -->
        <div class="column3">
            <div id="stats">
                <h1>STATISTICS</h1>

                <h2>BALANCES</h2>
                    <ul>
                        <li>Cakes: <span id="clickCount2" data-cake><?php echo isset($current_cakes) ? $current_cakes : 0; ?></span></li>
                        <li>Cash: $<span id="money"><?php echo isset($current_currency) ? $current_currency : 0; ?></span></li>
                    </ul>

                <h2>PRODUCTION</h2>
                    <ul>
                        <li>Auto-Bake Rate: <?php echo isset($current_cps) ? $current_cps : 0; ?></li>
                        <li>Click Power: <?php echo isset($current_clickPower) ? $current_clickPower : 0;?></li>
                        <li>Multiplier Bonus: <?php echo isset($current_multiplier) ? $current_multiplier : 0; ?></li>
                        <li>Total Cakes Per Click: <?php echo isset($current_clickPower) ? $current_clickPower * $current_multiplier * $current_prestige_multiplier : 0;?></li>
                        <!-- <li>Cake Type: <span id="cakeDetails"></span></li> -->
                    </ul>

                <h2>PROGRESS</h2>
                    <ul>
                        <li>Prestige Multiplier: x<?php echo $current_prestige_multiplier; ?></li>
                        <li>Current Prestige Level: <?php echo $current_prestige_level; ?></li>
                    </ul>

                <!-- HIDDEN ELEMENTS -->
                <div id="upgradeData" style="display:none;"
                    data-multiplier="<?php echo $current_multiplier; ?>"
                    data-clickpower="<?php echo $current_clickPower; ?>"
                    data-cps="<?php echo $current_cps; ?>"
                    data-bonus="<?php echo $current_bonus; ?>">
                </div>
                <span id="prestigeMultiplierStat" style="display:none;"><?php echo $current_prestige_multiplier; ?></span>
                <span id="prestigePointsStat" style="display:none;"><?php echo $current_prestige_points; ?></span>



        </div>

        <!-- SCRIPTS -->
        <!-- CLICK COUNTER -->
        <!-- GAME STATE + UPGRADE SYSTEM -->
        <script type="module" src="logic/game.js"></script>

        <!-- CLICKING LOGIC-->
        <script type="module" src="logic/clicker.js"></script>


        <!-- CAKE DETAILS -->
         <!-- <script src="logic/Cake.js"> -->
            <!-- // const sessionId = ""; // Pass the session ID to the JavaScript file -->
         <!-- </script> -->

         <!-- LOG IN REQUIREMENT -->
          <script src="logic/session_check/check_session.js"></script>
<!-- 
          DESTROY SESSION -->
          <script>
            const logOut = document.getElementById('logOut');
                logOut.addEventListener('click', function() {
                    window.location.href = 'php/logout.php';
                });
          </script>
    </body>
</html>