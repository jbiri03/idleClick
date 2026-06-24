<?php

// LOAD PHP FILE
require_once __DIR__ . '/config.php';

// DEFINE VARIABLE THAT STORES DATA SOURCE NAME
$dsn = "sqlite:$db";

// VARIABLES TO CONNECT TO TABLE
$table_name = 'users';
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];

//TRY CATCH
try {
    //CONNECT TO DB
    $pdo = new \PDO($dsn);
    echo 'Connected to the SQLite database successfully!' ;

    //INSERT TABLE DATA
    $sql = "INSERT INTO $table_name (email, username, password)
    VALUES ('$email', '$username', '$password')";
    $stmt = $pdo->prepare($sql);

    //EXECUTE QUERY
    $stmt->execute();

} catch (\PDOException $e) {
    echo $e->getMessage();
}


?>