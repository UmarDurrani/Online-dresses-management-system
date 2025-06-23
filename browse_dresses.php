<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Dresses - Dress Collection</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-5">
        <h1 class="mb-4">Browse Our Dress Collection</h1>
        
        <div class="row">
            <?php
            $sql = "SELECT * FROM dresses ORDER BY created_at DESC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="uploads/'.$row['image'].'" class="card-img-top" alt="'.$row['name'].'">
                            <div class="card-body">
                                <h5 class="card-title">'.$row['name'].'</h5>
                                <p class="card-text">$'.$row['price'].'</p>
                                <a href="dress_details.php?id='.$row['id'].'" class="btn btn-primary">View Details</a>
                                '.(isLoggedIn() ? '<a href="add_to_cart.php?id='.$row['id'].'" class="btn btn-success ml-2">Add to Cart</a>' : '').'
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="col-12"><p>No dresses found.</p></div>';
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>