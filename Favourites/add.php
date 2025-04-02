<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "anime");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    <title>Add Record</title>
</head>
<body>
    <h2>Add New Record</h2>
    <form method="POST" action="add.php">
        <label>User ID:</label>
        <input type="number" name="user_id" required><br>
        <label>Anime ID:</label>
        <input type="number" name="anime_id" required><br>
        <label>Music ID:</label>
        <input type="number" name="music_id"><br>
        <button type="submit" name="add">Add</button>
    </form>
</body>
</html>
