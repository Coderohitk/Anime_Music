<?php
include '../reusable/conn.php';  // Corrected path to the connection file

// Fetch anime list for dropdown
$anime_result = $conn->query("SELECT * FROM anime");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $anime_id = (int) $_POST["anime_id"]; // Ensure it's an integer
    $title = $conn->real_escape_string($_POST["title"]);
    $artist = $conn->real_escape_string($_POST["artist"]);

    // Validate file upload
    $target_dir = "../uploads/music/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $audio_file = $target_dir . basename($_FILES["audio_file"]["name"]);

    // Check file type (only allow MP3, WAV, etc.)
    $allowed_types = ['audio/mpeg', 'audio/wav'];
    if (!in_array($_FILES["audio_file"]["type"], $allowed_types)) {
        echo "Error: Only MP3 and WAV files are allowed.";
        exit;
    }

    // Check file size (limit to 10MB)
    if ($_FILES["audio_file"]["size"] > 10485760) {
        echo "Error: File size exceeds 10MB.";
        exit;
    }

    // Attempt to move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["audio_file"]["tmp_name"], $audio_file)) {
        // Insert into database
        $sql = "INSERT INTO music (anime_id, title, artist, audio_file) 
                VALUES ('$anime_id', '$title', '$artist', '$audio_file')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Music added successfully!'); window.location.href='../index.php';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error: There was a problem uploading the file.";
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
            <label class="form-label">Anime</label>
            <select name="anime_id" class="form-control" required>
                <option value="">Select Anime</option>
                <?php while ($anime = $anime_result->fetch_assoc()): ?>
                    <option value="<?php echo $anime['id']; ?>"><?php echo $anime['title']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Artist</label>
            <input type="text" name="artist" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Audio File</label>
            <input type="file" name="audio_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Music</button>
        <a href="../index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
