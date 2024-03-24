<?php

$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "healthConsultancy";

session_start();

$pat_id = $_SESSION['pat_id'];

$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

$apptID = $_GET['appointmentID'];

$data['appointmentID'] = $apptID;

$select = " SELECT * FROM Appointment WHERE ID = '$apptID' ";
$result = mysqli_query($conn, $select);

if (mysqli_num_rows($result) > 0) {
    // Fetch each row and add it to the appointments array
    while ($row = mysqli_fetch_assoc($result)) {
        $upSlot = $row['TimeSlotID'];
        $selectSlot = " SELECT * FROM TimeSlots WHERE ID = '$upSlot' ";
        $resultSlot = mysqli_query($conn, $selectSlot);
        while($rowslot = mysqli_fetch_array($resultSlot)){
            $data['date'] = substr($rowslot['SlotDateTimeStart'],0,10);
            $data['startTime'] = substr($rowslot['SlotDateTimeStart'],11,16);
            $data['endTime'] = substr($rowslot['SlotDateTimeEnd'],11,16);
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
            $data['doctorName'] = $rowDocName['DoctorName'];
        }
        $SympSelect = " SELECT * FROM patientSymptoms WHERE ApptID = '$apptID' ";
        $resultSymp = mysqli_query($conn, $SympSelect);
        while($rowSymp = mysqli_fetch_array($resultSymp)){
            $data['symptoms'] = $rowSymp['Symptom'];
        }
    }
}

echo json_encode($data);
?>