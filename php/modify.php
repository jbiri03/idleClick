<?php
require_once __DIR__ . '/config.php';

// DEFINE VARIABLE THAT STORES DATA SOURCE NAME
$dsn = "sqlite:$db";
$errMsg = "";

if(isset($_SESSION['user_id'])) {
    //CONNECT TO DATABASE
    try {
        //CONNECT TO DB
        $pdo = new \PDO($dsn);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            //MODIFY USERNAME
            $newUsername = strtolower(trim($_POST['newUsername']));
            $confirmUsername = strtolower(trim($_POST['confirmUsername']));

            if($newUsername === '' && $confirmUsername === ''){
                //do nothing
            }
            elseif($newUsername === '' || $confirmUsername === '') {
                $errMsg = "Please fill in both fields.";
            }
            elseif ($newUsername === $confirmUsername){
                //USERNAME REQUIREMENTS
                if(strlen($newUsername) < 5){
                    //NO SPECIAL CHARACTERS
                    $errMsg = "Username must be at least 5 characters.";
                }
                
                elseif(strlen($newUsername) > 20){
                    $errMsg = "Username must be less than 20 characters.";
                }
               
                elseif(!preg_match('/^[a-zA-Z][a-zA-Z0-9_]{4,19}$/i', $newUsername)){
                    $errMsg = "Invalid username. No special characters allowed.";
                }


                else{
                    
                    $checkSql = "SELECT id, username FROM users WHERE username = :username LIMIT 1";
                    $checkStmt = $pdo->prepare($checkSql);
                    $checkStmt->execute([':username' => $newUsername]);

                    $existingUser = $checkStmt->fetch(PDO::FETCH_ASSOC);

                    if($existingUser){
                        $errMsg = "Username taken.";
                    }

                    else{
                        $stmt = $pdo->prepare("UPDATE users SET username = :username WHERE id = :user_id");
                        $stmt->bindValue(':username', $newUsername, \PDO::PARAM_STR);
                        $stmt->bindValue(':user_id', $_SESSION['user_id'], \PDO::PARAM_INT);
                        $stmt->execute();

                        $_SESSION['username'] = $newUsername;
                        $usernameSuccessMsg = 'Username successfully updated.';
                    }
                }

            }
            else{
                $errMsg = "Usernames do not match.";
            }

            //MODIFY EMAIL
            $newEmail = strtolower(trim($_POST['newEmail']));
            $confirmEmail = strtolower(trim($_POST['confirmEmail']));

            if($newEmail === '' && $confirmEmail === ''){
                //do nothing
            }
            elseif($newEmail === '' || $confirmEmail === '') {
                $emailErr = "Please fill in both fields.";
            }
            elseif ($newEmail === $confirmEmail){
                if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)){
                    $emailErr = "Invalid email format.";

                }
                else{
                    $checkSql = "SELECT id, email FROM users WHERE email = :email AND id != :user_id LIMIT 1";
                    $checkStmt = $pdo->prepare($checkSql);
                    $checkStmt->execute([':email' => $newEmail]);

                    $existingUser = $checkStmt->fetch(PDO::FETCH_ASSOC);

                    if($existingUser){
                        $emailErr = "Email already registered.";
                    }
                    else{
                        $stmt = $pdo->prepare("UPDATE users SET email = :email WHERE id = :user_id");
                        $stmt->bindValue(':email', $newEmail, \PDO::PARAM_STR);
                        $stmt->bindValue(':user_id', $_SESSION['user_id'], \PDO::PARAM_INT);
                        $stmt->execute();

                        $emailSuccessMsg = 'Email successfully updated.';
                    }
                }
            }
            else{
                $emailErr = "Emails do not match.";
            }

            //CHANGE PASSWORD
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];

            if($newPassword === '' && $confirmPassword === ''){
                //do nothing
            }
            elseif($newPassword === '' || $confirmPassword === '') {
                $passwordErr = "Please fill in both fields.";
            }
            elseif ($newPassword === $confirmPassword){
                if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $newPassword)){
                    $passwordErr = "Password must be at least 8 characters and include an uppercase letter, a lowercase letter, and a number.";
                }
                else{
                    //HASH PASSWORD
                    $password = password_hash($newPassword, PASSWORD_DEFAULT);

                    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :user_id");
                    $stmt->bindValue(':password', $password, \PDO::PARAM_STR);
                    $stmt->bindValue(':user_id', $_SESSION['user_id'], \PDO::PARAM_INT);
                    $stmt->execute();

                    $passwordSuccessMsg = 'Password successfully updated.';

                }

            }
            else{
                $passwordErr = "Passwords do not match.";
            }

            
        }


    } catch (\PDOException $e) {

    }
}
?>