<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to sanitize and validate input
function sanitize_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and validate user inputs
    $firstname = sanitize_input($_POST['FirstName']);
    $lastname = sanitize_input($_POST['LastName']);
    $email = sanitize_input($_POST['Email']);
    $phone = sanitize_input($_POST['Phone']);
    
    // Check if any of the required fields are empty
    if (empty($firstname) || empty($lastname) || empty($email) || empty($phone)) {
        echo "<script>alert('Please fill out all fields.'); window.location.href = 'index.php';</script>";
        exit(); // Exit script if validation fails
    }
    if (strlen($phone)!=10 || strlen($firstname)<2 || strlen($firstname)<2 ) {
        echo "<script>alert('Please enter valid information.'); window.location.href = 'index.php';</script>";
        exit(); // Exit script if validation fails
    }

    // Further validation for email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.location.href = 'index.php';</script>";
        exit(); // Exit script if validation fails
    }
    if (!filter_var($email, FILTER_SANITIZE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.location.href = 'index.php';</script>";
        exit(); // Exit script if validation fails
    }

    $domain = substr($email, strpos($email, ".") + 1);


    if (strlen($domain)<2) {
        echo "<script>alert('Invalid email format.'); window.location.href = 'index.php';</script>";
        exit(); // Exit script if validation fails
    }


    // Validate first name and last name format
    if (!preg_match("/^[a-zA-Z\-]+$/", $firstname) || !preg_match("/^[a-zA-Z\-]+$/", $lastname)) {
        echo "<script>alert('First and last names should contain only letters and hyphens.'); window.location.href = 'index.php';</script>";
        exit(); // Exit script if validation fails
    }

    // Check for SQL injection keywords in inputs
    $sql_keywords = array("SELECT", "INSERT", "UPDATE", "DELETE", "DROP", "ALTER", "CREATE");
    $input_str = $firstname . " " . $lastname . " " . $email . "" . $phone;
    foreach ($sql_keywords as $keyword) {
        if (stripos($input_str, $keyword) !== false) {
            echo "<script>alert('Invalid input detected.'); window.location.href = 'index.php';</script>";
            exit(); // Exit script if validation fails
        }
    }

    // Connect to your SQL Server database
    $serverName = "floralwifi.database.windows.net"; // Server name
    $connectionOptions = array(
        "Database" => "floralwifidb", // Database name
        "Uid" => "wifisa", // Your username
        "PWD" => "BlueTree13#" // Your password
    );

    // Establish the connection
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    // Check connection
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $keywords = array("condrain", "metrus", "dg", "deg", "strada", "aspen" );
    $tableName = 'twelve'; // Default table
    $ttl = 12; // Default TTL
    foreach ($keywords as $keyword) {
        if (stripos($email, $keyword) !== false) {
            $tableName = 'oneTwenty';
            $ttl = 120;
            break;
        }
    }


// Select a token from the appropriate table
    $sql = "SELECT TOP 1 PrimaryKey, Token FROM $tableName";
    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
        echo "<script>alert('Error executing SQL query: " . print_r(sqlsrv_errors(), true) . "'); window.location.href = 'index.php';</script>";
        
        exit(); // Exit script if query fails
    }

    // Fetch the Token value and store it in a variable
    if ($obj = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $tokenValue = $obj['Token'];
        $tokenPrimaryKey = $obj['PrimaryKey'];
    } else {
        echo "<script>alert('No records found.'); window.location.href = 'index.php';</script>";
        exit(); // Exit script if no records found
    }


    $currentDate = date('Y-m-d H:i:s');

    // Insert user input into the Customers table
    $sql = "INSERT INTO Customer (FirstName, LastName, Email, Phone, Date, TTL, Token) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $params = array($firstname, $lastname, $email, $phone, $currentDate, $ttl, $tokenValue);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Error in statement execution: " . print_r(sqlsrv_errors(), true));
        echo "<script>alert('Error executing SQL insert query: ".print_r(sqlsrv_errors(), true)."'); window.location.href = 'index.php';</script>";
        exit(); // Exit script if query fails
    }

  // Delete the token from the appropriate table
  $deleteSql = "DELETE FROM $tableName WHERE PrimaryKey = ?";
  $deleteParams = array($tokenPrimaryKey);
  $deleteStmt = sqlsrv_query($conn, $deleteSql, $deleteParams);

  if ($deleteStmt === false) {
      echo "<script>alert('Error deleting token'); window.location.href = 'index.php';</script>";
      exit(); // Exit script if delete fails
  }

  sqlsrv_close($conn);

  
    // Output the generated success content
    $successContent = "<html>
<head>
<title>Submission Successful</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        text-align: center;
    }
    h1 {
        color: #d61e48;
        font-size: 36px;
        font-weight: bold;
    }
    strong {
        font-size: 48px;
    }
</style>
</head>
<body>
<div>
    <h1>Your submission was successful!</h1>
    <p>Here is your WiFi token: <strong>$tokenValue</strong></p>
</div>
</body>
</html>";

    echo $successContent;
}
?>
