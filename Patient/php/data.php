<?php
// Assuming you have a database connection established

$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "healthConsultancy";

session_start();

$pat_id = $_SESSION['pat_id'];

$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

// Perform SQL query to fetch appointment data from your database
// For example:
$today_date = date("Y-m-d");

    $timeSlot_array = array();
    $upcoming = array();
    
    $result = mysqli_query($conn ," SELECT * FROM Appointment WHERE PatientID = '$pat_id' ");

    while($rows = mysqli_fetch_array($result)){
        $timeSlot_array[] = $rows['TimeSlotID'];
    }

    foreach($timeSlot_array as $timeSlot){
        $select = " SELECT * FROM TimeSlots WHERE ID = '$timeSlot' ";
        $result = mysqli_query($conn, $select);
        if(mysqli_num_rows($result) > 0){
            while($rows = mysqli_fetch_array($result)){
                $apptDate = substr($rows['SlotDateTimeStart'],0,10);
                if($apptDate >= $today_date){
                    $upcoming[] = $rows['ID'];
                }
            }
        }
    // Convert the array to JSON format and output it
}

$appointments = array();

foreach($upcoming as $upSlot){

    $select = " SELECT * FROM Appointment WHERE TimeSlotID = '$upSlot' ";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        // Fetch each row and add it to the appointments array
        while ($row = mysqli_fetch_assoc($result)) {
            $selectSlot = " SELECT * FROM TimeSlots WHERE ID = '$upSlot' ";
            $resultSlot = mysqli_query($conn, $selectSlot);
            while($rowslot = mysqli_fetch_array($resultSlot)){
                $aptDate = substr($rowslot['SlotDateTimeStart'],0,10);
                $aval_id = $rowslot['AvailabilityID'];
            }
            $docIDSelect = " SELECT * FROM Availability WHERE AvailabilityID = '$aval_id' ";
            $resultdocID = mysqli_query($conn, $docIDSelect);
            while($rowDocID = mysqli_fetch_array($resultdocID)){
                $doc_ID = $rowDocID['DoctorID'];
            }
            $docNameSelect = " SELECT * FROM DoctorAvailability WHERE UID = '$doc_ID' ";
            $resultdocName = mysqli_query($conn, $docNameSelect);
            while($rowDocName = mysqli_fetch_array($resultdocName)){
                $docName = $rowDocName['DoctorName'];
            }
            $row['apptDate'] = $aptDate;
            $row['DocName'] = $docName;
            $appointments[] = $row;
        }

        // Convert the array to JSON format and output it
    }
}
echo json_encode($appointments);
// Close the database connection
mysqli_close($conn);
?>
