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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user_id = "";

if(isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
}


// // Check if user is logged in
// if (!isset($_SESSION['user_id'])) {
//     // Redirect to login page if not logged in
//     header("Location: login.php");
//     exit;
// }

// Include database connection code here

// if(isset($_GET['user_id'])) {
//     $user_id = $_GET['user_id'];
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    $user_id = $_REQUEST['user_id'];

    // Retrieve form data
    $old_password = $_REQUEST['old_password'];
    $new_password = $_REQUEST['new_password'];
    $confirm_password = $_REQUEST['confirm_password'];

    // Validate form data (you can add more validation as needed)

    // Check if old password matches the one in the database
    // $user_id = $_SESSION['user_id'];
    // $sql = "SELECT password FROM users WHERE user_id = $user_id";
    $sql = "SELECT password FROM users WHERE user_id = $user_id";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (!password_verify($old_password, $row['password'])) {
            $error = "Old password is incorrect";
        } elseif ($new_password !== $confirm_password) {
            $error = "New password and confirm password do not match";
        } else {
            // Update password in the database
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            // $update_sql = "UPDATE users SET password = '$hashed_password' WHERE user_id = $user_id";
            $update_sql = "UPDATE users SET password = '$hashed_password' WHERE user_id = $user_id";
            if ($conn->query($update_sql) === TRUE) {
                $success = "Password changed successfully";
            } else {
                $error = "Error updating password: " . $conn->error;

            }
        }
    } else {
        $error = "User not found";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Password</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        max-width: 400px;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        margin-top: 0;
        text-align: center;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        width: 100%;
        padding: 15px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        background-color: #4CAF50;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    .error {
        color: red;
        margin-bottom: 10px;
    }

    .success {
        color: green;
        margin-bottom: 10px;
    }
</style>
</head>
<body>

<div class="container">
    <h2>Change Password</h2>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
    <!-- <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <label for="old_password">Old Password:</label>
        <input type="password" id="old_password" name="old_password" required>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <input type="submit" value="Change Password">
    </form> -->
    <!-- <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> -->
    <!-- <form method="post" action="change_password.php?user_id=<?php echo htmlspecialchars($user_id); ?>"> -->
    <form method="post" action="change_password.php?user_id=<?php echo htmlspecialchars($user_id); ?>">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
        <label for="old_password">Old Password:</label>
        <input type="password" id="old_password" name="old_password" required>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <input type="submit" value="Change Password">
    </form>
</div>

</body>
</html>

