<?php
// Database connection
include '../reusable/conn.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['name'])) {
    // Get the form data
    $name = $_POST['name'];
    $anime_id = $_POST['anime_id'];
    $role = $_POST['role'];
    $description = $_POST['description'];
    $voice_actor_english = $_POST['voice_actor_english'];
    $voice_actor_japanese = $_POST['voice_actor_japanese'];

    // Handle the image upload
    $image = $_FILES['image'];
    $image_name = $image['name'];
    $image_tmp_name = $image['tmp_name'];
    $image_size = $image['size'];
    $image_error = $image['error'];

    if ($image_error === 0) {
        if ($image_size <= 5000000) {
            // Generate a unique name
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
            $image_new_name = uniqid('char_', true) . '.' . $image_ext;
            $upload_dir = "../uploads/character/";
            $image_destination = $upload_dir . $image_new_name;

            // Make sure the uploads/character/ folder exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Move uploaded image
            if (move_uploaded_file($image_tmp_name, $image_destination)) {
                // Save relative path to DB (for web use)
                $image_url = "uploads/character/" . $image_new_name;

                $sql = "INSERT INTO characters (name, anime_id, description, image_url, voice_actor_english, voice_actor_japanese, role) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sisssss", $name, $anime_id, $description, $image_url, $voice_actor_english, $voice_actor_japanese, $role);

                if ($stmt->execute()) {
                    header("Location: characters.php?success=1");
                    exit();
                } else {
                    echo "<p style='color: red;'>Error adding character: " . $conn->error . "</p>";
                }
                $stmt->close();
            } else {
                echo "<p style='color: red;'>Failed to move uploaded image.</p>";
            }
        } else {
            echo "<p style='color: red;'>Image too large. Max 5MB allowed.</p>";
        }
    } else {
        echo "<p style='color: red;'>Error uploading image.</p>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Character</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        a {
            text-decoration: none;
            color: #007BFF;
            display: inline-block;
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Add New Character</h2>
    
    <!-- Form for adding a new character -->
    <form method="POST" action="add.php" enctype="multipart/form-data">
        
        <label for="name">Name:</label>
        <input type="text" name="name" required>

        <label for="role">Role:</label>
        <input type="text" name="role" required>

        <label for="anime_id">Anime ID:</label>
        <input type="number" name="anime_id" required>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea>

        <label for="image">Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <label for="voice_actor_english">English Voice Actor:</label>
        <input type="text" name="voice_actor_english" required>

        <label for="voice_actor_japanese">Japanese Voice Actor:</label>
        <input type="text" name="voice_actor_japanese" required>

        <button type="submit">Add Character</button>
    </form>

    <br>
    <a href="characters.php">Back to Character List</a>
</body>
</html>
