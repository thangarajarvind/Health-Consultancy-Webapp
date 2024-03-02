<head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>

<?php
session_start();

$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "healthConsultancy";

$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

require '/Applications/XAMPP/xamppfiles/lib/php/PHPMailer/src/Exception.php';
require '/Applications/XAMPP/xamppfiles/lib/php/PHPMailer/src/PHPMailer.php';
require '/Applications/XAMPP/xamppfiles/lib/php/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['submit'])){
    $slot_id = $_POST['timeslotID'];

    if($slot_id == null){
     echo '.';
     pop("Please select a time slot","error","../html/cancel_slot.php","");
    }

    $select = " SELECT * FROM TimeSlots WHERE ID = '$slot_id' ";

    $result = mysqli_query($conn, $select);

   if(mysqli_num_rows($result) > 0){
     $row = mysqli_fetch_array($result);
     if($row['IsAvailable'] == 0){
          $appointment = $row['SlotDateTimeStart'];

          $selectAppointment = " SELECT * FROM Appointment WHERE TimeSlotID = '$slot_id' ";
          $resultAppointment = mysqli_query($conn, $selectAppointment);

          $row = mysqli_fetch_array($resultAppointment);
          $pat_id = $row['PatientID'];

          $selectPat = " SELECT * FROM Patients WHERE UID = '$pat_id' ";
          $resultPatient = mysqli_query($conn, $selectPat);

          $row = mysqli_fetch_array($resultPatient);
          $pat_email = $row['PatientEmail'];
          $pat_name = $row['PatientsName'];

          $DELETE = "DELETE FROM TimeSlorts WHERE ID = '$slot_id' ";

          //$q_result1 = $conn->query($DELETE);

          $mail = new PHPMailer();
          $mail->isSMTP();
          $mail->Host = 'sandbox.smtp.mailtrap.io';
          $mail->SMTPAuth = true;
          $mail->Port = 2525;
          $mail->Username = '34fca8e46805cf';
          $mail->Password = 'aea572039bfb25';

          $mail->IsHTML(true);
          $mail->AddAddress($pat_email, $pat_name);
          $mail->SetFrom("healthConsult@gmail.com", "Health Consultancy");
          $mail->AddReplyTo("healthConsult@gmail.com", "Health Consultancy");
          $mail->Subject = "Reg:Cancellation of Appointment";
          $content = "<b>Dear ".$pat_name.",<br><br>

               I hope this message finds you well.<br><br>
          
               I regret to inform you that your scheduled appointment for ".$appointment." has been canceled due to unforeseen circumstances. Unfortunately, the doctor is unable to attend to appointments during this time.<br><br>
          
               We apologize for any inconvenience this may cause you. Your health and well-being remain our top priority, you can reschedule any other available appointment time slots at your convenience.<br><br>
          
               Please accept our sincerest apologies for the inconvenience.<br><br>
          
          Thank you for your understanding and cooperation.</b>";

          $mail->MsgHTML($content); 


          if(!$mail->Send()) {
          echo "Error while sending Email.";
          var_dump($mail);
          } else {
               echo '.';
               pop("Slot removed","success","../html/cancel_slot.php","Appointment was cancelled");
          }
      }
      else{
          $DELETE = "DELETE FROM TimeSlorts WHERE ID = '$slot_id' ";

          //$q_result1 = $conn->query($DELETE);

          echo '.';
          pop("Slot removed","success","../html/cancel_slot.php","");

      }
        
   }
   else{
        echo "<script>alert('Time Slot missing')";
   }
}

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

?>