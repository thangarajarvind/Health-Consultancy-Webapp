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
     <link rel="stylesheet" href="filterapp.css" type="text/css">
     
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
    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "healthConsultancy";
    $conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);
    ?>


    <section class="dashboard">
        
        <form class="filter" method="post">
            <h2>Filter</h2>
        <input type="date" name="search_date" placeholder="Date">
        <input type="text" name="search_name" placeholder="Doctor Name">
        
        <input type="submit" value="Search">
        </form>
        <table id="appointment">
            <?php
            function filterTable($conn, $searchName, $searchDate) {
                $sql = "SELECT AD.appointmentID, AD.UID, PD.Prescription, P.PatientsName,AD.date,AD.DoctorName,AD.DoctorType, PD.Description,PS.Symptom FROM appointmentdetails as AD join Patients as P join Patientdiagnosis as PD join PatientSymptoms as PS WHERE (AD.AppointmentID=PS.ApptID and AD.AppointmentID=PD.ApptID and AD.UID=P.UID) and (Doctorname LIKE '%$searchName%' AND Date LIKE '%$searchDate%')";
                $result = $conn->query($sql);
                return $result;
                }
                // Check if form is submitted and process search
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $searchName = $_POST['search_name'];
                    $searchDate = $_POST['search_date'];

                    $result = filterTable($conn, $searchName, $searchDate);
                } else {
                    // If form is not submitted, show all data
                    $sql = "SELECT * FROM your_table_name";
                    $result = $conn->query($sql);
                }
                if (mysqli_num_rows($result)==0){
                    echo '<script>alert("No Data Found")</script>';
                    echo '<script>window.location.replace("pastapp.php");</script>';
                    exit;
                }

            ?>
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
                while ($row = mysqli_fetch_array($result)) {
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

        <!--<input type="submit" name="Viewpres" value="View Prescription" onclick="openForm()">-->


        <!--<div class="pres-popup" id="myForm">
    <h2>Prescription</h2>
    <p><b>
        <?php 
            $apid = $_POST['Viewpres'];
            $select = "SELECT Prescription from patientdiagnosis WHERE ApptID=$apid";
            $result1 = $conn->query($select);
            echo $result; // This should be corrected to $result1
        ?>
    </b></p>
    <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
</div>


        <script>
    function openForm() {
        document.getElementById("myForm").style.display = "block";
    }

    function closeForm() {
        document.getElementById("myForm").style.display = "none";
    }
</script>-->

        
    </section>
</body>
</html>

