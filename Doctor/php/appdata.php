<?php
// Start session
session_start();
$docID = $_SESSION["doc_id"];
// Assuming you have a database connection established
$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "healthConsultancy";
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get patient ID from session


// Perform SQL query to fetch appointment data from your database
$today_date = date("Y-m-d");
$timeSlot_array = array();
$past = array();

$result = mysqli_query($conn, "SELECT ad.AppointmentID, ad.date, p.PatientsName, a.TimeSlotID,d.UID FROM appointmentdetails AS ad INNER JOIN patients AS p ON ad.UID = p.UID INNER JOIN Appointment AS a ON ad.AppointmentID = a.ID INNER JOIN doctoravailability as d ON d.DoctorName = ad.DoctorName WHERE d.UID = $docID ORDER BY ad.AppointmentID ASC;");

if ($result) {
    while ($rows = mysqli_fetch_array($result)) {
        $timeSlot_array[] = $rows['TimeSlotID'];

    }
} else {
    echo ("Error: " . mysqli_error($conn));
}


foreach ($timeSlot_array as $timeSlot) {
    $select1 = "SELECT * FROM TimeSlots WHERE ID = '$timeSlot'";
    $result = mysqli_query($conn, $select1);
    if ($result) {
        while ($rows = mysqli_fetch_array($result)) {
            $apptDate = substr($rows['SlotDateTimeStart'], 0, 10);
            if ($apptDate < $today_date) {
                $past[] = $rows['ID'];

            }
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

$appointments = array();

foreach ($past as $upSlot) {
    $select2 = "SELECT ad.AppointmentID, ad.date, p.PatientsName FROM appointmentdetails AS ad INNER JOIN patients AS p ON ad.UID = p.UID INNER JOIN Appointment AS a ON ad.AppointmentID = a.ID WHERE a.TimeSlotID = $upSlot";

    // Execute the SQL query
    $result = mysqli_query($conn, $select2);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Check if the row is already in $appointments
            $alreadyExists = false;
            foreach ($appointments as $existingRow) {
                if ($existingRow['AppointmentID'] == $row['AppointmentID']) {
                    $alreadyExists = true;
                    break;
                }
            }
            // If the row is not already in $appointments, add it
            if (!$alreadyExists) {
                $appointments[] = $row;
            }
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}



// Output the appointments as JSON
header('Content-Type: application/json');
echo json_encode($appointments);

// Close the database connection
mysqli_close($conn);
