<?php
include "../reusable/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $release_year = $_POST["release_year"];
    $genre = $_POST["genre"];
    $rating = $_POST["rating"];

    $target_dir = "../uploads/anime/"; // One level up from this file
    $relative_path = "uploads/anime/";

    // Create the uploads directory if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Debug: check if folder is writable
    if (!is_writable($target_dir)) {
        die("❌ Error: Upload folder is not writable! Please check folder permissions.");
    }

    $file = $_FILES["cover_image"];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

    // Debug: check for upload errors
    if ($file["error"] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds upload_max_filesize.",
            UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds MAX_FILE_SIZE in HTML form.",
            UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded.",
            UPLOAD_ERR_NO_FILE => "No file was uploaded.",
            UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder.",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
            UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload.",
        ];
        die("❌ Upload error: " . ($errors[$file["error"]] ?? "Unknown error (" . $file["error"] . ")"));
    }

    if (!in_array($file["type"], $allowed_types)) {
        die("❌ Invalid file type: " . $file["type"]);
    }

    // Generate a unique name
    $unique_name = uniqid() . "_" . basename($file["name"]);
    $target_file = $target_dir . $unique_name;
    $db_file_path = $relative_path . $unique_name;

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        // Upload success — insert into DB
        $sql = "INSERT INTO anime (title, description, release_year, genre, rating, cover_image) 
                VALUES ('$title', '$description', '$release_year', '$genre', '$rating', '$db_file_path')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('✅ New anime added successfully!'); window.location.href='../index.php';</script>";
        } else {
            echo "❌ Database error: " . $conn->error;
        }
    } else {
        die("❌ Failed to move uploaded file. Please check folder permissions or temp directory setup.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Anime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Add Anime</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Release Year</label>
            <input type="number" name="release_year" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Genre</label>
            <input type="text" name="genre" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Rating</label>
            <input type="number" name="rating" class="form-control" step="0.1" max="10">
        </div>
        <div class="mb-3">
            <label class="form-label">Cover Image</label>
            <input type="file" name="cover_image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Anime</button>
    </form>
</div>
</body>
</html>
