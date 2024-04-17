<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "demo4";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

date_default_timezone_set('Asia/Kolkata');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $star = $_POST['star'];
    $query = $_POST['query'];
    $timestamp = date('Y-m-d H:i:s');

    $user_id_query = "SELECT user_id FROM users WHERE name = '$name'";
    $user_id_result = $conn->query($user_id_query);

    if ($user_id_result && $user_id_result->num_rows > 0) {
        $row = $user_id_result->fetch_assoc();
        $user_id = $row['user_id'];

        $sql = "INSERT INTO inquiry_details (id3, name3, email3, phone3, star, query3, date3, time_and_date) 
                VALUES ('$user_id', '$name', '$email', '$phone', $star, '$query', CURRENT_DATE(), '$timestamp')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Data stored successfully');</script>";
            // Redirect to the same page to prevent form resubmission
            echo "<script>window.location = '" . $_SERVER['PHP_SELF'] . "';</script>";
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
} else {
    echo "Error: User not found";
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Form</title>
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
            width: 100%; /* Add this line */
            background-color: #4CAF50;
            overflow: hidden;
            width: 100%;
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 1000px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            margin-top: 5px;
        }

        .stars {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .star {
            font-size: 30px;
            color: #ccc;
            cursor: pointer;
            margin: 0 5px;
        }

        .star:hover,
        .star.active {
            color: #ffca28;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            display: block;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container2">
    <div class="navbar">
        <a href="http://localhost/Sem-8%20Project%20Final/login.php">Login</a>
        <a href="http://localhost/Sem-8%20Project%20Final/employee_login.php">Employee Login</a>
        <a href="http://localhost/Sem-8%20Project%20Final/inquiry.php">Inquiry</a>
    </div>
</div>

<!-- <div class="dashboard">
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
    <br>
    <a href="#">Employees</a>
    <a href="#">Complaints</a>
    <a href="#">Change Password</a>
    <a href="#">Logout</a>
</div> -->

<div class="container">
    <h2 style="text-align: center;">Inquiry Form</h2>
    <!-- <form id="inquiry-form" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post"> -->
    <form id="inquiry-form" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" novalidate>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email-ID:</label>
            <input type="email" id="email" name="email">
        </div>
        <div class="form-group">
            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone">
        </div>
        <div class="form-group">
            <label for="query">Query:</label>
            <textarea id="query" name="query" required></textarea>
        </div>
        <div class="stars">
            <span class="star" data-rating="1">&#9733;</span>
            <span class="star" data-rating="2">&#9733;</span>
            <span class="star" data-rating="3">&#9733;</span>
            <span class="star" data-rating="4">&#9733;</span>
            <span class="star" data-rating="5">&#9733;</span>
        </div>
        <!-- Hidden input field to store the selected star rating -->
        <input type="hidden" id="star-rating" name="star" value="">
        <!-- <div class="form-group">
            <label for="query">Query:</label><br>
            <textarea id="query" name="query" required></textarea>
        </div> -->
        <div>
            <input type="submit" value="Submit">
        </div>
    </form>
</div>

<script>
    const form = document.getElementById('inquiry-form');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const stars = document.querySelectorAll('.star');
    const starRatingInput = document.getElementById('star-rating');

    // form.addEventListener('submit', function (event) {
    //     let isValid = true;

    //     // Validate Name
    //     if (!/^[a-zA-Z ]*$/.test(nameInput.value)) {
    //         alert("Name should only contain alphabets and spaces.");
    //         isValid = false;
    //     }

    //     // Validate Email
    //     // if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
    //     //     alert("Invalid email format.");
    //     //     isValid = false;
    //     // }

    //     // Validate Phone Number
    //     if (!/^\d{10}$/.test(phoneInput.value)) {
    //         alert("Phone number should be 10 digits.");
    //         isValid = false;
    //     }

    //     if (!isValid) {
    //         event.preventDefault();
    //     }
    // });

    form.addEventListener('submit', function (event) {
    let isValid = true;

    // Validate Name
    if (!/^[a-zA-Z ]*$/.test(nameInput.value)) {
        alert("Name should only contain alphabets and spaces.");
        isValid = false;
    }

    // Validate Email
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
        alert("Invalid email format.");
        isValid = false;
    }

    // Validate Phone Number
    if (!/^\d{10}$/.test(phoneInput.value)) {
        alert("Phone number should be 10 digits.");
        isValid = false;
    }

    if (!isValid) {
        event.preventDefault();
    }
});


    stars.forEach((star) => {
        star.addEventListener('mouseover', function() {
            const rating = this.getAttribute('data-rating');
            highlightStars(rating);
        });

        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            highlightStars(rating);
            // Set the selected star rating value to the input field
            starRatingInput.value = rating;
        });
    });

    function highlightStars(rating) {
        stars.forEach((star) => {
            if (star.getAttribute('data-rating') <= rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }
</script>

</body>
</html>
