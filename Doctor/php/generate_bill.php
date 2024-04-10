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
        <a href="createtime.php">Create Time Slot</a>
        <a href="slots.php">Cancel Time Slot</a>
        <a href="booked_appt.php">View Booked Appointments</a>
        <a class="active" href="cal.php">Generate Bill</a> <!-- New link for bill generation -->
        <a href="../../Reg_Login/php/logout.php">Log Out</a>
    </div>
<?php
session_start();
$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "healthConsultancy";
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $appointmentID = $_SESSION['appt_id'];
    echo "Appointment ID: $appointmentID <br>";

    $sql = "SELECT PatientID FROM Appointment WHERE ID = $appointmentID";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $patientID = $row["PatientID"];
        echo "Patient ID: $patientID <br>";
    }

    // Retrieve service IDs and quantities
    $serviceIDs = isset($_POST['serviceSelect']) ? $_POST['serviceSelect'] : array();
    $quantities = isset($_POST['quantity']) ? $_POST['quantity'] : array();

    // Print service IDs and quantities for debugging
    echo "Service IDs: ";
    print_r($serviceIDs);
    echo "<br>";
    echo "Quantities: ";
    print_r($quantities);
    echo "<br>";

    // Check if any services were selected
    if (empty($serviceIDs)) {
        echo "No services selected.";
        exit; // Exit the script if no services were selected
    }

    // Calculate total cost
    $totalCost = 0;
    foreach ($serviceIDs as $index => $serviceID) {
        $quantity = $quantities[$index];
        // Retrieve service cost from the database
        $serviceQuery = "SELECT Cost FROM Service WHERE ServiceID = $serviceID";
        $serviceResult = mysqli_query($conn, $serviceQuery);
        if ($serviceResult && mysqli_num_rows($serviceResult) > 0) {
            $serviceData = mysqli_fetch_assoc($serviceResult);
            $totalCost += $serviceData['Cost'] * $quantity;
            echo "Total Cost (updated): $totalCost <br>";
        }
    }

    // Insert into Bill table
    $insertBillQuery = "INSERT INTO Bill (AppointmentID, PatientID, TotalCost, GeneratedAt) VALUES ('$appointmentID', '$patientID', '$totalCost', NOW())";
    $result = mysqli_query($conn, $insertBillQuery);
    if ($result) {
        $billID = mysqli_insert_id($conn);
        echo "Bill ID: $billID <br>";

        // Insert into BillService table
        foreach ($serviceIDs as $index => $serviceID) {
            $quantity = $quantities[$index];
            $insertBillServiceQuery = "INSERT INTO BillService (BillID, ServiceID, Quantity) VALUES ('$billID', '$serviceID', '$quantity')";
            $billServiceResult = mysqli_query($conn, $insertBillServiceQuery);
            if (!$billServiceResult) {
                echo "Error inserting into BillService table: " . mysqli_error($conn);
                exit;
            }
        }

        // Provide feedback to the user
        alert('a');
        pop("Bill generated","success","../html/bill.php","");
    } else {
        // Handle insertion failure
        echo "Error inserting into Bill table: " . mysqli_error($conn);
    }
} else {
    // Handle invalid request
    echo "Invalid request!";
}

// Close database connection
mysqli_close($conn);

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
?>
