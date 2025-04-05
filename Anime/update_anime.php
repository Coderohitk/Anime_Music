<?php
include "../reusable/conn.php";

// Load existing anime
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $result = $conn->query("SELECT * FROM anime WHERE anime_id=$id");
    $anime = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["anime_id"];
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);
    $release_year = $_POST["release_year"];
    $genre = mysqli_real_escape_string($conn, $_POST["genre"]);
    $rating = $_POST["rating"];

    $target_dir = "../uploads/anime/";
    $relative_path = "uploads/anime/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Start with existing image
    $cover_image = $_POST["existing_image"];

    if (!empty($_FILES["cover_image"]["name"])) {
        $file = $_FILES["cover_image"];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

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
            die("❌ Upload error: " . ($errors[$file["error"]] ?? "Unknown error"));
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
    }

    $sql = "UPDATE anime SET 
                title='$title', 
                description='$description', 
                release_year='$release_year', 
                genre='$genre', 
                rating='$rating', 
                cover_image='$cover_image' 
            WHERE anime_id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('✅ Anime updated successfully!'); window.location.href='read_anime.php';</script>";
    } else {
        echo "❌ Database error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Anime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Update Anime</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="anime_id" value="<?= $anime['anime_id']; ?>">
        <input type="hidden" name="existing_image" value="<?= $anime['cover_image']; ?>">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?= $anime['title']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"><?= $anime['description']; ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Release Year</label>
            <input type="number" name="release_year" class="form-control" value="<?= $anime['release_year']; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Genre</label>
            <input type="text" name="genre" class="form-control" value="<?= $anime['genre']; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Rating</label>
            <input type="number" name="rating" class="form-control" step="0.1" max="10" value="<?= $anime['rating']; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Cover Image</label><br>
            <img src="../<?= $anime['cover_image']; ?>" alt="Current Cover" style="height: 100px;"><br><br>
            <input type="file" name="cover_image" class="form-control">
            <small class="text-muted">Leave blank to keep current image.</small>
        </div>
        <button type="submit" class="btn btn-success">Update Anime</button>
    </form>
</div>
</body>
</html>
    