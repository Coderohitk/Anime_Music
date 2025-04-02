<?php
    $conn = new mysqli("localhost", "root", "", "anime");

// Check if ID is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete query to remove the character by its ID
    $delete_sql = "DELETE FROM characters WHERE id = $id";

    if ($conn->query($delete_sql) === TRUE) {
        echo "Character deleted successfully.";
        header("Location: characters.php");  // Redirect to characters page after deletion
        exit();
    } else {
        echo "Error deleting character: " . $conn->error;
    }
} else {
    echo "No ID specified.";
}

$conn->close();  // Close the database connection
?>
