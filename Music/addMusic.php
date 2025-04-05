<?php
include '../reusable/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST["title"]);
    $artist = $conn->real_escape_string($_POST["artist"]);
    $album = $conn->real_escape_string($_POST["album"]);
    $genre = $conn->real_escape_string($_POST["genre"]);
    $release_year = (int) $_POST["release_year"];

    $target_dir = "../uploads/music/covers/";
    $relative_path = "/uploads/music/covers/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file = $_FILES["cover_image"];
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];

    if ($file["error"] !== UPLOAD_ERR_OK) {
        die("❌ Upload error: " . $file["error"]);
    }

    if (!in_array($file["type"], $allowed_types)) {
        die("❌ Invalid file type: " . $file["type"]);
    }

    $unique_name = uniqid() . "_" . basename($file["name"]);
    $target_file = $target_dir . $unique_name;
    $cover_image = $relative_path . $unique_name;

    if (!move_uploaded_file($file["tmp_name"], $target_file)) {
        die("❌ Failed to save uploaded image.");
    }

    $sql = "INSERT INTO music (title, artist, album, genre, release_year, cover_image)
            VALUES ('$title', '$artist', '$album', '$genre', '$release_year', '$cover_image')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('✅ Music added successfully!'); window.location.href='../index.php';</script>";
    } else {
        echo "❌ Database error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Music</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Add Music</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3"><label>Title</label><input type="text" name="title" class="form-control" required></div>
        <div class="mb-3"><label>Artist</label><input type="text" name="artist" class="form-control" required></div>
        <div class="mb-3"><label>Album</label><input type="text" name="album" class="form-control"></div>
        <div class="mb-3"><label>Genre</label><input type="text" name="genre" class="form-control"></div>
        <div class="mb-3"><label>Release Year</label><input type="number" name="release_year" class="form-control"></div>
        <div class="mb-3"><label>Cover Image</label><input type="file" name="cover_image" class="form-control" required></div>
        <button type="submit" class="btn btn-primary">Add Music</button>
    </form>
</div>
</body>
</html>
