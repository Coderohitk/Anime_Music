<?php
// Include database connection
include '../reusable/conn.php';

// Check if ID is set and is numeric (safe check)
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $character_id = $_GET['id'];

    // Fetch character details from the database
    $sql = "SELECT * FROM characters WHERE character_id = $character_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Character not found.";
        exit();
    }
} else {
    echo "Invalid ID specified.";
    exit();
}

// Check if form is submitted to update the character
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $anime_id = $_POST['anime_id'];
    $description = $_POST['description'];
    $voice_actor_english = $_POST['voice_actor_english'];
    $voice_actor_japanese = $_POST['voice_actor_japanese'];
    $role = $_POST['role']; // New field

    // Handle image upload (if a new image is uploaded)
    $image = $_FILES['image'];
    $image_new_name = "";
    if ($image['error'] == 0) {
        $image_name = $image['name'];
        $image_tmp_name = $image['tmp_name'];
        $image_size = $image['size'];

        // Check file size (max 5MB)
        if ($image_size <= 5000000) {
            // Generate unique name for the image
            $image_new_name = uniqid('', true) . "." . strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
            $image_destination = "../uploads/" . $image_new_name;

            // Move the uploaded image to the server folder
            if (!move_uploaded_file($image_tmp_name, $image_destination)) {
                echo "Error uploading image.";
                exit();
            }
        } else {
            echo "Image size is too large. Maximum allowed size is 5MB.";
            exit();
        }
    }

    // If no new image is uploaded, retain the previous image URL
    $image_url = $image_new_name ? $image_destination : $row['image_url'];

    // Update query to modify character details
    $update_sql = "UPDATE characters SET name = ?, anime_id = ?, description = ?, image_url = ?, voice_actor_english = ?, voice_actor_japanese = ?, role = ? WHERE character_id = ?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sisssssi", $name, $anime_id, $description, $image_url, $voice_actor_english, $voice_actor_japanese, $role, $character_id);

    if ($stmt->execute()) {
        echo "Character updated successfully.";
        header("Location: characters.php"); // Redirect to the character list page
        exit();
    } else {
        echo "Error updating character: " . $conn->error;
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
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
    
    <!-- Form to edit character details -->
    <form method="POST" action="edit.php?id=<?php echo $character_id; ?>" enctype="multipart/form-data">
        <label for="name">Name:</label><br>
        <input type="text" name="name" value="<?php echo $row['name']; ?>" required><br><br>

        <label for="anime_id">Anime ID:</label><br>
        <input type="number" name="anime_id" value="<?php echo $row['anime_id']; ?>" required><br><br>

        <label for="description">Description:</label><br>
        <textarea name="description" required><?php echo $row['description']; ?></textarea><br><br>

        <label for="image">Image (Leave empty to keep current):</label><br>
        <input type="file" name="image" accept="image/*"><br><br>

        <label for="voice_actor_english">English Voice Actor:</label><br>
        <input type="text" name="voice_actor_english" value="<?php echo $row['voice_actor_english']; ?>" required><br><br>

        <label for="voice_actor_japanese">Japanese Voice Actor:</label><br>
        <input type="text" name="voice_actor_japanese" value="<?php echo $row['voice_actor_japanese']; ?>" required><br><br>

        <!-- New Role Field -->
        <label for="role">Role:</label><br>
        <input type="text" name="role" value="<?php echo $row['role']; ?>" required><br><br>

        <button type="submit">Update Character</button>
    </form>

    <br>
    <a href="characters.php">Back to Character List</a>
</body>
</html>
