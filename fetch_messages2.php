<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "demo4";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_SESSION['name'];

$sql = "SELECT * FROM inquiry_details WHERE name3='$name' ORDER BY time_and_date ASC"; // Change DESC to ASC
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $sender = $row['name3'];
        $message = $row['query3'];
        $reply = $row['reply'];

        echo "<div class='message'><p class='sender'>$sender:</p><p>$message</p></div>";
    }
} else {
    echo "<p>No messages found.</p>";
}

$conn->close();
?>
