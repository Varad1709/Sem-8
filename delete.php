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

// Check if ID is set and not empty
// if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
//     // Prepare SQL statement to delete the record
//     $id = $_GET['user_id'];
//     $idq = $_GET['query_id'];
//     $sql = "DELETE FROM inquiry_details WHERE id3 = $id AND query_id = $idq";

//     // Execute the SQL statement
//     if ($conn->query($sql) === TRUE) {
//         // Redirect back to the previous page after deletion
//         header("Location: ".$_SERVER['HTTP_REFERER']);
//         exit();
//     } else {
//         echo "Error deleting record: " . $conn->error;
//     }
// } else {
//     echo "Invalid request";
// }

// Check if both user ID and query ID are set and not empty
if (isset($_GET['user_id']) && !empty($_GET['user_id']) && isset($_GET['query_id']) && !empty($_GET['query_id'])) {
    // Prepare SQL statement to delete the record
    $id = $_GET['user_id'];
    $idq = $_GET['query_id'];
    $sql = "DELETE FROM inquiry_details WHERE id3 = $id AND query_id = $idq";

    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        // Redirect back to the previous page after deletion
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request";
}


// Close connection
$conn->close();
?>
