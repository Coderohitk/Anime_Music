<?php
// Database connection
include '../reusable/conn.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch all characters
$sql = "SELECT * FROM characters";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime Characters</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            width: 100px;
            height: auto;
        }
        .action-btns a {
            margin: 0 5px;
            text-decoration: none;
            color: #fff;
            background-color: #4CAF50;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .add-btn {
            background-color: #008CBA;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<h1>Anime Characters</h1>

<a href="add.php" class="add-btn">+ Add Character</a>

<table>
    <thead>
        <tr>
            <th>Character ID</th>
            <th>Anime ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Role</th>
            <th>Image</th>
            <th>English Voice Actor</th>
            <th>Japanese Voice Actor</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            // Output each character's data
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["character_id"] . "</td>";
                echo "<td>" . $row["anime_id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["description"] . "</td>";
                echo "<td>" . $row["role"] . "</td>";
                echo "<td><img src='../" . $row['image_url'] . "' alt='" . $row['name'] . "'></td>";
                echo "<td>" . $row["voice_actor_english"] . "</td>";
                echo "<td>" . $row["voice_actor_japanese"] . "</td>";
                echo "<td class='action-btns'>
                        <a href='edit.php?id=" . $row['character_id'] . "'>Edit</a>
                        <a href='delete.php?id=" . $row['character_id'] . "' class='delete-btn'>Delete</a>
                    </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No characters found</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
