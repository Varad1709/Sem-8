<?php
require('fpdf.php');

session_start();

// Check if the user is an employee
$is_employee = isset($_SESSION['is_employee']) && $_SESSION['is_employee'];

// Redirect to login if not logged in as employee
if (!$is_employee) {
    header("Location: login.php"); // Redirect to regular login page
    exit;
}

$name17 = "";
if(isset($_GET['name'])) {
    $name17 = $_GET['name'];
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "demo4";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize selected month
$selected_month = '';

// Check if a month is selected
if (isset($_GET['month']) && !empty($_GET['month'])) {
    $selected_month = $_GET['month'];
    // Validate selected month to avoid SQL injection
    if (!preg_match('/^[1-9]|1[0-2]$/', $selected_month)) {
        die("Invalid month selection");
    }
}

$emp_id = $_GET['emp_id'];

// Construct SQL query based on the selected month
if (!empty($selected_month)) {
    $sql = "SELECT * FROM inquiry_details WHERE MONTH(date3) = $selected_month";
} else {
    // If no month is selected, fetch all records
    $sql = "SELECT * FROM inquiry_details";
}

$result = $conn->query($sql);

// Check for query execution error
if (!$result) {
    die("Error in query: " . $conn->error);
}

class PDF extends FPDF {
    // Page header
    function Header() {
        // Title
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,'Inquiry Follow Up',0,1,'C');
        // Line break
        $this->Ln(10);
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// PDF generation function
function generatePDF($result) {
    // Instantiate FPDF class
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('Arial', 'B', 16);

    // Title
    $pdf->Cell(0, 10, 'Inquiry Follow Up', 0, 1, 'C');
    $pdf->Ln(10); // Line break

    // Table header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(30, 10, 'Name', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Email', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Phone', 1, 0, 'C');
    $pdf->Cell(25, 10, 'Star Rating', 1, 0, 'C');
    $pdf->Cell(65, 10, 'Query', 1, 1, 'C');

    // Table data
    $pdf->SetFont('Arial', '', 12);
    while($row = $result->fetch_assoc()) {
        $pdf->Cell(30, 10, $row["name3"], 1, 0, 'L');
        $pdf->Cell(30, 10, $row["email3"], 1, 0, 'L');
        $pdf->Cell(30, 10, $row["phone3"], 1, 0, 'L');
        $pdf->Cell(25, 10, $row["star"], 1, 0, 'C');
        $pdf->MultiCell(65, 10, $row["query3"], 1, 'L');
    }

    // Output PDF
    $pdf->Output('D', 'inquiry_follow_up.pdf');
    exit;
}


// PDF generation function
// function generatePDF($result) {
//     $pdf = new PDF();
//     $pdf->AliasNbPages();
//     $pdf->AddPage();
//     $pdf->SetFont('Arial','',12);

//     // Add table header
//     $pdf->SetFillColor(230, 230, 230);
//     $pdf->SetFont('Arial', 'B', 12);
//     $pdf->Cell(30, 10, 'Name', 1, 0, 'C', true);
//     $pdf->Cell(30, 10, 'Email', 1, 0, 'C', true);
//     $pdf->Cell(30, 10, 'Phone', 1, 0, 'C', true);
//     $pdf->Cell(25, 10, 'Star Rating', 1, 0, 'C', true); // Adjusted width for Star Rating
//     $pdf->Cell(65, 10, 'Query', 1, 0, 'C', true); // Adjusted width for Query
//     $pdf->Cell(80, 10, 'Action', 1, 1, 'C', true); // Adjusted width for Action

//     // Add table rows
//     $pdf->SetFont('Arial', '', 10);
//     while($row = $result->fetch_assoc()) {
//         $pdf->Cell(30, 10, $row["name3"], 1, 0, 'L');
//         $pdf->Cell(30, 10, $row["email3"], 1, 0, 'L');
//         $pdf->Cell(30, 10, $row["phone3"], 1, 0, 'L');
//         $pdf->Cell(25, 10, $row["star"], 1, 0, 'C'); // Adjusted width for Star Rating
//         $pdf->MultiCell(65, 10, $row["query3"], 1, 'L'); // Adjusted width for Query
//         $pdf->Cell(80, 10, '', 1, 1); // Empty cell for Action
//     }

//     // Output PDF
//     $pdf->Output('D','inquiry_follow_up.pdf');
//     exit;
// }

// Handle download PDF request
if(isset($_POST['download_pdf'])) {
    generatePDF($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inquiry Follow Up</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome for icons -->
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

    .container {
        /* max-width: 1400px; */
        width: 80%;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        position: fixed;
        left:190px;
    }

    h2 {
        color: #333;
        text-align: center;
    }

    form {
        margin-bottom: 20px;
        text-align: center;
    }

    select {
        padding: 8px;
        font-size: 16px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
        text-align: left;
    }

    tr:hover {
        background-color: #f9f9f9;
    }

    .edit-btn, .delete-btn, .messages-btn {
        padding: 8px 12px;
        margin: 0 4px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .edit-btn {
        background-color: #4CAF50;
        color: #fff;
    }

    .delete-btn {
        background-color: #FF0000;
        color: #fff;
    }

    .messages-btn {
        background-color: #2196F3;
        color: #fff;
    }

    .action-buttons a {
        margin-right: 10px;
    }

    .action-buttons a:last-child {
        margin-right: 0;
    }

    .download-pdf-btn {
        padding: 10px 20px;
        background-color: #333;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .download-pdf-btn:hover {
        background-color: #555;
    }

    .edit-btn, .delete-btn, .messages-btn {
        text-decoration: none; /* Remove underline */
        display: inline-block; /* Make the buttons display inline */
        margin-right: 1px;
    }

    .action-buttons a {
    display: inline-block;
    margin-right: 10px;
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
    <a href="change_password_em.php?emp_id=<?php echo $emp_id; ?>">Change Password</a>
    <a href="logout_employee.php">Logout</a>
</div>


<div class="container2">
    <div class="navbar">
        <a href="http://localhost/Sem-8%20Project%20Final/login.php">Login</a>
        <a href="http://localhost/Sem-8%20Project%20Final/employee_login.php">Employee Login</a>
        <a href="http://localhost/Sem-8%20Project%20Final/inquiry.php">Inquiry</a>
        <!-- <a href="#contact">Contact</a> -->
    </div>
</div>
<br>
<br>
<br>
<br>

<div class="container">
    <h2>Inquiry Follow Up</h2>

    <form method="GET">
        <select id='gMonth2' name="month" onchange="submitForm()">
            <option value=''>--Select Month--</option>
            <option value='1' <?php if ($selected_month == '1') echo 'selected="selected"'; ?>>January</option>
            <option value='2' <?php if ($selected_month == '2') echo 'selected="selected"'; ?>>February</option>
            <option value='3' <?php if ($selected_month == '3') echo 'selected="selected"'; ?>>March</option>
            <option value='4' <?php if ($selected_month == '4') echo 'selected="selected"'; ?>>April</option>
            <option value='5' <?php if ($selected_month == '5') echo 'selected="selected"'; ?>>May</option>
            <option value='6' <?php if ($selected_month == '6') echo 'selected="selected"'; ?>>June</option>
            <option value='7' <?php if ($selected_month == '7') echo 'selected="selected"'; ?>>July</option>
            <option value='8' <?php if ($selected_month == '8') echo 'selected="selected"'; ?>>August</option>
            <option value='9' <?php if ($selected_month == '9') echo 'selected="selected"'; ?>>September</option>
            <option value='10' <?php if ($selected_month == '10') echo 'selected="selected"'; ?>>October</option>
            <option value='11' <?php if ($selected_month == '11') echo 'selected="selected"'; ?>>November</option>
            <option value='12' <?php if ($selected_month == '12') echo 'selected="selected"'; ?>>December</option>
        </select>
        <input type="hidden" name="name" value="<?php echo htmlspecialchars($name17); ?>">
    </form>

    <table>
        <thead>
            <tr>
                <th style="width: 130px;">Name</th>
                <th style="width: 150px;">Email</th>
                <th style="width: 120px;">Phone</th>
                <th style="width: 70px;">Star Rating</th>
                <th style="width: 250px;">Query</th>
                <th class="action-buttons" style="width: 400px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["name3"]. "</td>";
                    echo "<td>" . $row["email3"]. "</td>";
                    echo "<td>" . $row["phone3"]. "</td>";
                    echo "<td>" . $row["star"]. "</td>";
                    echo "<td>" . $row["query3"]. "</td>";
                    echo "<td class='action-buttons'>";
                    // echo "<a class='edit-btn' href='edit.php?user_id=" . $row["id3"] . "&query_id=" . $row["query_id"] . "&emp_id=". $emp_id2 ."'><i class='fas fa-edit'></i> Edit</a>";
                    $customer_id = $row["id3"]; // Assuming you have a column named 'customer_id' in your table

                    // Query to fetch the name of the customer based on their ID
                    $name_query = "SELECT name3 FROM inquiry_details WHERE id3 = $customer_id"; // Adjust the table name and column names as per your database schema
                    $name_result = $conn->query($name_query);

                    if ($name_result && $name_result->num_rows > 0) {
                        $customer_name = $name_result->fetch_assoc()["name3"];
                        echo "<a class='edit-btn' href='edit.php?user_id=" . $row["id3"] . "&query_id=" . $row["query_id"] . "&emp_id=". $emp_id . "&name=" . $customer_name . "&month=" . $selected_month;

                    // Check if a month is selected
                        if (!empty($selected_month)) {
                            // Include the month parameter in the URL
                            echo "&month=" . $selected_month;
                        }

                        echo "'><i class='fas fa-edit'></i> Edit</a>";
                    } else {
                        echo "Error: Unable to fetch customer name.";
                    }

                    // echo "<a class='delete-btn' href='delete.php?user_id=" . $row["id3"] . "&query_id=" . $row["query_id"] . "'><i class='fas fa-trash'></i> Delete</a>";
                    echo "<a class='delete-btn' href='delete.php?user_id=" . $row["id3"] . "&query_id=" . $row["query_id"] . "'><i class='fas fa-trash'></i> Delete</a>";
                    // echo "<a class='messages-btn' href='messages.php?user_id=" . $row["id3"] . "&query_id=" . $row["query_id"] . "&name=" . urlencode($name17);
                    // $selected_month=$_GET['month'];
                    // if (!empty($selected_month)) {
                    //     echo "&month=$selected_month"; // Append the selected month if available
                    // }
                    // echo "'><i class='fas fa-envelope'></i> Messages</a>";
                    // echo "<a class='messages-btn' href='messages.php?user_id=" . $row["id3"] . "&query_id=" . $row["query_id"] . "&name=" . urlencode($name17) 
                    echo "<a class='messages-btn' href='messages.php?user_id=" . $row["id3"] . "&query_id=" . $row["query_id"] . "&name=" . urlencode($name17) . "&emp_id=" . $emp_id . "";

                    // echo "<a class='delete-btn' href='messages.php?user_id=" . $row["id3"] . "&query_id=" . $row["query_id"] . "&emp_id=". $emp_id ."'><i class='fas fa-trash'></i> Messages</a>";
                    if (!empty($selected_month)) {
                        echo "&month=$selected_month"; // Append the selected month if available
                    }
                    echo "'><i class='fas fa-envelope'></i> Messages</a>";

                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No data available</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <form method="post">
        <button type="submit" class="download-pdf-btn" name="download_pdf"><i class='fas fa-download'></i> Download PDF</button>
    </form>
</div>

<script>
function submitForm() {
    // Get selected month value
    var selectedMonth = document.getElementById('gMonth2').value;

    // Get name17 and emp_id values
    var name17 = "<?php echo htmlspecialchars($name17); ?>";
    var emp_id = "<?php echo $emp_id; ?>";

    // Construct the new URL with all parameters
    // var url = 'http://example.com/page.php?month=' + selectedMonth + '&name=' + encodeURIComponent(name17) + '&emp_id=' + $emp_id;
    var url = 'http://localhost/Sem-8%20Project%20Final/follow_up.php?month=' + selectedMonth + '&name=' + encodeURIComponent(name17) + '&emp_id=' + emp_id;


    // Redirect to the new URL
    window.location.href = url;
}
</script>

</body>
</html>
