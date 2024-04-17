<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "demo4";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Registration
if(isset($_POST['register'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!')</script>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the username already exists
        $check_sql = "SELECT * FROM users WHERE name='$name'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            echo "<script>alert('Username already exists!')</script>";
        } else {
            // Insert new user into the database
            $insert_sql = "INSERT INTO users (name, password) VALUES ('$name', '$hashed_password')";
            if ($conn->query($insert_sql) === TRUE) {
                echo "<script>window.location.href = 'login.php';</script>";
                exit(); // Stop further execution
            } else {
                echo "<script>alert('Error: " . $insert_sql . "<br>" . $conn->error . "')</script>";
            }
        }
    }
}

// Login
if(isset($_POST['login'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE name='$name'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['name'] = $name; // Set the username in the session
            echo "<script> window.location.href = 'chats.php?user_id=" . $row['user_id'] . "';</script>";
            exit(); // Stop further execution
        } else {
            echo "<script>alert('Incorrect password!')</script>";
        }
    } else {
        echo "<script>alert('User not found!')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login/Register Page</title>
<style>
    /* CSS styles for navbar */
    /* .navbar {
        overflow: hidden;
        background-color: #333;
    }

    .navbar a {
        float: left;
        display: block;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
    }

    .navbar a:hover {
        background-color: #ddd;
        color: black;
    } */
    .navbar {
        background-color: #4CAF50; /* Background color */
        overflow: hidden;
        width: 100%; /* Make it full-width */
        position: fixed; /* Fixed position so it stays at the top */
        top: 0; /* Position at the top */
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

    .container {
        max-width: 1600px;
        width: 300px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    /* .container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 30px;
        width: 400px;
        text-align: center;
    } */

    h2 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #333;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        margin-bottom: 10px;
    }

    .button-group {
        display: flex;
        justify-content: space-between;
    }

    button {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        /* background-color: #4CAF50; */
        /* color: #fff; */
        background-color: #4CAF50; /* Button color */
        color: #fff; /* Text color */
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        /* background-color: #45a049; */
        background-color: #45a049; /* Hover color */
    }

    .register-text {
        color: #333;
        margin-bottom: 10px;
        cursor: pointer;
    }
</style>
</head>
<body>

<div class="container2">
    <div class="navbar">
        <a href="http://localhost/Sem-8%20Project%20Final/login.php">Login</a>
        <!-- <a href="http://localhost/Sem-8%20Project%20Final/employee_login.php">Employee Login</a> -->
        <a href="http://localhost/Sem-8%20Project%20Final/inquiry.php">Inquiry</a>
        <!-- <a href="#contact">Contact</a> -->
    </div>
</div>
    
<div class="container">
    <h2>Login/Register Page</h2>
    <div class="form-group" id="registerForm" style="display: none;">
        <form action="" method="post" onsubmit="return validateName() && validatePassword()">
            <input type="text" name="name" placeholder="Name" required><br>
            <input type="password" name="password" id="password" placeholder="Password" required><br>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
            <input type="submit" name="register" value="Register">
        </form>
    </div>
    <div class="form-group" id="loginForm" style="display: none;">
        <form action="" method="post">
            <input type="text" name="name" placeholder="Name" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" name="login" value="Login">
        </form>
    </div>
    <div class="button-group">
        <button id="registerBtn">Register</button>
        <button id="loginBtn">Login</button>
    </div>
    <!-- <div class="register-text" id="registerText">Already registered? Click here to login.</div> -->
</div>

<script>
    var registerForm = document.getElementById('registerForm');
    var loginForm = document.getElementById('loginForm');
    var registerText = document.getElementById('registerText');
    var registerBtn = document.getElementById('registerBtn');
    var loginBtn = document.getElementById('loginBtn');

    registerBtn.addEventListener('click', function() {
        registerForm.style.display = 'block';
        loginForm.style.display = 'none';
        registerText.style.display = 'none';
    });

    loginBtn.addEventListener('click', function() {
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
        registerText.style.display = 'none';
    });

    registerText.addEventListener('click', function() {
        registerForm.style.display = 'none';
        loginForm.style.display = 'block';
        registerText.style.display = 'none';
    });

    function validateName() {
        var nameInput = document.getElementsByName('name')[0].value;
        var nameRegex = /^[a-zA-Z\s]+$/;
        if (!nameRegex.test(nameInput)) {
            alert('Name must contain only alphabets and spaces');
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }

    function validatePassword() {
        var passwordInput = document.getElementById('password');
        var password = passwordInput.value;
        var passwordRegex = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{5,}$/;
        if (!passwordRegex.test(password)) {
            alert('Password must have at least 5 letters, 1 number, and 1 special character');
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }
</script>

</body>
</html>
