<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Select time slot</title>

   <link rel="stylesheet" href="../css/style.css">

   <?php
      session_start();

      $host = "localhost";
      $dbusername = "root";
      $dbpassword = "";
      $dbname = "healthConsultancy";

      $conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

      $patID = 458;

      if(isset($_POST['submit'])){
        $type = $_POST['type'];
        $date = $_POST['date'];
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
<h1> Health Consultancy</h1>
<div class="form-container">
<form name="cancel" action="../php/book.php" method="POST">
  <table>
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

</div>

</body>
</html>