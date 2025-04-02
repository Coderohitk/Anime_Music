<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "anime");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch existing record
    $sql = "SELECT * FROM favorites WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $stmt->close();
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $anime_id = $_POST['anime_id'];
    $music_id = $_POST['music_id'];
    
    // Update query
    $sql = "UPDATE favorites SET anime_id = ?, music_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $anime_id, $music_id, $id);
    
    if ($stmt->execute()) {
        echo "Record updated successfully.";
        header("Location: Favourites.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Record</title>
</head>
<body>
    <h2>Edit Record</h2>
    <form method="POST" action="edit.php">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label>Anime ID:</label>
        <input type="number" name="anime_id" value="<?php echo $row['anime_id']; ?>" required><br>
        <label>Music ID:</label>
        <input type="number" name="music_id" value="<?php echo $row['music_id']; ?>" required><br>
        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>
