<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Appointment Slot</title>
    <link rel="stylesheet" href="../css/doctor.css">
    <style>
        /* Background image styling */
        .main-content {
            background-image: url("https://www.semiosissoftware.com/wp-content/uploads/2020/02/Doctor-Appointment-System-1536x840.jpg"); /* Replace 'path/to/your/image.jpg' with the actual path to your image */
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            height: 150vh; /* Ensure the background covers the entire viewport height */
            width: 200vh;
        }


        table {
            width: 80%; /* Set table width to 80% */
            margin: 0 auto; /* Center the table horizontally */
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid white; /* Set table borders to white */
            text-align: center; /* Center-align table content */
            padding: 10px; /* Add padding to table cells */
        }

        th {
            background-color: #2C3E50; /* Set header background color */
            color: white; /* Set header text color to white */
        }

        tr:nth-child(even) {
            background-color: #e0e7ef; /* Set even row background color */
        }

        tr:nth-child(odd) {
            background-color: #f2f2f2; /* Set odd row background color */
        }

        tr:hover {
            background-color: #ddd; /* Set hover row background color */
        }
    </style>
</head>
<body>
<?php
// Include config.php for database connection
include('config.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all form fields are set and not empty
    if (isset($_POST['date']) && isset($_POST['available_from']) && isset($_POST['available_to']) && isset($_POST['DoctorID']) 
        && !empty($_POST['date']) && !empty($_POST['available_from']) && !empty($_POST['available_to']) && !empty($_POST['DoctorID'])) {
        
        $date = $_POST['date'];
        $available_from = $_POST['available_from'];
        $available_to = $_POST['available_to'];
        $doctor_id = $_POST['DoctorID'];

        // Check if the slot already exists
        $sql_check = "SELECT * FROM Availability 
                      WHERE Date = '$date' 
                      AND AvailableFrom = '$available_from' 
                      AND AvailableTo = '$available_to'
                      AND DoctorID = '$doctor_id'";

        $result_check = mysqli_query($conn, $sql_check);
        
        if (mysqli_num_rows($result_check) > 0) {
            echo "Cannot create multiple slots for the given time for this doctor";
        } else {
            // Insert the appointment slot into the database
            $sql_insert = "INSERT INTO Availability (Date, AvailableFrom, AvailableTo, DoctorID) 
                           VALUES ('$date', '$available_from', '$available_to', '$doctor_id')";

            if (mysqli_query($conn, $sql_insert)) {
                echo "Appointment slot created successfully";
            } else {
                echo "Error: " . $sql_insert . "<br>" . mysqli_error($conn);
            }
        }

    } else {
        echo "All form fields are required";
    }
}

// Display the records in a table
$sql = "SELECT * FROM Availability";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>
    <tr>
        <th>Date</th>
        <th>Available From</th>
        <th>Available To</th>
        <th>Doctor ID</th>
    </tr>";
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>" . $row["Date"] . "</td>
        <td>" . $row["AvailableFrom"] . "</td>
        <td>" . $row["AvailableTo"] . "</td>
        <td>" . $row["DoctorID"] . "</td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

// Close the database connection
mysqli_close($conn);
include('createtimeslots.php');
?>
</body>
</html>
