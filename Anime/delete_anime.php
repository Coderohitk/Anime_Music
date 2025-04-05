<?php
include '../reusable/conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $result = $conn->query("SELECT cover_image FROM anime WHERE anime_id = $id");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $image_path = "../" . ltrim($row['cover_image'], "/"); // ✅ proper path

        echo "DEBUG: Image path: <code>$image_path</code><br>";

        if (file_exists($image_path)) {
            
            unlink($image_path);
        } 
    }

    $conn->query("DELETE FROM anime WHERE anime_id = $id");
    header("Location: read_anime.php");
    exit();
}
?>
