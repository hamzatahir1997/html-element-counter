<?php

// Database configuration
$host = "localhost";
$user = "root"; // Adjust to your database user
$password = ""; // Adjust to your database password
$dbName = "element_counter";

// Create a new connection
$connection = new mysqli($host, $user, $password);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Create database
$sql = "CREATE DATABASE $dbName";
if ($connection->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $connection->error . "<br>";
}

// Connect to the created database
$connection = new mysqli($host, $user, $password, $dbName);

// Create tables
$commands = [
    "CREATE TABLE domain (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL UNIQUE
    )",

    "CREATE TABLE url (
        id INT AUTO_INCREMENT PRIMARY KEY,
        domain_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        UNIQUE(domain_id, name),
        FOREIGN KEY (domain_id) REFERENCES domain(id)
    )",

    "CREATE TABLE element (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL UNIQUE
    )",

    "CREATE TABLE request (
        id INT AUTO_INCREMENT PRIMARY KEY,
        domain_id INT NOT NULL,
        url_id INT NOT NULL,
        element_id INT NOT NULL,
        count INT NOT NULL,
        duration INT NOT NULL,
        time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (domain_id) REFERENCES domain(id),
        FOREIGN KEY (url_id) REFERENCES url(id),
        FOREIGN KEY (element_id) REFERENCES element(id)
    )"
];

foreach ($commands as $command) {
    if ($connection->query($command) === TRUE) {
        echo "Table created successfully<br>";
    } else {
        echo "Error creating table: " . $connection->error . "<br>";
    }
}

$connection->close();

?>

