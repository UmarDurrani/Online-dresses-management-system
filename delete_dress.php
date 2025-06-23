<?php 
include 'config.php';

if (!isAdmin()) {
    redirect('index.php');
}

if (!isset($_GET['id'])) {
    redirect('manage_dresses.php');
}

$dress_id = $_GET['id'];

// Get dress details to delete image
$sql = "SELECT image FROM dresses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dress_id);
$stmt->execute();
$result = $stmt->get_result();
$dress = $result->fetch_assoc();

if ($dress) {
    // Delete image file
    if (file_exists("uploads/" . $dress['image'])) {
        unlink("uploads/" . $dress['image']);
    }
    
    // Delete dress from database
    $sql = "DELETE FROM dresses WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $dress_id);
    $stmt->execute();
}

redirect('manage_dresses.php?success=delete');
?>