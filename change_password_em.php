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

$emp_id2 = "";

if(isset($_GET['emp_id'])) {
    $emp_id2 = $_GET['emp_id'];
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $emp_id2 = $_REQUEST['emp_id'];

    // Retrieve form data
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // $sql = "SELECT `password` FROM users_employee WHERE emp_id = $emp_id2";
    $sql = "SELECT `password` FROM users_employee WHERE emp_id = '$emp_id2'";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (!password_verify($old_password, $row['password'])) {
            $error = "Old password is incorrect";
        } elseif ($new_password !== $confirm_password) {
            $error = "New password and confirm password do not match";
        } else {
            // Update password in the database
            // $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            // // $update_sql = "UPDATE users_employee SET password = '$hashed_password' WHERE emp_id = $emp_id2";
            // $update_sql = "UPDATE users_employee 
            //             SET `password` = '$hashed_password' 
            //             WHERE emp_id = $emp_id2";
            // if ($conn->query($update_sql) === TRUE) {
            //     $success = "Password changed successfully";
            // } else {
            //     $error = "Error updating password: " . $conn->error;

            // }
            // Update password in the database
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Debugging
            // echo "Emp ID: " . $emp_id2 . "<br>";
            // echo "Hashed Password: " . $hashed_password . "<br>";

            // Prepare the statement
            $stmt = $conn->prepare("UPDATE users_employee SET `password` = ? WHERE emp_id = ?");
            $stmt->bind_param("si", $hashed_password, $emp_id2);

            // Bind parameters and execute the statement
            if ($stmt->execute()) {
                $success = "Password changed successfully";
            } else {
                $error = "Error updating password: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();

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
    <form method="post" action="change_password_em.php?emp_id=<?php echo htmlspecialchars($emp_id2); ?>">
        <input type="hidden" name="emp_id" value="<?php echo htmlspecialchars($emp_id2); ?>">
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

