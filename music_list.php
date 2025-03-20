<?php
include 'reusable/conn.php';  // Include database connection

// Get the anime_id from the URL parameter
$anime_id = isset($_GET['anime_id']) ? $_GET['anime_id'] : 0;

// Fetch anime title and related music by joining the anime and music tables
$sql = "
    SELECT music.*, anime.title AS anime_title
    FROM music
    JOIN anime ON music.anime_id = anime.id
    WHERE music.anime_id = $anime_id
";

$music_result = $conn->query($sql);

if ($music_result->num_rows == 0) {
    $music_message = "No music found for this anime.";
} else {
    $music_message = null;
}

// Fetch the anime title for display
$anime_title = '';
if ($anime_id > 0) {
    $anime_result = $conn->query("SELECT title FROM anime WHERE id = $anime_id");
    if ($anime_result->num_rows > 0) {
        $anime_data = $anime_result->fetch_assoc();
        $anime_title = $anime_data['title'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Music List</h2>
    <h3>Anime: <?php echo htmlspecialchars($anime_title); ?> </h3> <!-- Display anime title -->
    <?php if ($music_message): ?>
        <div class="alert alert-warning" role="alert">
            <?php echo $music_message; ?>
        </div>
    <?php endif; ?>
    <ul class="list-group">
        <?php while ($music = $music_result->fetch_assoc()): ?>
            <li class="list-group-item">
                <strong><?php echo $music['title']; ?></strong> by <?php echo $music['artist']; ?>
                <br>
                <a href="<?php echo $music['audio_file']; ?>" download>Download Audio</a>
            </li>
        <?php endwhile; ?>
    </ul>
    <a href="index.php" class="btn btn-secondary mt-4">Back to Anime List</a>
</div>
</body>
</html>
