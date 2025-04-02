<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "anime");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    // Get the ID from the URL
    $id = $_GET['id'];
    
    // Fetch existing record for editing
    $sql = "SELECT * FROM characters WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if record exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Record not found.";
        exit();
    }
    
    $stmt->close();
}

// Update record logic
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $anime_id = $_POST['anime_id'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];
    $voice_actor_english = $_POST['voice_actor_english'];
    $voice_actor_japanese = $_POST['voice_actor_japanese'];
    
    // Prepare the UPDATE query
    $sql = "UPDATE characters SET name = ?, anime_id = ?, description = ?, image_url = ?, voice_actor_english = ?, voice_actor = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissssi", $name, $anime_id, $description, $image_url, $voice_actor_english, $voice_actor_japanese, $id);
    
    // Execute the query and provide feedback
    if ($stmt->execute()) {
        echo "Record updated successfully.";
        header("Location: characters.php"); // Redirect to the list page
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Character</title>
</head>
<body>
    <h2>Edit Character</h2>
    
    <!-- Display the current values in the form -->
    <form method="POST" action="edit.php">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
        
        <label for="name">Name:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required><br><br>
        
        <label for="anime_id">Anime ID:</label><br>
        <input type="number" name="anime_id" value="<?php echo htmlspecialchars($row['anime_id']); ?>" required><br><br>
        
        <label for="description">Description:</label><br>
        <textarea name="description" required><?php echo htmlspecialchars($row['description']); ?></textarea><br><br>
        
        <label for="image_url">Image URL:</label><br>
        <input type="url" name="image_url" value="<?php echo htmlspecialchars($row['image_url']); ?>" required><br><br>
        
        <label for="voice_actor_english">English Voice Actor:</label><br>
        <input type="text" name="voice_actor_english" value="<?php echo htmlspecialchars($row['voice_actor_english']); ?>" required><br><br>
        
        <label for="voice_actor_japanese">Japanese Voice Actor:</label><br>
        <input type="text" name="voice_actor_japanese" value="<?php echo htmlspecialchars($row['voice_actor']); ?>" required><br><br>
        
        <button type="submit" name="update">Update</button>
    </form>

    <br>
    <a href="characters.php">Back to Character List</a>
</body>
</html>
