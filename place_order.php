<?php 
include 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

// Get cart items
$sql = "SELECT c.id as cart_id, d.id as dress_id, d.name, d.price, d.image, c.quantity 
        FROM cart c 
        JOIN dresses d ON c.dress_id = d.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    redirect('cart.php');
}

// Calculate total
$total = 0;
$items = [];
while($row = $result->fetch_assoc()) {
    $item_total = $row['price'] * $row['quantity'];
    $total += $item_total;
    $items[] = $row;
}

// Create order
$sql = "INSERT INTO orders (user_id, total_amount) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("id", $_SESSION['user_id'], $total);
$stmt->execute();
$order_id = $conn->insert_id;

// Add order items
foreach ($items as $item) {
    $sql = "INSERT INTO order_items (order_id, dress_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiid", $order_id, $item['dress_id'], $item['quantity'], $item['price']);
    $stmt->execute();
}

// Clear cart
$sql = "DELETE FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();

// Redirect to thank you page
redirect('account.php?order_success=1');
?>