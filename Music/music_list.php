<?php
include '../reusable/conn.php';
$result = $conn->query("SELECT * FROM music");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Music List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Music List</h2>
    <a href="addMusic.php" class="btn btn-primary mb-3">Add Music</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Artist</th>
                <th>Album</th>
                <th>Genre</th>
                <th>Year</th>
                <th>Cover</th>
                <th>Audio</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['title'] ?></td>
                <td><?= $row['artist'] ?></td>
                <td><?= $row['album'] ?></td>
                <td><?= $row['genre'] ?></td>
                <td><?= $row['release_year'] ?></td>
                <td><img src="<?= $row['cover_image'] ?>" width="50"></td>
                <!-- <td><audio controls><source src="<?= $row['audio_file'] ?>" type="audio/mpeg"></audio></td> -->
                <td>
                    <a href="edit_music.php?id=<?= $row['music_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_music.php?id=<?= $row['music_id'] ?>" class="btn btn-danger btn-sm" 
                        onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
