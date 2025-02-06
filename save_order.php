<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    die(json_encode(['success' => false, 'error' => 'User not logged in']));
}

$data = json_decode(file_get_contents('php://input'), true);

// Sanitize inputs
$name = mysqli_real_escape_string($conn, $data['name']);
$number = mysqli_real_escape_string($conn, $data['number']);
$email = mysqli_real_escape_string($conn, $data['email']);
$method = mysqli_real_escape_string($conn, $data['method']);
$address = mysqli_real_escape_string($conn, $data['address']);
$payment_status = mysqli_real_escape_string($conn, $data['payment_status']);
$placed_on = date('d-M-Y');

// Get cart products
$cart_products = [];
$cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'");
while ($cart_item = mysqli_fetch_assoc($cart_query)) {
    $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ')';
}
$total_products = implode(', ', $cart_products);

// Calculate total price
$cart_total = 0;
$cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'");
while ($cart_item = mysqli_fetch_assoc($cart_query)) {
    $cart_total += ($cart_item['price'] * $cart_item['quantity']);
}

// Insert order
$insert_query = mysqli_query(
    $conn,
    "INSERT INTO `orders` (user_id, name, number, email, method, address, 
    total_products, total_price, placed_on, payment_status) 
    VALUES ('$user_id', '$name', '$number', '$email', '$method', '$address', 
    '$total_products', '$cart_total', '$placed_on', '$payment_status')"
);

if ($insert_query) {
    // Clear cart after successful order
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'");
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}
?>