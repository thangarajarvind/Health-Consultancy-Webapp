<?php
session_start();
$appointmentID = $_SESSION["appt_id"];
$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "healthConsultancy";
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
if (!$conn) {
    echo "database not connected";
}
// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the JSON data sent from the client
    $json_data = file_get_contents('php://input');
    // Decode JSON data into a PHP array
    $data = json_decode($json_data, true);

    foreach ($data as $row) {
        // Process and insert the data into the database (replace with your database logic)

        $medicineName = $row['medicineName'];
        $amount = $row['amount'];
        $description = $row['description'];
        $morning = $row['morning'];
        $afternoon = $row['afternoon'];
        $night = $row['night'];

        if ($amount <= 0 || $medicineName == null || $description == null) {
            echo "Please fill out all required fields";
            //header("Location: http://localhost/healthConsultancy/php/prescribe.php?appointmentID=$appointmentID", true, 301);
            exit; // Exit the script to prevent further execution
        } else {
            $sql = "INSERT INTO prescription (ApptId, Medicine, Count, Descripti, Morning, Afternoon, Night) VALUES ('$appointmentID', '$medicineName', '$amount', '$description', '$morning', '$afternoon', '$night')";

            if (mysqli_query($conn, $sql)) {
                echo "Prescription Updated Successfully";
                header('../html/viewAppointment.html');

                exit; // Exit the script to prevent further execution
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                exit; // Exit the script to prevent further execution
            }
        }
    }

    // Send a response back to the client indicating the success or failure of the operation

} else {
    // If the request method is not POST, return an error message
    echo "Error: Invalid request method";
}