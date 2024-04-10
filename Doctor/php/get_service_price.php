<?php
// Database connection
$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "healthConsultancy";

$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch price of selected service from the database
$serviceID = $_GET['serviceID'];
$sql = "SELECT Cost FROM Service WHERE ServiceID = $serviceID";
$result = $conn->query($sql);

$response = array();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response['price'] = $row['Cost'];
}

echo json_encode($response);

$conn->close();
?>
