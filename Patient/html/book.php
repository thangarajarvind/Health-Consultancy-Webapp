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
      session_start();

      $host = "localhost";
      $dbusername = "root";
      $dbpassword = "";
      $dbname = "healthConsultancy";

      $conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

      $patID = $_SESSION['pat_id'];

      if(isset($_POST['submit'])){
        $type = $_POST['type'];
        $date = $_POST['date'];

        $_SESSION['type'] = $type;
        $_SESSION['date'] = $date;
      }

      if($type == NULL || $date == NULL){
        $type = $_SESSION['type'];
        $date = $_SESSION['date'];
      }

      $select = " SELECT * FROM DoctorAvailability WHERE DoctorType = '$type' ";
      $result = mysqli_query($conn, $select);

      $doc_array = array();
      $aval_array = array();
      $slot_array = array();

      while($rows = mysqli_fetch_array($result)){
        $doc_array[] = $rows['UID'];
    }
    foreach($doc_array as $doc_id){
        $select = " SELECT * FROM Availability WHERE DoctorID = '$doc_id' AND Date = '$date' ";
        $result = mysqli_query($conn, $select);
        if(mysqli_num_rows($result) > 0){
            while($rows = mysqli_fetch_array($result)){
                $aval_array[] = $rows['AvailabilityID'];
            }
            foreach($aval_array as $aval){
                $select = " SELECT * FROM Timeslots WHERE AvailabilityID = '$aval' AND IsAvailable = 1";
                $result = mysqli_query($conn, $select);
                if(mysqli_num_rows($result) > 0){
                    while($rows = mysqli_fetch_array($result)){
                        $slot_array[] = $rows['ID'];
                    }
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
        <a class="active" href="search.html">Book Appointment</a>
        <a href="../../Reg_Login/php/logout.php">Log Out</a>
        
    </div>
    <section class="dashboard">
      <h2>Book Appointment</h2>
      <br><br>
      <table id="appointment" class="users-table">
      <form name="cancel" action="../php/book.php" method="POST">
    <thead>
      <tr>
        <th></th>
        <th>Doctor Name</th>
        <th>Slot start time</th>
        <th>Slot end time</th>
      </tr>
  </thead>
    <tbody>
        <?php
        foreach($slot_array as $slot){
            $select = " SELECT * FROM TimeSlots WHERE ID = '$slot' ";
            $result = mysqli_query($conn, $select);
            $row = mysqli_fetch_array($result);
            $doc_id = $row['DoctorID'];


            $select = " SELECT * FROM DoctorAvailability WHERE UID = '$doc_id' ";
            $result = mysqli_query($conn, $select);
            $row1 = mysqli_fetch_array($result);
            $doc_name = $row1['DoctorName'];

            echo "<tr><td><input type='radio' name='timeslotID' value=".$slot.">&nbsp;</td>";
            echo "<td>Dr.".$doc_name."</td>";
            echo "<td>".$row['SlotDateTimeStart']."</td>";
            echo "<td>".$row['SlotDateTimeEnd']."</td>";
        }
        ?>
    </tbody>
  </table>
  <input type="submit" name="submit" value="Book Appointment" class="form-btn">
</form>
      </section>