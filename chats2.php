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

// Retrieve customer ID from the URL parameter
if(isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $customer_id = $_GET['user_id'];
} else {
    die("Invalid customer ID");
}

// Fetch list of inquiries for the customer
$sql = "SELECT query_id FROM inquiry_details WHERE id3 = $customer_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("No inquiries found for the customer");
}

$inquiries = array();

while($row = $result->fetch_assoc()) {
    $inquiries[] = $row['query_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Chats</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 800px;
        margin: 50px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
        margin-top: 0;
    }
    .button-container {
        display: flex;
        flex-wrap: wrap;
    }
    .button-container button {
        margin: 10px;
        padding: 10px 20px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .button-container button:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>

<div class="container">
    <h2>Customer Chats:</h2>
    <div class="button-container">
        <?php

        $counter = 1;
        // Generate buttons for each inquiry
        foreach($inquiries as $inquiry_id) {
            echo "<button onclick=\"location.href='messages_users.php?user_id=$customer_id&query_id=$inquiry_id'\">Inquiry $counter</button>";
            $counter++;
        }
        ?>
    </div>
</div>

</body>
</html>
