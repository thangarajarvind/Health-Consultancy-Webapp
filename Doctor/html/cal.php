<?php
$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "healthConsultancy";

session_start();

$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Perform SQL query to fetch appointment data from your database
// For example:
$today_date = date("Y-m-d");

$timeSlot_array = array();
$past = array();

$result = mysqli_query($conn, "SELECT * FROM Appointment ");

while ($rows = mysqli_fetch_array($result)) {
    $timeSlot_array[] = $rows['TimeSlotID'];
}

foreach ($timeSlot_array as $timeSlot) {
    $select = "SELECT * FROM TimeSlots WHERE ID = '$timeSlot' ";
    $result = mysqli_query($conn, $select);
    if (mysqli_num_rows($result) > 0) {
        while ($rows = mysqli_fetch_array($result)) {
            $apptDate = substr($rows['SlotDateTimeStart'], 0, 10);
            if ($apptDate < $today_date) { // Change comparison to fetch past appointments
                $past[] = $rows['ID'];
            }
        }
    }
}

$appointments = array();

foreach ($past as $pastSlot) {

    $select = "SELECT * FROM Appointment WHERE TimeSlotID = '$pastSlot' ";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        // Fetch each row and add it to the appointments array
        while ($row = mysqli_fetch_assoc($result)) {
            $selectSlot = "SELECT * FROM TimeSlots WHERE ID = '$pastSlot' ";
            $resultSlot = mysqli_query($conn, $selectSlot);
            while ($rowslot = mysqli_fetch_array($resultSlot)) {
                $aptDate = substr($rowslot['SlotDateTimeStart'], 0, 10);
                $aval_id = $rowslot['AvailabilityID'];
            }
            $docIDSelect = "SELECT * FROM Availability WHERE AvailabilityID = '$aval_id' ";
            $resultdocID = mysqli_query($conn, $docIDSelect);
            while ($rowDocID = mysqli_fetch_array($resultdocID)) {
                $doc_ID = $rowDocID['DoctorID'];
            }
            $docNameSelect = "SELECT * FROM DoctorAvailability WHERE UID = '$doc_ID' ";
            $resultdocName = mysqli_query($conn, $docNameSelect);
            while ($rowDocName = mysqli_fetch_array($resultdocName)) {
                $docName = $rowDocName['DoctorName'];
            }
            $patientID = $row['PatientID'];
            $patientNameQuery = "SELECT PatientsName FROM Patients WHERE UID = '$patientID'";
            $patientNameResult = mysqli_query($conn, $patientNameQuery);
            $patientName = mysqli_fetch_assoc($patientNameResult)['PatientsName'];

            $row['apptDate'] = $aptDate;
            $row['DocName'] = $docName;
            $row['PatientName'] = $patientName;
            $appointments[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Consultancy</title>
    <link rel="icon" type="image/x-icon" href="../css/doctor_favicon.png">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/doctor.css" type="text/css">
    
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
        td, th {
            height: 50px !important;
            vertical-align: middle !important;
            align-items: center !important;
            padding: 0 !important;
            margin: 0 !important; 
            line-height: 0 !important; 
            border: none !important;
        }
        .heading {
            margin-left: 38% !important;
        }
        .container {
            margin: 0 auto;
            max-width: 80%;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            text-align: center; 
            vertical-align: middle;
            border: 4px solid #ddd;
            padding: 7px;
        }
        input[type="submit"] {
            width: 20%;
        }
    </style>
</head>
<body>
    <div class="nav" id="mynavbar">
        <a href="">Health Consultancy</a>
        <div class="nav-right" id="navbar-right">
            <a href="logout.php">Logout</a>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </div>
    <div class="sidenav" id="mySidenav">
        <br>
        <a href="createtime.php">Create Time Slot</a>
        <a href="slots.php">Cancel Time Slot</a>
        <a href="booked_appt.php">View Booked Appointments</a>
        <a class="active" href="cal.php">Generate Bill</a> <!-- New link for bill generation -->
        <a href="../../Reg_Login/php/logout.php">Log Out</a>
    </div>
    <section class="dashboard">
        <div class="container">
            <h1>Past Appointments</h1>
            <form name="generate_bill" action="bill.php" method="POST">
                <table id='entries'>
                    <thead>
                        <tr>
                            <th>Select</th> <!-- New th for radio buttons -->
                            <th>Appointment ID</th>
                            <th>Patient Name</th>
                            <th>Doctor Name</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $row) : ?>
                            <tr>
                                <td><input type="radio" name="selectedAppointment" value="<?php echo $row['ID']; ?>"></td> <!-- Radio button -->
                                <td><?php echo $row['ID']; ?></td>
                                <td><?php echo $row['PatientName']; ?></td>
                                <td><?php echo $row['DocName']; ?></td>
                                <td><?php echo $row['apptDate']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <input type="submit" name="submit" value="Generate Bill" class="form-btn">
            </form>
        </div>
    </section>
</body>
</html>


