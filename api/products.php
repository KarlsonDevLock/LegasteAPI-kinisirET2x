<?php

header("Content-Type: application/json");

require_once '../config/database.php';


function respond(int $statusCode, string $message, $data = null): void {
    http_response_code($statusCode);
    echo json_encode([
        "message" => $message,
        "data"    => $data
    ]);
    exit();
}


function getRequestBody(): object {
    return json_decode(file_get_contents("php://input")) ?? (object)[];
}

─────────────────────────────────────────────

$db = connectDB();


if (!$db) {
    respond(500, "Could not connect to the database.");
}

$method = $_SERVER['REQUEST_METHOD'];


if ($method === 'GET') {

    
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        $stmt = $db->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if (!$product) {
            respond(404, "Product not found.");
        }

        respond(200, "Product fetched.", $product);
    }

   
    $stmt = $db->query("SELECT * FROM products");
    $products = $stmt->fetchAll();

    respond(200, "Products fetched.", $products);
}


if ($method === 'POST') {
    $body = getRequestBody();

    
    if (empty($body->product) || !isset($body->price)) {
        respond(400, "Fields 'product' and 'price' are required.");
    }

    $stmt = $db->prepare("INSERT INTO products (product, price) VALUES (?, ?)");
    $stmt->execute([
        trim($body->product),
        (float) $body->price
    ]);

    
    $newId = $db->lastInsertId();
    $stmt  = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$newId]);
    $newProduct = $stmt->fetch();

    respond(201, "Product created.", $newProduct);
}



if ($method === 'PUT') {
    $body = getRequestBody();

    if (empty($body->id) || empty($body->product) || !isset($body->price)) {
        respond(400, "Fields 'id', 'product', and 'price' are required.");
    }

    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([(int) $body->id]);

    if (!$stmt->fetch()) {
        respond(404, "Product not found.");
    }

    $stmt = $db->prepare("UPDATE products SET product = ?, price = ? WHERE id = ?");
    $stmt->execute([
        trim($body->product),
        (float) $body->price,
        (int) $body->id
    ]);

  
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([(int) $body->id]);
    $updatedProduct = $stmt->fetch();

    respond(200, "Product updated.", $updatedProduct);
}


if ($method === 'DELETE') {
    $body = getRequestBody();

    if (empty($body->id)) {
        respond(400, "Field 'id' is required.");
    }


    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([(int) $body->id]);
    $product = $stmt->fetch();

    if (!$product) {
        respond(404, "Product not found.");
    }

    $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([(int) $body->id]);


    respond(200, "Product deleted.", $product);
}


respond(405, "Method not allowed.");