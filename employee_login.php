<?php
session_start(); // Move session_start() to the top

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "demo4";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Register new user
    if(isset($_POST['register'])) {
        $name = $_POST['name'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $emp_id = $_POST['employee_id'];

        if($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users_employee (emp_id, name, password) VALUES ('$emp_id', '$name', '$hashed_password')";
            
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Registration successful!')</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "<script>alert('Passwords do not match!')</script>";
        }
    } else if (isset($_POST['login'])) { // Login validation
        $name = $_POST['name'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users_employee WHERE name='$name'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if(password_verify($password, $row['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['name'] = $name; // Set the username in the session
                $_SESSION['is_employee'] = true;


                $employee_check_sql = "SELECT * FROM users_employee WHERE name='$name'";
                $employee_check_result = $conn->query($employee_check_sql);
                if ($employee_check_result->num_rows > 0) {
                    $_SESSION['is_employee'] = true; // Set $_SESSION['is_employee'] to true for employee login
                    $_SESSION['emp_id'] = $row['emp_id'];
                } else {
                    $_SESSION['is_employee'] = false; // Set $_SESSION['is_employee'] to false for regular user login
                }

                // echo "<script>alert('Login successful!'); window.location.href = 'follow_up.php?name=" . urlencode($name) . "';</script>";
                echo "<script> window.location.href = 'follow_up.php?name=" . urlencode($name) . "&emp_id=" . urlencode($row['emp_id']) . "';</script>";
                exit(); // Stop further execution
            } else {
                echo "<script>alert('Incorrect password!')</script>";
            }
        } else {
            echo "<script>alert('User not found!')</script>";
        }

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Login/Register</title>
<style>
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

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        /* display: flex;
        justify-content: center;
        align-items: center; */
        
        height: 100vh;
    }


    .container {
        max-width: 1600px;
        width: 300px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        height: auto; /* Set the height to 100% of the viewport height */
        display: flex; /* Use flexbox to vertically center the content */
        flex-direction: column; /* Align items vertically */
        justify-content: center; /* Center items vertically */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .container2 {
        /* max-width: 1400px; */
        /* margin: 10px; */
        /* padding: 10px; */
        background-color: #fff;
        border-radius: 8px;
        /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
        width: 100%; /* Make it full-width */
        margin: 0; /* Remove margin */
        padding: 0;
    }

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
        background-color: #4CAF50;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #45a049;
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
        <a href="http://localhost/Sem-8%20Project%20Final/employee_login.php">Employee Login</a>
        <!-- <a href="http://localhost/Sem-8%20Project%20Final/inquiry.php">Inquiry</a> -->
        <!-- <a href="#contact">Contact</a> -->
    </div>
</div>

<div class="container">
    <h2>Employee Login/Register</h2>
    <div class="form-group" id="registerForm" style="display: none;">
        <form action="" method="post" onsubmit="return validateEmployeeRegistration()">
            <input type="text" name="name" id="registerName" placeholder="Name" value="" required><br>
            <input type="password" name="password" id="registerPassword" placeholder="Password" value="" required><br>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
            <input type="text" name="employee_id" placeholder="Employee ID Number" required><br>
            <input type="submit" name="register" value="Register">
        </form>
    </div>
    <div class="form-group" id="loginForm" style="display: none;">
        <form action="" method="post" onsubmit="return validateLogin()">
            <input type="text" name="name" id="loginName" placeholder="Name" value="<?php echo isset($loginFailed) ? htmlspecialchars($_POST['name']) : '' ?>" required><br>
            <input type="password" name="password" id="loginPassword" placeholder="Password" value="<?php echo isset($loginFailed) ? htmlspecialchars($_POST['password']) : '' ?>" required><br>
            <input type="submit" name="login" value="Login">
        </form>
    </div>
    <div class="button-group">
        <button id="registerBtn">Register</button>
        <button id="loginBtn">Login</button>
    </div>
    <div class="register-text" id="registerText">Already registered? Click here to login.</div>
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

    
</script>

</body>
</html>
