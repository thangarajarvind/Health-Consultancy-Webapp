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

$sql = "SELECT ServiceID, Name FROM Service";
$result = $conn->query($sql);

$services = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

// Output JSON response
header('Content-Type: application/json');
echo json_encode($services);

$conn->close();
?>