<?php
include 'reusable/conn.php';  // Include database connection

// Fetch all anime
$anime_result = $conn->query("SELECT * FROM anime");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime Music</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <?php 
    include 'reusable/nav.php'; 
    ?>
    <ul class="list-group">
        <?php while ($anime = $anime_result->fetch_assoc()): ?>
            <li class="list-group-item d-flex align-items-center">
                <!-- Display anime cover image -->
                <img src="<?php echo $anime['cover_image']; ?>" alt="<?php echo htmlspecialchars($anime['title']); ?>" class="img-thumbnail" style="width: 100px; height: 100px; margin-right: 10px;">
                <!-- Display anime title with a link -->
                <a href="music_list.php?anime_id=<?php echo $anime['anime_id']; ?>" class="fs-4">
                    <?php echo htmlspecialchars($anime['title']); ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</div>
</body>
</html>
