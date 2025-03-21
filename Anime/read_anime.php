<?php
include "../reusable/conn.php";
$result = $conn->query("SELECT * FROM anime");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Anime List</h2>
    <a href="addAnime.php" class="btn btn-primary mb-3">Add New Anime</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Release Year</th>
                <th>Genre</th>
                <th>Rating</th>
                <th>Cover</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row["title"]; ?></td>
                    <td><?= $row["description"]; ?></td>
                    <td><?= $row["release_year"]; ?></td>
                    <td><?= $row["genre"]; ?></td>
                    <td><?= $row["rating"]; ?></td>
                    <td><img src="../<?= $row["cover_image"]; ?> "width="50"></td>
                    <td>
                        <a href="update_anime.php?id=<?= $row['anime_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_anime.php?id=<?= $row['anime_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
