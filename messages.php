<?php
session_start();

date_default_timezone_set('Asia/Kolkata');

$is_employee = isset($_POST['is_employee']) && $_POST['is_employee'] === 'true';
// if (isset($_GET['is_employee']) && $_GET['is_employee'] === 'true') {
//     $_SESSION['is_employee'] = true;
// } else {
//     $_SESSION['is_employee'] = false;
// }
// $user_id = $_POST['user_id'];

require('fpdf.php');

$servername = "localhost";
$username = "root";
$password = "";
$database = "demo4";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$emp_id = $_GET['emp_id'];

$selected_month = isset($_GET['month']) ? $_GET['month'] : '';

if(isset($_GET['name'])) {
    $name17 = $_GET['name'];
    // $selected_month = $_GET['month'];
    $selected_month = isset($_GET['month']) ? $_GET['month'] : '';
}

// Retrieve customer ID from the URL parameter
if(isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $customer_id = $_GET['user_id'];
} else {
    die("Invalid customer ID");
}

// Fetch customer details from the database
$sql = "SELECT * FROM inquiry_details WHERE id3 = $customer_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Customer not found");
}

$customer = $result->fetch_assoc();

// Handle inserting new message
if(isset($_POST['submit'])) {
    $message_content = $_POST['message_content'];
    $timestamp = date('Y-m-d H:i:s');
    $query_id2 = $_REQUEST['query_id'];
    $name108 = $_GET['name'];

    // $source = isset($_SESSION['is_employee']) && $_SESSION['is_employee'] ? 'Employee' : 'Regular User';
    $source = 'Employee';

    $insert_sql = "INSERT INTO messages2 (id, query_id2, message_content, time_and_date, source, name_em) 
    VALUES ($customer_id, $query_id2, '$message_content', '$timestamp', '$source', '$name108')";

    
    // Insert new message into the messages2 table
    // $insert_sql = "INSERT INTO messages2 (id, message_content, time_and_date) 
    // VALUES ($customer_id, '$message_content', '$timestamp')";
    if ($conn->query($insert_sql) === TRUE) {
        // Redirect back to the same page to refresh
        header("Location: messages.php?user_id=$customer_id&query_id=$query_id2&name=$name17");
        exit;
    } else {
        echo "Error: " . $insert_sql . "<br>" . $conn->error;
    }
}

// Fetch all messages associated with the customer from both tables
// $sql_all_messages = "SELECT id3 AS id, query3 AS message_content, time_and_date AS timestamp FROM inquiry_details WHERE id3 = $customer_id 
//                     UNION
//                     SELECT id, message_content, time_and_date FROM messages2 WHERE id = $customer_id
//                     ORDER BY timestamp ASC";

$user_id = $_GET['user_id']; // Get the user_id from the URL parameter
$query_id = $_GET['query_id']; // Get the query_id from the URL parameter


$sql_all_messages = "SELECT id, message_content, timestamp, source 
FROM (
    SELECT id3 AS id, query3 AS message_content, time_and_date AS timestamp, 'Regular User' AS source 
    FROM inquiry_details 
    WHERE id3 = $user_id AND query_id = $query_id
    
    UNION 
    
    SELECT id, message_content, time_and_date AS timestamp, source 
    FROM messages2 
    WHERE id = $customer_id and query_id2 = $query_id
    
    -- UNION
    
    -- SELECT emp_id, name AS name, NULL AS timestamp, NULL AS source 
    -- FROM users_employee
) AS combined_results

ORDER BY timestamp ASC";

$result_all_messages = $conn->query($sql_all_messages);

