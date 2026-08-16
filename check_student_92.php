<?php
$servername = '127.0.0.1';
$username = 'root';
$password = '';
$dbname = 'learnsphere';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$sql = 'SELECT id, name, profile_photo FROM users WHERE id = 92';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row['id'] . "\n";
        echo "Name: " . $row['name'] . "\n";
        echo "Profile Photo: " . ($row['profile_photo'] ?? 'NULL') . "\n";
    }
} else {
    echo "No results\n";
}

$conn->close();
?>
