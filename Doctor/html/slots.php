<?php
session_start();
$doc_id = $_SESSION['doc_id'];
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
   
      $result = mysqli_query($conn ," SELECT * FROM TimeSlots WHERE DoctorID = '$doc_id' ");
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
        <a href="#">Create Time Slot</a>
        <a class="active" href="slots.php">Cancel Time Slot</a>
        <a href="../../Reg_Login/php/logout.php">Log Out</a>
        
    </div>
    <section class="dashboard">
      <h2>Cancel Time Slots</h2>
      <br><br>
      <table id="appointment" class="users-table">
      <form name="cancel" action="../php/cancel_slot.php" method="POST">
          <thead>
                <tr>
                    <th></th>
                    <th>TimeSlot ID</th>
                    <th>Slot start time</th>
                    <th>Slot end time</th>
                    <th>Booked by patient?</th>
                    
                </tr>
            </thead>
            <tbody>
            <?php

                while($row = mysqli_fetch_array($result)){
                    echo "<tr><td><input type='radio' name='timeslotID' value=".$row['ID'].">&nbsp;</td>";
                    echo "<td>".$row['ID']."</td>";
                    echo "<td>".$row['SlotDateTimeStart']."</td>";
                    echo "<td>".$row['SlotDateTimeEnd']."</td>";
                    if($row['IsAvailable'] == '1'){
                    echo "<td style='color:green;'>No</td></tr>";
                    }
                    if($row['IsAvailable'] == '0'){
                    echo "<td style='color:red;'>Yes</td></tr>";
                    }
                }
            
                ?>
                <!-- Add more rows with random data -->
            </tbody>
        </table>
        <input type="submit" name="submit" value="Cancel">
            </form>
      </section>