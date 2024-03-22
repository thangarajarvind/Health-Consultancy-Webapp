<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Appointment Slot</title>
    <link rel="stylesheet" href="../css/doctor.css">
    <style>
        /* Background image styling */

    </style>
</head>
<body>
<?php
// Include config.php for database connection
include('config.php');

session_start();
$doctor_id = $_SESSION['doc_id'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all form fields are set and not empty
    if (isset($_POST['date']) && isset($_POST['available_from']) && isset($_POST['available_to']) 
        && !empty($_POST['date']) && !empty($_POST['available_from']) && !empty($_POST['available_to'])) {
        
        $date = $_POST['date'];
        $available_from = $_POST['available_from'];
        $available_to = $_POST['available_to'];

        // Check if the slot already exists
        $sql_check = "SELECT * FROM Availability 
                      WHERE Date = '$date' 
                      AND AvailableFrom = '$available_from' 
                      AND AvailableTo = '$available_to'
                      AND DoctorID = '$doctor_id'";

        $result_check = mysqli_query($conn, $sql_check);
        
        if (mysqli_num_rows($result_check) > 0) {
            echo "Cannot create multiple slots for the given time for this doctor";
        } else {
            // Insert the appointment slot into the database
            $sql_insert = "INSERT INTO Availability (Date, AvailableFrom, AvailableTo, DoctorID) 
                           VALUES ('$date', '$available_from', '$available_to', '$doctor_id')";

            if (mysqli_query($conn, $sql_insert)) {
                echo "Appointment slot created successfully";
            } else {
                echo "Error: " . $sql_insert . "<br>" . mysqli_error($conn);
            }
        }

    } else {
        echo "All form fields are required";
    }
}
mysqli_close($conn);
include('createtimeslots.php');
?>
</body>
</html>
