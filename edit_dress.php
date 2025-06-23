<?php 
include 'config.php';

if (!isAdmin()) {
    redirect('index.php');
}

if (!isset($_GET['id'])) {
    redirect('manage_dresses.php');
}

$dress_id = $_GET['id'];

// Get dress details
$sql = "SELECT * FROM dresses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dress_id);
$stmt->execute();
$result = $stmt->get_result();
$dress = $result->fetch_assoc();

if (!$dress) {
    redirect('manage_dresses.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    
    // Handle image upload if new image is provided
    $image = $dress['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Delete old image
            if (file_exists("uploads/" . $dress['image'])) {
                unlink("uploads/" . $dress['image']);
            }
            
            // Generate unique filename
            $image = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $image;
            
            // Move uploaded file
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "File is not an image.";
        }
    }
    
    if (!isset($error)) {
        // Update dress in database
        $sql = "UPDATE dresses SET name = ?, description = ?, price = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsi", $name, $description, $price, $image, $dress_id);
        
        if ($stmt->execute()) {
            redirect('manage_dresses.php?success=edit');
        } else {
            $error = "Error updating dress. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dress - Dress Collection</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Dress</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="edit_dress.php?id=<?php echo $dress_id; ?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Dress Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $dress['name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $dress['description']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $dress['price']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="image">Current Image</label><br>
                                <img src="uploads/<?php echo $dress['image']; ?>" width="100" class="mb-2">
                                <label for="image">Upload New Image (leave blank to keep current)</label>
                                <input type="file" class="form-control-file" id="image" name="image">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Dress</button>
                            <a href="manage_dresses.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>