<?php 
include 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dress_id = $_POST['dress_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
} elseif (isset($_GET['id'])) {
    $dress_id = $_GET['id'];
    $quantity = 1;
} else {
    redirect('browse_dresses.php');
}

// Check if dress exists
$sql = "SELECT id FROM dresses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dress_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    redirect('browse_dresses.php');
}

// Check if item already in cart
$sql = "SELECT id, quantity FROM cart WHERE user_id = ? AND dress_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $_SESSION['user_id'], $dress_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update quantity
    $cart_item = $result->fetch_assoc();
    $new_quantity = $cart_item['quantity'] + $quantity;
    $sql = "UPDATE cart SET quantity = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $new_quantity, $cart_item['id']);
    $stmt->execute();
} else {
    // Add new item to cart
    $sql = "INSERT INTO cart (user_id, dress_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $_SESSION['user_id'], $dress_id, $quantity);
    $stmt->execute();
}

redirect('cart.php');
?>