<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Database connection
    $conn = new mysqli("localhost", "root", "", "anime");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Delete query
    $sql = "DELETE FROM favorites WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "Record deleted successfully.";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
    
    // Redirect back to main page
    header("Location: Favourites.php");
    exit();
} else {
    echo "Invalid request.";
}
?>
