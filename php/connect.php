<?php
//SESSION START
session_start();


// LOAD PHP FILE
require_once __DIR__ . '/config.php';

// DEFINE VARIABLE THAT STORES DATA SOURCE NAME
$dsn = "sqlite:$db";

// VARIABLES TO CONNECT TO TABLE
$table_name = 'users';
$email = strtolower(trim($_POST['email']));
$username = strtolower(trim($_POST['username']));
$password = $_POST['password'];
$confirm = $_POST['confirm'];

//ERROR MESSAGE VARIABLES
$emailErr = "";
$usernameErr = "";
$passwordErr = "";
$confirmErr = "";

//CONNECT TO DATABASE
// try {
//     $pdo = new \PDO($dsn);
// } 
// catch (\PDOException $e) {
//     echo "Connection failed: " . $e->getMessage();
// }


//VALIDATE AND INSERT
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //VALIDATE EMAILm,
    //EMAIL LEFT BLANK
    if (empty($email)) {
        $emailErr = "Email required";
    } 

    //EMAIL FORMAT
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $emailErr = "Invalid email format";
    }

    //VALIDATE USERNAME -- (5 - 20 chars, no special characters)
    if(!preg_match('/^[a-zA-Z][a-zA-Z0-9_]{4,19}$/i', $username)){
        //NO SPECIAL CHARACTERS
        $usernameErr = "Invalid username. No special characters allowed.";



        //LESS THAN 5 CHARACTERS
        if(strlen($username) < 5){
            $usernameErr = "Username must be at least 5 characters.";
        }

        //LESS THAN 20 CHARACTERS
        if(strlen($username) > 20){
            $usernameErr = "Username must be less than 20 characters.";
        }
        
        //USERNAME LEFT BLANK
        if (empty($username)) { 
            $usernameErr = "Username required";
        } 
    }

        //VALIDATE PASSWORD -- at least one lower case letter, at least one upper case letter, at least one digit, min 8 characters
    if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)){
        $passwordErr = "Invalid password";
            
            //PASSWORD LEFT BLANK
        if(empty($password)){
        $passwordErr = "Password required";
        }
    }

    //VALIDATE THAT CONFIRM PASSWORD MATCHES PASSWORD
    if($confirm != $password){
        $confirmErr = "Passwords do not match";
    }


    //CODE CLEANUP
    $noErrMsg = empty($emailErr) && empty($usernameErr) && empty($passwordErr) && empty($confirmErr);

    //IF ALL IS VALID !
    if ($noErrMsg) {
        try {
            //CONNECT TO DB
            $pdo = new \PDO($dsn);

            $checkSql = "SELECT id, username, email FROM users WHERE username = :username OR email = :email LIMIT 1";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([':username' => $username,':email' => $email]);

            $existingUser = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                if (strcasecmp($existingUser['username'], $username) === 0) {
                    $usernameErr = "Username already taken";
                }

                if (strcasecmp($existingUser['email'], $email) === 0) {
                    $emailErr = "Email already registered";
                }
            }
            else {
                //HASH PASSWORD
                $password = password_hash($password, PASSWORD_DEFAULT);

                //INSERT TABLE DATA
                $sql = "INSERT INTO $table_name (email, username, password) VALUES (:email, :username, :password)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':username', $username);
                $stmt->bindValue(':password', $password);

                //EXECUTE QUERY
                $stmt->execute();

                //RELOCATE AFTER SUCCESS!
                if(isset($_POST['button'])){
                    $_SESSION['success_message'] = 'Account created successfully. Please log in.';;
                    header("Location: /capstone/php/login.php");
                    exit();
                }
            }
        } 
        catch (\PDOException $e) {
            echo $e->getMessage();
        }
        }
    }
?>