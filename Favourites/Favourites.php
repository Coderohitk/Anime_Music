<?php
include '../reusable/conn.php';

if (isset($_POST['add'])) {
    $user_id = $_POST['user_id'];
    $anime_id = $_POST['anime_id'];
    $music_id = $_POST['music_id'];
    
    // Insert query
    $sql = "INSERT INTO favorites (user_id, anime_id, music_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $anime_id, $music_id);
    
    if ($stmt->execute()) {
        echo "Record added successfully.";
        header("Location: Favourites.php");
        exit();
    } else {
        echo "Error adding record: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Favorites</title>
</head>
<body>
    <h2>Add New Favorite</h2>
    <form action="add.php" method="POST">
        User ID: <input type="number" name="user_id" required><br>
        Anime ID: <input type="number" name="anime_id"><br>
        Music ID: <input type="number" name="music_id"><br>
        <input type="submit" name="add" value="Add">
    </form>

    <h2>Favorites List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Anime ID</th>
            <th>Music ID</th>
            <th>Actions</th>
        </tr>
        <?php
     $conn = new mysqli("localhost", "root", "", "anime");
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
        $result = $conn->query("SELECT * FROM favorites");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['user_id']}</td>
                    <td>{$row['anime_id']}</td>
                    <td>{$row['music_id']}</td>
                    <td>
                        <a href='edit.php?id={$row['id']}'>Edit</a>
                        <a href='delete.php?id={$row['id']}'>Delete</a>
                    </td>
                  </tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
