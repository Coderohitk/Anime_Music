<?php
include '../reusable/conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM music WHERE music_id=$id");
    $music = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST["title"]);
    $artist = $conn->real_escape_string($_POST["artist"]);
    $album = $conn->real_escape_string($_POST["album"]);
    $genre = $conn->real_escape_string($_POST["genre"]);
    $release_year = (int) $_POST["release_year"];

    $sql = "UPDATE music SET title='$title', artist='$artist', album='$album', 
            genre='$genre', release_year='$release_year' WHERE music_id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Updated successfully!'); window.location.href='music_list.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="POST">
    <label>Title</label><input type="text" name="title" value="<?= $music['title'] ?>" required><br>
    <label>Artist</label><input type="text" name="artist" value="<?= $music['artist'] ?>" required><br>
    <label>Album</label><input type="text" name="album" value="<?= $music['album'] ?>"><br>
    <label>Genre</label><input type="text" name="genre" value="<?= $music['genre'] ?>"><br>
    <label>Release Year</label><input type="number" name="release_year" value="<?= $music['release_year'] ?>" required><br>
    <button type="submit">Update</button>
</form>
