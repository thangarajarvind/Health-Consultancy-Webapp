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
        <a class="active" href="search.html">Book Appointment</a>
        <a href="../../Reg_Login/php/logout.php">Log Out</a>
        
    </div>
<?php

session_start();

require '/Applications/XAMPP/xamppfiles/lib/php/PHPMailer/src/Exception.php';
require '/Applications/XAMPP/xamppfiles/lib/php/PHPMailer/src/PHPMailer.php';
require '/Applications/XAMPP/xamppfiles/lib/php/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "healthConsultancy";

$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

if(isset($_POST['submit'])){
    $reason = $_POST['reason'];
    $symp = $_POST['symp'];

    $slot_id = $_SESSION['slot_id'];
    $pat_id = $_SESSION['pat_id'];

    $insertAppt = "INSERT INTO Appointment(PatientID, TimeSlotID) VALUES('$pat_id','$slot_id')";
    $result = $conn->query($insertAppt);

    $selectAppt = "SELECT ID FROM Appointment ORDER BY ID DESC LIMIT 1";
    $result = mysqli_query($conn, $selectAppt);
    $row = mysqli_fetch_array($result);
    $apptID = $row['ID'];

    $insertSymp = "INSERT INTO patientSymptoms(ApptID, Symptom, Description) VALUES('$apptID','$symp','$reason')";
    $result = $conn->query($insertSymp);

    $updateTimeSlot = "UPDATE TimeSlots SET IsAvailable = 0 WHERE ID = '$slot_id'";
    $result = $conn->query($updateTimeSlot);

    $selectPat = " SELECT * FROM Patients WHERE UID = '$pat_id' ";
    $resultPatient = mysqli_query($conn, $selectPat);

    $row = mysqli_fetch_array($resultPatient);
    $pat_email = $row['PatientEmail'];
    $pat_name = $row['PatientsName'];

    $selectAppointment = " SELECT * FROM TimeSlots WHERE ID = '$slot_id' ";
    $resultAppointment = mysqli_query($conn, $selectAppointment);
    $row = mysqli_fetch_array($resultAppointment);
    $appointment = $row['SlotDateTimeStart'];

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
    $mail->Subject = "Reg:Appointment confirmation";

    $content = "<b>Dear ".$pat_name.",<br><br>

          We are pleased to confirm your upcoming consultation appointment at ".$appointment.".<br><br>
          
          If you need to cancel the appointment, please feel free to access the portal.<br><br>
          
          Looking forward to seeing you soon.</b>";

          $mail->MsgHTML($content);
    if(!$mail->Send()) {
          echo "Error while sending Email.";
          var_dump($mail);
     } else {
        echo '.';
        pop("Appointment booked!","success","../html/search.html","Check your mail for appointment confirmation");
     }


}
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