<?php

// LOAD PHP FILE
require_once 'config.php';

// DEFINE VARIABLE THAT STORES DATA SOURCE NAME
$dsn = "sqlite:$db";

try {
    $pdo = new \PDO($dsn);
    echo 'Connected to the SQLite database successfully!';
} catch (\PDOException $e) {
    echo $e->getMessage();
}

?>