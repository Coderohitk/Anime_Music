<?php
// Include database connection
include '../reusable/conn.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $character_id = $_GET['id'];

    // Step 1: Fetch the image URL from the database
    $image_sql = "SELECT image_url FROM characters WHERE character_id = ?";
    $stmt = $conn->prepare($image_sql);
    $stmt->bind_param("i", $character_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_url = $row['image_url'];

        // Step 2: Construct the absolute server path to the image
        $image_path = realpath(__DIR__ . '/../' . $image_url);

        // Step 3: Delete the image file from the server (if it exists)
        if (!empty($image_url) && $image_path && file_exists($image_path)) {
            if (unlink($image_path)) {
                echo "Image deleted successfully from the folder.<br>";
            } else {
                echo "Error deleting the image from the folder.<br>";
            }
        } else {
            echo "Image file does not exist or path is incorrect.<br>";
        }

        // Step 4: Delete the character from the database
        $delete_sql = "DELETE FROM characters WHERE character_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $character_id);

        if ($delete_stmt->execute()) {
            // Redirect to the character list page after deletion
            header("Location: characters.php");
            exit();
        } else {
            echo "Error deleting character: " . $conn->error;
        }

        $delete_stmt->close();
    } else {
        echo "Character not found.";
    }

    $stmt->close();
} else {
    echo "Invalid ID specified.";
}

// Close the database connection
$conn->close();
?>
