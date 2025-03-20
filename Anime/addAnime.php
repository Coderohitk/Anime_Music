<?php
include "../reusable/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $release_year = $_POST["release_year"];

    // Handle file upload
    $target_dir = "../uploads/covers/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $cover_image = $target_dir . basename($_FILES["cover_image"]["name"]);
    move_uploaded_file($_FILES["cover_image"]["tmp_name"], $cover_image);

    // Save into database
    $sql = "INSERT INTO anime (title, description, release_year, cover_image) 
            VALUES ('$title', '$description', '$release_year', '$cover_image')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New anime added successfully!'); window.location.href='../index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <input type="number" name="release_year" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Cover Image</label>
            <input type="file" name="cover_image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Anime</button>
        <a href="../index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
