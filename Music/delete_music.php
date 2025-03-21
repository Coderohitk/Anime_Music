<?php
include '../reusable/conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM music WHERE music_id=$id");
    echo "<script>alert('Deleted successfully!'); window.location.href='music_list.php';</script>";
}
?>
