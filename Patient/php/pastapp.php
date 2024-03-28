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
     <link rel="stylesheet" href="../css/filterapp.css" type="text/css">
     
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
        <a href="futureapp.php">Future Appointments</a>
        <a class="active" href="pastapp.php">Past Appointments</a>
        <a href="../../Reg_Login/php/logout.php">Log Out</a>
        
    </div>

    <?php
session_start();
$patient_mail = $_SESSION['PatientEmail'];

$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "healthConsultancy";

$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);
 

$select=("SELECT AD.appointmentID, AD.UID, PD.Prescription, P.PatientsName,AD.date,AD.DoctorName,AD.DoctorType, PD.Description,PS.Symptom FROM appointmentdetails as AD join Patients as P join Patientdiagnosis as PD join PatientSymptoms as PS WHERE AD.AppointmentID=PS.ApptID and AD.AppointmentID=PD.ApptID and AD.UID=P.UID");
$appointments = mysqli_query($conn,$select); 

if (mysqli_num_rows($appointments)==0){
    echo '<script>alert("no values")</script>'; 
}
?>

    <section class="dashboard">
        <form id="search-form" class="appointment" action="filterapp.php">
            <input type="submit" value="Filter">
        </form>
        <table id="appointment">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Patient Name</th>
                    <th>Date</th>
                    <th>Doctor Name</th>
                    <th>Doctor Type</th>
                    <th>Diagnosis</th>
                    <th>Symptoms</th>
                    <th>Prescription</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php 
                while ($row = mysqli_fetch_array($appointments)) {
                    echo "<tr><td>".$row['appointmentID']."</td>";
                    echo "<td>".$row['PatientsName']."</td>" ;
                    echo "<td>".$row['date']."</td>" ;
                    echo "<td>".$row['DoctorName']."</td>" ;
                    echo "<td>".$row['DoctorType']."</td>" ;
                    echo "<td>".$row['Description']."</td>" ;
                    echo "<td>".$row['Symptom']."</td>" ;
                    echo "<td>".$row['Prescription']."</td></tr>" ;
                    
                }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>



