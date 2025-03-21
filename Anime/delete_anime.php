<?php
include "../reusable/conn.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $conn->query("DELETE FROM anime WHERE anime_id=$id");
    echo "<script>alert('Anime deleted successfully!'); window.location.href='read_anime.php';</script>";
}
?>
