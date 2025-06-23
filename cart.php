<?php 
include 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

// Handle remove from cart
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    $sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $remove_id, $_SESSION['user_id']);
    $stmt->execute();
    redirect('cart.php');
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Dress Collection</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-5">
        <h1 class="mb-4">Your Shopping Cart</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        while($row = $result->fetch_assoc()): 
                            $item_total = $row['price'] * $row['quantity'];
                            $total += $item_total;
                        ?>
                            <tr>
                                <td>
                                    <img src="uploads/<?php echo $row['image']; ?>" width="50" class="mr-3">
                                    <?php echo $row['name']; ?>
                                </td>
                                <td>$<?php echo number_format($row['price'], 2); ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td>$<?php echo number_format($item_total, 2); ?></td>
                                <td>
                                    <a href="cart.php?remove=<?php echo $row['cart_id']; ?>" class="btn btn-sm btn-danger">Remove</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Total:</strong></td>
                            <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="text-right">
                <a href="browse_dresses.php" class="btn btn-secondary">Continue Shopping</a>
                <a href="checkout.php" class="btn btn-primary ml-2">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Your cart is empty. <a href="browse_dresses.php">Browse our collection</a> to add items.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>