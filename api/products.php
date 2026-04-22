<?php

require_once "database.php";

//get products from databasees
$sql = "SELECT * FROM products";
$pdoStatement = $pdo->prepare($sql);

$pdoStatement->execute();
$products = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

//output products as json
echo json_encode($products);
exit;