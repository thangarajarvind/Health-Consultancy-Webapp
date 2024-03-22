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
        <a href="#">Create Time Slot</a>
        <a class="active" href="slots.php">Cancel Time Slot</a>
        <a href="../../Reg_Login/php/logout.php">Log Out</a>
        
    </div>

<?php
session_start();

$slot_id = $_SESSION['slot_id'];

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
    $reason = $_POST['reason'];

    $select = " SELECT * FROM TimeSlots WHERE ID = '$slot_id' ";

    $result = mysqli_query($conn, $select);

    $row = mysqli_fetch_array($result);
     if($row['IsAvailable'] == 0){
          $appointment = $row['SlotDateTimeStart'];

          $selectAppointment = " SELECT * FROM Appointment WHERE TimeSlotID = '$slot_id' ";
          $resultAppointment = mysqli_query($conn, $selectAppointment);

          $row = mysqli_fetch_array($resultAppointment);
          $pat_id = $row['PatientID'];
          $ApptID = $row['ID'];

          $selectPat = " SELECT * FROM Patients WHERE UID = '$pat_id' ";
          $resultPatient = mysqli_query($conn, $selectPat);

          $row = mysqli_fetch_array($resultPatient);
          $pat_email = $row['PatientEmail'];
          $pat_name = $row['PatientsName'];

          $mail = new PHPMailer();
          $mail->isSMTP();
          $mail->Host = 'sandbox.smtp.mailtrap.io';
          $mail->SMTPAuth = true;
          $mail->Port = 2525;
          $mail->Username = '1bf9f9c4051f37';
          $mail->Password = 'df21c9e5fce7ca';

          $mail->IsHTML(true);
          $mail->AddAddress($pat_email, $pat_name);
          $mail->SetFrom("healthConsult@gmail.com", "Health Consultancy");
          $mail->AddReplyTo("healthConsult@gmail.com", "Health Consultancy");
          $mail->Subject = "Reg:Cancellation of Appointment";

    if($reason == ""){
        
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
          $DELETE_SLOT = "DELETE FROM TimeSlots WHERE ID = '$slot_id' ";
          $DELETE_SYMP = "DELETE FROM patientSymptoms WHERE ApptID = '$ApptID' ";
          $DELETE_APPT = "DELETE FROM Appointment WHERE TimeSlotID = '$slot_id' ";
          $q_result1 = mysqli_query($conn, $DELETE_SYMP);
          $q_result2 = mysqli_query($conn, $DELETE_APPT);
          $q_result3 = mysqli_query($conn, $DELETE_SLOT);
          
               echo '.';
               pop("Slot removed","success","../html/slots.php","Appointment was cancelled");
          
     }
    }
    else{
          $content = "<b>Dear ".$pat_name.",<br><br>

          I hope this message finds you well.<br><br>

          I regret to inform you that your scheduled appointment for ".$appointment." has been canceled due to unforeseen circumstances. Unfortunately, the doctor is unable to attend to appointments during this time.<br><br>

          The doctor's words, the reason for cancellation is:'".$reason."'. <br><br>

          We apologize for any inconvenience this may cause you. Your health and well-being remain our top priority, you can reschedule any other available appointment time slots at your convenience.<br><br>

          Please accept our sincerest apologies for the inconvenience.<br><br>

     Thank you for your understanding and cooperation.</b>";

     $mail->MsgHTML($content); 
    if(!$mail->Send()) {
          echo "Error while sending Email.";
          var_dump($mail);
     } else {
          $DELETE_SLOT = "DELETE FROM TimeSlots WHERE ID = '$slot_id' ";
          $DELETE_SYMP = "DELETE FROM patientSymptoms WHERE ApptID = '$ApptID' ";
          $DELETE_APPT = "DELETE FROM Appointment WHERE TimeSlotID = '$slot_id' ";
          $q_result1 = mysqli_query($conn, $DELETE_SYMP);
          $q_result2 = mysqli_query($conn, $DELETE_APPT);
          $q_result3 = mysqli_query($conn, $DELETE_SLOT);
          if($q_result1 == TRUE && $q_result2 == TRUE){
               echo '.';
               pop("Slot removed","success","../html/slots.php","Appointment was cancelled and reason was communicated to the patient");
     }
    }
}
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