<?php

$host = "localhost";
$user = "root"; // Adjust to your database user
$password = ""; // Adjust to your database password
$dbName = "element_counter";

$connection = new mysqli($host, $user, $password, $dbName);
if ($connection->connect_error) {
    die("Database connection failed: " . $connection->connect_error);
}

?>
