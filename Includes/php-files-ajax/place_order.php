<?php
include "../../connect.php";

// Get the raw POST data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validate the data
if (!isset($data['items']) || empty($data['items'])) {
    echo json_encode(['success' => false, 'message' => 'No items in the order']);
    exit;
}

try {
    // Start transaction
    $con->beginTransaction();

    // Insert the main order
    $stmt = $con->prepare("INSERT INTO placed_orders (order_time, client_id, delivery_address) 
                          VALUES (NOW(), :client_id, :address)");
    
    $clientId = isset($data['customer']['id']) ? $data['customer']['id'] : null;
    
    $stmt->execute([
        ':client_id' => $clientId,
        ':address' => $data['address']
    ]);
    
    $orderId = $con->lastInsertId();

    // Insert each item in the order
$stmt = $con->prepare("INSERT INTO in_order (order_id, menu_id, client_id, quantity, item_name) 
VALUES (:order_id, :menu_id, :client_id, :quantity, :item_name)");

foreach ($data['items'] as $item) {

$stmt->execute([
':order_id' => $orderId,
':menu_id' => $item['id'],
':client_id' => $clientId,
':quantity' => $item['quantity'],
':item_name' => isset($item['name']) ? $item['name'] : null  // Make sure we check if name exists
]);
}

    // If it's a new customer (not logged in), create a client record
    if (!isset($data['customer']['id'])) {
        $stmt = $con->prepare("INSERT INTO clients (client_name, client_phone, client_email, client_address) 
                              VALUES (:name, :phone, :email, :address)");
        
        $stmt->execute([
            ':name' => $data['customer']['name'],
            ':phone' => $data['customer']['phone'],
            ':email' => $data['customer']['email'],
            ':address' => $data['address']
        ]);
        
        $clientId = $con->lastInsertId();
    }

    //////////////////////////
    // Payment Table Update
    //////////////////////////

    // Calculate total amount securely from database
    $totalAmount = 0;

    $stmtPrice = $con->prepare("SELECT menu_price FROM menus WHERE menu_id = :menu_id");

    foreach ($data['items'] as $item) {
        $stmtPrice->execute([':menu_id' => $item['id']]);
        $menu = $stmtPrice->fetch(PDO::FETCH_ASSOC);
        if ($menu) {
            $totalAmount += $menu['menu_price'] * $item['quantity'];
        }
    }

    // Insert into payment table
    $paymentStmt = $con->prepare("INSERT INTO payment (time, amount, order_id) VALUES (NOW(), :amount, :order_id)");
    $paymentStmt->execute([
        ':amount' => $totalAmount,
        ':order_id' => $orderId
    ]);

    //////////////////////////

    // Commit the transaction
    $con->commit();

    echo json_encode(['success' => true, 'order_id' => $orderId]);
    
} catch (PDOException $e) {
    // Rollback the transaction on error
    $con->rollBack();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}