<?php 
include 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if (!isset($_GET['id'])) {
    redirect('cart.php');
}

$cart_id = $_GET['id'];

// Verify the cart item belongs to the user
$sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $cart_id, $_SESSION['user_id']);
$stmt->execute();

redirect('cart.php');
?>