<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "demo4";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID parameter is set
if(isset($_GET['user_id'])) {
    // Retrieve data for the specified ID
    $id = $_GET['user_id'];
    $idq = $_GET['query_id'];
    $sql = "SELECT * FROM inquiry_details WHERE id3=$id AND query_id = $idq";
    $result = $conn->query($sql);

    // Check if record exists
    if ($result->num_rows > 0) {
        // Fetch data
        $row = $result->fetch_assoc();
        $name = $row['name3'];
        $email = $row['email3'];
        $phone = $row['phone3'];
        $star = $row['star'];
        $query = $row['query3'];
        $note = $row['note']; // Assuming 'note' is the column name for storing notes
        $reply = $row['reply'];
        $replyTimestamp = $row['time_and_date']; // Assuming 'reply_timestamp' is the column name for storing reply timestamps

        // Update data if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newStar = $_POST['star'];
            $newNote = $_POST['note'];
            // $newReply = $_POST['reply'];

            // Prepare and bind the update statement
            $updateSql = "UPDATE inquiry_details SET star=?, note=?, time_and_date=NOW() WHERE id3=?";
            $stmt = $conn->prepare($updateSql);

            // Check if the statement was prepared successfully
            if ($stmt) {
                // Bind parameters
                $stmt->bind_param("ssi", $newStar, $newNote, $id);

                // Execute the update statement
                if ($stmt->execute()) {
                    echo "Record updated successfully";
                    // Refresh the page to display updated data
                    header("Refresh:2; url=edit.php?user_id=$id&query_id=$idq");
                    exit();
                } else {
                    echo "Error updating record: " . $stmt->error;
                }

                // Close the statement
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        }

        // Display form with data pre-filled
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Inquiry</title>
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
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    input[type="text"],
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    input[type="submit"] {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
        font-size: 16px;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    .reply-section {
        display: none;
    }

    .show-reply-btn {
        margin-bottom: 10px;
    }

    .reply-info {
        font-size: 12px;
        color: #666;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Edit Inquiry</h2>
    <form action="<?php echo $_SERVER["PHP_SELF"] . "?user_id=$id&query_id=$idq"; ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="email">Email-ID:</label>
            <input type="text" id="email" name="email" value="<?php echo $email; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="star">Star Rating:</label>
            <input type="text" id="star" name="star" value="<?php echo $star; ?>">
        </div>
        <div class="form-group">
            <label for="query">Query:</label><br>
            <textarea id="query" name="query" readonly><?php echo $query; ?></textarea>
        </div>
        <!-- <div class="form-group reply-section">
            <label for="reply">Reply:</label><br>
            <textarea id="reply" name="reply"><?php echo $reply; ?></textarea>
            <?php if (!empty($replyTimestamp)) { ?>
                <div class="reply-info">Reply sent at <?php echo $replyTimestamp; ?></div>
            <?php } ?>
        </div> -->
        <div class="form-group">
            <label for="note">Note:</label><br>
            <textarea id="note" name="note"><?php echo $note; ?></textarea>
        </div>
        <!-- <div class="form-group">
            <button type="button" class="show-reply-btn" onclick="toggleReply()">Show Reply</button>
        </div> -->
        <div>
            <input type="submit" value="Update">
        </div>
    </form>
</div>

<script>
    function toggleReply() {
        var replySection = document.querySelector('.reply-section');
        replySection.style.display = (replySection.style.display === 'none') ? 'block' : 'none';
    }
</script>

</body>
</html>

<?php
    } else {
        echo "Record not found.";
    }
} else {
    echo "ID parameter not set.";
}

$conn->close();
?>
