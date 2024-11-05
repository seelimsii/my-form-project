<?php
// Authentication code (assume basic session check for simplicity)

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formDB";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM submissions";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>Name</th><th>Email</th><th>Mobile</th><th>Message</th><th>Image</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['mobile']}</td>
                <td>{$row['message']}</td>
                <td><img src='{$row['file_path']}' alt='User Image' width='100'></td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No data found.";
}

$conn->close();
?>
