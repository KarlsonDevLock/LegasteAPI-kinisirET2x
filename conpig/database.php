<?php
//database dtails
$dbhost = "localhost:8080";
$dbname = "comshop";
$username = "root";
$password = ""; //empty sa ni.. kay ambot

$dsn = "mysql:dbhost=$dbhost;dbname=$dbname;charset=utf8mb4";

//pdo object

$pdo = new PDO($dsn, $username, $password);
//this file is for connecting to the database, and we will include this file in our products.php file to use the $pdo object for database operations. 
?>