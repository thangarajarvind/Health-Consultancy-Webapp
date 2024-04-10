<?php

$pat_id = '458';

$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "healthConsultancy";

$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

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
            else{
                echo 0;
            }
        }
    }
}
foreach($upcoming as $upSlot){
    echo $appt;
    $select = " SELECT * FROM Appointment WHERE TimeSlotID = '$upSlot' ";
    $result = mysqli_query($conn, $select);

    while($row = mysqli_fetch_array($result)){
        echo "<tr><td><input type='radio' name='timeslotID' value=".$row['ID'].">&nbsp;</td>";
        echo "<td>".$row['ID']."</td>";
        echo "<br>";
        echo "<td>".$row['PatientID']."</td>";
        echo "<br>";
        echo "<td>".$row['TimeSlotID']."</td>";
    }

}
?>