// Check if query execution was successful
if ($result_all_messages === false) {
    echo "Error executing query: " . $conn->error;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Messages</title>
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
        width: 93%; /* Add this line */
        background-color: #4CAF50;
        overflow: hidden;
        width: 94.1%;
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

    .container2 {
        max-width: 1400px;
        /* margin: 10px; */
        /* padding: 10px; */
        background-color: #fff;
        border-radius: 8px;
        /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
        max-width: 100%; /* Make it full-width */
        margin: 0; /* Remove margin */
        padding: 0;
    }

    .message-container {
        margin-bottom: 20px;
        position: relative;
        margin-left: 100px;
    }

    .message {
        background-color: #f2f2f2;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
    }

    .reply-form {
        margin-top: 20px;
        position: relative; /* Change position to absolute */
        bottom: 0; /* Place the reply form at the bottom of the message container */
        left: 105px; 
        width: calc(100% - 200px);
    }

    .reply-form textarea {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border-radius: 5px;
    }

    .reply-form button {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        border-radius: 5px;
        cursor: pointer;
    }

    button.back-button {
        position: absolute;
        top: 60px; /* Adjust the top position as needed */
        left: 110px; /* Adjust the left position as needed */
        z-index: 1; /* Ensure the button appears on top of other elements */
    }

    h2 {
        margin-left: 103px; /* Adjusted margin to leave space for the dashboard */
        margin-top: 54px;
    }

</style>
</head>
<body>

<!-- <?php
$selected_month = $_GET['month'];

?> -->




<!-- <button onclick="window.location.href='follow_up.php'<?php if (!empty($selected_month)) echo "?month=$selected_month"; ?>'" style="position: absolute; top: 10px; left: 10px;">Back</button> -->
<!-- <button class="back-button" onclick="window.location.href='follow_up.php<?php if (!empty($selected_month)) echo "?month=$selected_month"; ?><?php if (!empty($name17)) echo "?name=$name17"; ?><?php if (!empty($emp_id)) echo "?emp_id=$emp_id"; ?>'">Back</button> -->
<button class="back-button" onclick="window.location.href='follow_up.php<?php if (!empty($selected_month)) echo "?month=$selected_month"; ?><?php if (!empty($name17)) echo "&name=$name17"; ?><?php if (!empty($emp_id)) echo "&emp_id=$emp_id"; ?>'">Back</button>
<br>

<div class="container2">
    <div class="navbar">
        <a href="http://localhost/Sem-8%20Project%20Final/login.php">Login</a>
        <a href="http://localhost/Sem-8%20Project%20Final/employee_login.php">Employee Login</a>
        <!-- <a href="http://localhost/Sem-8%20Project%20Final/inquiry.php">Inquiry</a> -->
        <!-- <a href="#contact">Contact</a> -->
    </div>
</div>

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
    <a href="follow_up.php">Complaints</a>
    <a href="change_password_em.php?emp_id=<?php echo $emp_id; ?>">Change Password</a>
    <a href="logout_employee.php">Logout</a>
</div>

<h2>Customer Inquiry: <?php echo $customer['name3']; ?></h2>

<div class="message-container">
    <?php
    if ($result_all_messages->num_rows > 0) {
        while($message = $result_all_messages->fetch_assoc()) {
            echo "<div class='message'>";
            echo $message['message_content']; // Display message content
            echo "<br>";
            // echo "<small>" . $message['timestamp'] . "</small>"; // Display timestamp
            echo "<small>" . date('Y-m-d H:i:s', strtotime($message['timestamp'])) . "</small>";
        
            // Check if the message is from a regular user or an employee
            // if ($message['source'] === 'Regular User') {
            //     echo "<br>";
            //     echo "<small>Source: Employee</small>";
            // } elseif ($message['source'] === 'Employee') {
            //     echo "<br>";
            //     echo "<small>Source: Employee</small>";
            //     echo "<br>";
            //     echo "<small>ID: " . $message['id'] . "</small>"; // Display employee's ID
            // }

            if ($message['source'] === 'Regular User') {
                echo "<br>";
                // echo "<small>Source:$customer['name3'];</small>"; // Corrected source display for regular users
                echo "<small>Source: " . $customer['name3'] . "</small>";
            } elseif ($message['source'] === 'Employee') {
                // echo "<br>";
                $employee_id = $message['id'];
                $employee_sql = "SELECT name_em FROM messages2 WHERE query_id2 = $query_id";
                $employee_result = $conn->query($employee_sql);
                if ($employee_result->num_rows > 0) {
                    $employee_row = $employee_result->fetch_assoc();
                    $employee_name = $employee_row['name_em'];
                    echo "<br>";
                    echo "<small>Source:Employee</small>";
                }
                // if (!empty($message['id'])) {
                //     echo "<br>";
                //     echo "<small>ID: " . $message['id'] . "</small>"; // Display employee's ID
                // }
            } elseif ($message['source'] === NULL) {
                echo "<br>";
                echo "<small>Source:" . $name17 . "</small>"; // Source display for messages from users_employee
            }
        
            echo "</div>";
        }
    } else {
        echo "<p>No messages available</p>";
    }
    ?>
</div>


<div class="reply-form">
    <!-- <form method="post">
        <textarea name="message_content" placeholder="Type your reply here"></textarea>
        <br>
        <button type="submit" name="submit">Send</button>
    </form> -->
    <form method="post">
        <textarea name="message_content" placeholder="Type your reply here"></textarea>
        <br>
        <button type="submit" name="submit">Send</button>
    </form>
</div>

</body>
</html>