<?php

// LOAD PHP FILE
require_once __DIR__ . '/config.php';

// DEFINE VARIABLE THAT STORES DATA SOURCE NAME
$dsn = "sqlite:$db";

// VARIABLES TO CONNECT TO TABLE
$email = $_POST['email'];
$username = $_POST['username'];
$table_name = 'users';

//TRY CATCH
try {
    //CONNECT TO DB
    $pdo = new \PDO($dsn);
    echo 'Connected to the SQLite database successfully!' ;

    //INSERT TABLE DATA
    $sql = "INSERT INTO $table_name (email, username)
    VALUES ('$email', '$username')";
    $stmt = $pdo->prepare($sql);

    //EXECUTE QUERY
    $stmt->execute();

} catch (\PDOException $e) {
    echo $e->getMessage();
}


?>