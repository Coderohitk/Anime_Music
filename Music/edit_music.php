<?php
include '../reusable/conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM music WHERE music_id=$id");
    $music = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["music_id"];
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

    $cover_image = $_POST["existing_image"];

    if (!empty($_FILES["cover_image"]["name"])) {
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
            die("❌ Failed to upload new cover image.");
        }
    }

    $sql = "UPDATE music SET title='$title', artist='$artist', album='$album', genre='$genre', release_year='$release_year', cover_image='$cover_image' WHERE music_id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('✅ Music updated successfully!'); window.location.href='music_list.php';</script>";
    } else {
        echo "❌ Database error: " . $conn->error;
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="music_id" value="<?= $music['music_id'] ?>">
    <input type="hidden" name="existing_image" value="<?= $music['cover_image'] ?>">
    <label>Title</label><input type="text" name="title" value="<?= $music['title'] ?>" required><br>
    <label>Artist</label><input type="text" name="artist" value="<?= $music['artist'] ?>" required><br>
    <label>Album</label><input type="text" name="album" value="<?= $music['album'] ?>"><br>
    <label>Genre</label><input type="text" name="genre" value="<?= $music['genre'] ?>"><br>
    <label>Release Year</label><input type="number" name="release_year" value="<?= $music['release_year'] ?>"><br>
    <label>Cover Image</label><br>
    <img src="<?= $music['cover_image'] ?>" alt="Current Cover" style="height: 100px;"><br>
    <input type="file" name="cover_image"><br><small>Leave empty to keep current image</small><br><br>
    <button type="submit">Update</button>
</form>
