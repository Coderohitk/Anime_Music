<?php
include '../reusable/conn.php';  // Ensure correct database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $title = $conn->real_escape_string($_POST["title"]);
    $artist = $conn->real_escape_string($_POST["artist"]);
    $album = $conn->real_escape_string($_POST["album"]);
    $genre = $conn->real_escape_string($_POST["genre"]);
    $release_year = (int) $_POST["release_year"];

    // Handle audio file upload
    $target_audio_dir = "../uploads/music/audio/";
    if (!is_dir($target_audio_dir)) {
        mkdir($target_audio_dir, 0777, true);
    }
    $audio_file = $target_audio_dir . basename($_FILES["audio_file"]["name"]);

    // Allowed file types for audio
    // $allowed_audio_types = ['audio/mpeg', 'audio/wav'];
    // if (!in_array($_FILES["audio_file"]["type"], $allowed_audio_types)) {
    //     echo "Error: Only MP3 and WAV files are allowed.";
    //     exit;
    // }

    // Handle cover image upload
    $target_image_dir = "../uploads//music/covers/";
    if (!is_dir($target_image_dir)) {
        mkdir($target_image_dir, 0777, true);
    }
    $cover_image = $target_image_dir . basename($_FILES["cover_image"]["name"]);

    // Allowed file types for images
    $allowed_image_types = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($_FILES["cover_image"]["type"], $allowed_image_types)) {
        echo "Error: Only JPG, PNG, and WEBP images are allowed.";
        exit;
    }

    // Move uploaded files
    if (move_uploaded_file($_FILES["audio_file"]["tmp_name"], $audio_file) &&
        move_uploaded_file($_FILES["cover_image"]["tmp_name"], $cover_image)) {
        
        // Insert into database
        $sql = "INSERT INTO music (title, artist, album, genre, release_year, cover_image, audio_file) 
                VALUES ('$title', '$artist', '$album', '$genre', '$release_year', '$cover_image', '$audio_file')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Music added successfully!'); window.location.href='../index.php';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error: There was a problem uploading the files.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Music</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Add Music</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Artist</label>
            <input type="text" name="artist" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Album</label>
            <input type="text" name="album" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Genre</label>
            <input type="text" name="genre" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Release Year</label>
            <input type="number" name="release_year" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Cover Image</label>
            <input type="file" name="cover_image" class="form-control" required>
        </div>
        <!-- <div class="mb-3">
            <label class="form-label">Audio File</label>
            <input type="file" name="audio_file" class="form-control" required>
        </div> -->
        <button type="submit" class="btn btn-primary">Add Music</button>
        <a href="../index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
