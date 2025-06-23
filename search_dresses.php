<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Dresses - Dress Collection</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-5">
        <h1 class="mb-4">Search Dresses</h1>
        
        <form method="GET" action="search_dresses.php" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="query" placeholder="Search by dress name..." value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </div>
        </form>
        
        <div class="row">
            <?php
            if (isset($_GET['query'])) {
                $search = "%" . $_GET['query'] . "%";
                $sql = "SELECT * FROM dresses WHERE name LIKE ? OR description LIKE ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $search, $search);
                $stmt->execute();
                $result = $stmt->get_result();
                
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
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<div class="col-12"><p>No dresses found matching your search.</p></div>';
                }
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>