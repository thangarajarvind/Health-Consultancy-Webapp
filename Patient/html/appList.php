<?php

session_start();

$pat_id = $_SESSION['pat_id'];

?>

<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">
    <meta name="viewport"
          content="width=device-width,
                   initial-scale=1.0">
    <title>Health Consultancy</title>
    <link rel="icon" type="image/x-icon" href="../css/doctor_favicon.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/doctor.css" type="text/css">
    
    <style>
    	body {
    		font-family: Arial, Helvetica, sans-serif;
    	}
    </style>

<?php
      

      if (empty($_SESSION['logged_in'])){
        echo '.';
        pop("You are not logged in","error","../html/login.html","");

        function pop($t, $i, $l, $x){
            echo "<script type='text/javascript'>";
            echo "Swal.fire({
                title: '$t',
                text: '$x',
                icon: '$i',   
                confirmButtonColor: '#55c2da',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '$l'
                }
            });";
            echo "</script>";
        }
      }

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
   ?>
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
        <a href="search.html">Book Appointment</a>
        <a class="active" href="appList.php">View Appointment</a>
        <a href="../../Reg_Login/php/logout.php">Log Out</a>
        
    </div>
    <section class="dashboard">
      <h2>Upcoming Appointments</h2>
      <br><br>
      <table id="appointment" class="users-table">
      <form name="cancel" action="../php/cancel_slot.php" method="POST">
          <thead>
                <tr>
                    <th></th>
                    <th>Appointment ID</th>
                    <th>Patient ID</th>
                    <th>Time Slot ID</th>                 
                </tr>
            </thead>
            <tbody>
            <?php

            foreach($upcoming as $upSlot){
                echo $appt;
                $select = " SELECT * FROM Appointment WHERE TimeSlotID = '$upSlot' ";
                $result = mysqli_query($conn, $select);

                while($row = mysqli_fetch_array($result)){
                    echo "<tr><td><input type='radio' name='timeslotID' value=".$row['ID'].">&nbsp;</td>";
                    echo "<td>".$row['ID']."</td>";
                    echo "<td>".$row['PatientID']."</td>";
                    echo "<td>".$row['TimeSlotID']."</td>";
                }

            }
            
                ?>
                <!-- Add more rows with random data -->
            </tbody>
        </table>
        <input type="submit" name="submit" value="Cancel">
            </form>
      </section>