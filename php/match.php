<?php
session_start();
require_once __DIR__ . '/config.php';

// DEFINE VARIABLE THAT STORES DATA SOURCE NAME
$dsn = "sqlite:$db";
$errMsg = "";

//CONNECT TO DATABASE
try {
    //CONNECT TO DB
    $pdo = new \PDO($dsn);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = strtolower(trim($_POST['username']));
        $password = $_POST['password'];

        if ($username && $password) {
            // Fetch user by username
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindValue(':username', $username, \PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            // Verify password
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                echo "✅ Login successful! Welcome, " . htmlspecialchars($username);
                header("Location: ../index.html");
                exit;
            } else {
                $errMsg = "❌ Invalid username or password.";
            }
        } else {
            $errMsg = "❌ Please fill in all fields.";
        }
    }


} catch (\PDOException $e) {

}
?>