<?php
include '../reusable/conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Step 1: Get image path from DB
    $result = $conn->query("SELECT cover_image FROM music WHERE music_id = $id");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = ".." . $row['cover_image'];  // DB path is like /uploads/music/covers/...

        // Step 2: Delete image file
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // Step 3: Delete from database
    $conn->query("DELETE FROM music WHERE music_id = $id");

    header("Location: music_list.php");
    exit();
}
?>
