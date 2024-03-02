<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login form</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/style.css">

   <?php
      session_start();

      $host = "localhost";
      $dbusername = "root";
      $dbpassword = "";
      $dbname = "healthConsultancy";

      $conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

      $docID = 123;

      $result = mysqli_query($conn ," SELECT * FROM TimeSlots WHERE DoctorID = '$docID' ");
   ?>
</head>
<body>
<h1> Health Consultancy</h1>
<div class="form-container">
<form name="cancel" action="../php/cancel_slot.php" method="POST">
  <table>
    <thead>
      <tr>
        <th></th>
        <th>TimeSlotID</th>
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
    </tbody>
  </table>
  <input type="submit" name="submit" value="cancel" class="form-btn">
</form>

</div>

</body>
</html>