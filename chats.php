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

    .dashboard {
        position: fixed;
        left: 0;
        top: 0px; /* Adjust this value based on your navbar height */
        height: calc(100% - 60px); /* Adjust this value based on your navbar height */
        height: 100%;
        background-color: #4CAF50;
        padding: 20px;
        font-size: 8px;
    }

    .dashboard a {
        display: block;
        color: #fff;
        text-decoration: none;
        margin-bottom: 20px;
    }

    .dashboard a:hover {
        color: #ffca28;
    }

    .navbar {
        position: fixed; /* Add this line */
        top: 0; /* Add this line */
        right: 0; /* Add this line */
        width: 94%; /* Add this line */
        background-color: #4CAF50;
        overflow: hidden;
        width: 93.4%;
        color: #fff;
    }

    .navbar a {
        float: left;
        display: block;
        color: #f2f2f2; /* Text color */
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
    }

    .navbar a:hover {
    /* background-color: #ddd; Hover color */
        background-color: #45a049;
            /* color: black; Text color on hover */
    }
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
    }

    .container2 {
            background-color: #fff;
            border-radius: 8px;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
            padding: 2px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center; /* Center content horizontally */
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
    .contact-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .contact-list-item {
        margin-bottom: 10px;
    }
    .contact-button {
        width: 100%;
        padding: 15px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .contact-button:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>
<div class="dashboard">
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <a href="change_password.php?user_id=<?php echo $customer_id; ?>">Change Password</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container2">
    <div class="navbar">
        <a href="http://localhost/Sem-8%20Project%20Final/login.php">Login</a>
        <a href="http://localhost/Sem-8%20Project%20Final/employee_login.php">Employee Login</a>
        <a href="http://localhost/Sem-8%20Project%20Final/inquiry.php">Inquiry</a>
    </div>
</div>



<div class="container">
    <h2>Customer Chats:</h2>
    <ul class="contact-list">
        <?php
        $counter = 1;
        // Generate buttons for each inquiry
        foreach($inquiries as $inquiry_id) {
            echo "<li class='contact-list-item'><button class='contact-button' onclick=\"location.href='messages_users.php?user_id=$customer_id&query_id=$inquiry_id'\">Inquiry $counter</button></li>";
            $counter++;
        }
        ?>
    </ul>
</div>

</body>
</html>

