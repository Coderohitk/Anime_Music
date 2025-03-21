<?php
include "../reusable/conn.php";

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

    $cover_image = $anime["cover_image"]; // Keep old image
    if (!empty($_FILES["cover_image"]["name"])) {
        $target_dir = "../uploads/covers/";
        $cover_image = $target_dir . basename($_FILES["cover_image"]["name"]);
        move_uploaded_file($_FILES["cover_image"]["tmp_name"], $cover_image);
    }

    $sql = "UPDATE anime SET title='$title', description='$description', release_year='$release_year', genre='$genre', rating='$rating', cover_image='$cover_image' WHERE anime_id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Anime updated successfully!'); window.location.href='read_anime.php';</script>";
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
    <title>Update Anime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Update Anime</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="anime_id" value="<?= $anime['anime_id']; ?>">
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
            <input type="number" name="release_year" class="form-control" value="<?= $anime['release_year']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Genre</label>
            <input type="text" name="genre" class="form-control" value="<?= $anime['genre']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Rating</label>
            <input type="number" step="0.1" name="rating" class="form-control" value="<?= $anime['rating']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Cover Image</label>
            <input type="file" name="cover_image" class="form-control">
            <img src="<?= $anime['cover_image']; ?>" width="100">
        </div>
        <button type="submit" class="btn btn-primary">Update Anime</button>
        <a href="read_anime.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
