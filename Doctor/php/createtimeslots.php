<!DOCTYPE html>
<html lang="en">
 
<head>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible"
               content="IE=edge">
     <meta name="viewport"
               content="width=device-width,
                    initial-scale=1.0">
     <title>Health Consultancy</title>
     <link rel="icon" type="image/x-icon" href="../css/doctor_favicon.png">
     
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
     <link rel="stylesheet" href="../css/doctor.css" type="text/css">
     
     <style>
          body {
               font-family: Arial, Helvetica, sans-serif;
          }
     </style>
</head>
<body>
  <div class="nav" id="mynavbar">
        <a href="">Health Consultancy</a>
        <div class="nav-right" id="navbar-right">
            <a href="../../Reg_Login/php/logout.php">Logout</a>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </div>
    <div class="sidenav" id="mySidenav">
        <br>
        <a class="active" href="../html/createtimeslot.html">Create Time Slot</a>
        <a href="slots.php">Cancel Time Slot</a>
        <a href="../../Reg_Login/php/logout.php">Log Out</a>
        
    </div>
<?php
include('config.php');

session_start();
$doctor_id = $_SESSION['doc_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['date']) && isset($_POST['available_from']) && isset($_POST['available_to']) 
        && !empty($_POST['date']) && !empty($_POST['available_from']) 
        && !empty($_POST['available_to'])) {
        
        $date = $_POST['date'];
        $available_from = $_POST['available_from'];
        $available_to = $_POST['available_to'];

        // Fetching the AvailabilityID from the Availability table
$sql_availability_id = "SELECT AvailabilityID FROM Availability 
                        WHERE AvailableFrom = '$available_from' 
                        AND AvailableTo = '$available_to'
                        AND Date = '$date'
                        AND DoctorID = '$doctor_id'";
$result_availability_id = mysqli_query($conn, $sql_availability_id);

if ($result_availability_id) {
    $availability_id_row = mysqli_fetch_assoc($result_availability_id);
    $availability_id = $availability_id_row['AvailabilityID'];
} else {
    echo "Error fetching AvailabilityID: " . mysqli_error($conn);
    // Handle the error accordingly
}


        $sql_check = "SELECT * FROM TimeSlots 
                      WHERE SlotDateTimeStart = '$available_from' 
                      AND SlotDateTimeEnd = '$available_to'
                      AND DoctorID = '$doctor_id'";
        
        $result_check = mysqli_query($conn, $sql_check);
        
        if (mysqli_num_rows($result_check) > 0) {
            echo "Cannot create multiple slots for the given time for this doctor";
        } else {
            // Define the divideTimeSlots function
            function divideTimeSlots($date, $start, $end, $duration) {
                $start = new DateTime($date . ' ' . $start);
                $end = new DateTime($date . ' ' . $end);
                $interval = new DateInterval('PT' . $duration . 'M');
                $slots = array();

                while ($start < $end) {
                    $slotEnd = clone $start;
                    $slotEnd->add($interval);
                    $slots[] = array(
                        'start' => $start->format('Y-m-d H:i:s'),
                        'end' => $slotEnd->format('Y-m-d H:i:s')
                    );
                    $start = $slotEnd;
                }

                return $slots;
            }

            // Use divideTimeSlots function to generate time slots
            $timeSlots = divideTimeSlots($date ,$available_from, $available_to, 60); // Assuming each slot is 60 minutes

            foreach ($timeSlots as $slot) {
                $startTime = $slot['start'];
                $endTime = $slot['end'];

                $insertSlot = "INSERT INTO TimeSlots (SlotDateTimeStart, SlotDateTimeEnd, DoctorID, AvailabilityID, IsAvailable) VALUES ('$startTime', '$endTime', $doctor_id, $availability_id, '1')";
                $result = $conn->query($insertSlot);
            }

            if ($result) {
                echo "Appointment slots created successfully";
                pop("Time slots created","success","../html/createtimeslot.html","");
            } else {
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
                echo "Error: Appointment slot creation failed";
                echo $conn->error;
            }
        }

    } else {
        echo "All form fields are required";
    }
}

$sql = "SELECT * FROM TimeSlots";
$result = $conn->query($sql);


mysqli_close($conn);
?>
</body>
</html>

<?php

function pop($t, $i, $l, $x){
     echo "<script type='text/javascript'>";
     echo "Swal.fire({
          title: '$t',
          text: '$x',
          icon: '$i',   
          confirmButtonColor: '#1f5fa0',
     }).then((result) => {
          if (result.isConfirmed) {
               window.location.href = '$l'
          }
     });";
     echo "</script>";
}

?>