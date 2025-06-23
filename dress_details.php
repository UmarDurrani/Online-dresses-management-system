<?php 
include 'config.php';

if (!isset($_GET['id'])) {
    redirect('browse_dresses.php');
}

$dress_id = $_GET['id'];
$sql = "SELECT * FROM dresses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dress_id);
$stmt->execute();
$result = $stmt->get_result();
$dress = $result->fetch_assoc();

if (!$dress) {
    redirect('browse_dresses.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $dress['name']; ?> - Dress Collection</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="uploads/<?php echo $dress['image']; ?>" class="img-fluid" alt="<?php echo $dress['name']; ?>">
            </div>
            <div class="col-md-6">
                <h1><?php echo $dress['name']; ?></h1>
                <h3 class="text-primary">$<?php echo $dress['price']; ?></h3>
                <p><?php echo $dress['description']; ?></p>
                
                <?php if (isLoggedIn()): ?>
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="dress_id" value="<?php echo $dress['id']; ?>">
                        <div class="form-group">
                            <label for="quantity">Quantity:</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" style="width: 80px;">
                        </div>
                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                    </form>
                <?php else: ?>
                    <p><a href="login.php">Login</a> to add this item to your cart.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>