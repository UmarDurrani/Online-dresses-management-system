<?php 
include 'config.php';

if (!isAdmin()) {
    redirect('index.php');
}

if (!isset($_GET['id']) || !isset($_GET['status'])) {
    redirect('manage_orders.php');
}

$order_id = $_GET['id'];
$status = $_GET['status'];

// Validate status
if (!in_array($status, ['pending', 'processing', 'completed'])) {
    redirect('manage_orders.php');
}

// Update order status
$sql = "UPDATE orders SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $order_id);
$stmt->execute();

redirect('manage_orders.php?success=1');
?>