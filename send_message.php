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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST['message'];
    $sender = $_SESSION['name'];

    $sql = "INSERT INTO messages (sender_id, message_content) VALUES ('$sender', '$message')";
    if ($conn->query($sql) === TRUE) {
        // Echo the message content back to the client
        echo $message;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
