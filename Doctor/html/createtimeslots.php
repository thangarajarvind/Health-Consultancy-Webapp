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
            background-image: url("https://www.semiosissoftware.com/wp-content/uploads/2020/02/Doctor-Appointment-System-1536x840.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh; /* Changed to 100vh */
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid white;
            text-align: center;
            padding: 10px;
        }

        th {
            background-color: #2C3E50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #e0e7ef;
        }

        tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['date']) && isset($_POST['available_from']) && isset($_POST['available_to']) 
        && isset($_POST['DoctorID']) && !empty($_POST['date']) && !empty($_POST['available_from']) 
        && !empty($_POST['available_to']) && !empty($_POST['DoctorID'])) {
        
        $date = $_POST['date'];
        $available_from = $_POST['available_from'];
        $available_to = $_POST['available_to'];
        $doctor_id = $_POST['DoctorID'];

        // Fetching the AvailabilityID from the Availability table
$sql_availability_id = "SELECT AvailabilityID FROM Availability 
                        WHERE AvailableFrom = '$available_from' 
                        AND AvailableTo = '$available_to'
                        AND DoctorID = '$doctor_id'";
$result_availability_id = mysqli_query($conn, $sql_availability_id);

if ($result_availability_id) {
    $availability_id_row = mysqli_fetch_assoc($result_availability_id);
    $availability_id = $availability_id_row['AvailabilityID'];
} else {
    echo "Error fetching AvailabilityID: " . mysqli_error($conn);
    // Handle the error accordingly
}


        $sql_check = "SELECT * FROM TimeSlots 
                      WHERE SlotDateTimeStart = '$available_from' 
                      AND SlotDateTimeEnd = '$available_to'
                      AND DoctorID = '$doctor_id'";
        
        $result_check = mysqli_query($conn, $sql_check);
        
        if (mysqli_num_rows($result_check) > 0) {
            echo "Cannot create multiple slots for the given time for this doctor";
        } else {
            // Define the divideTimeSlots function
            function divideTimeSlots($start, $end, $duration) {
                $start = new DateTime($start);
                $end = new DateTime($end);
                $interval = new DateInterval('PT' . $duration . 'M');
                $slots = array();

                while ($start < $end) {
                    $slotEnd = clone $start;
                    $slotEnd->add($interval);
                    $slots[] = array(
                        'start' => $start->format('Y-m-d H:i:s'),
                        'end' => $slotEnd->format('Y-m-d H:i:s')
                    );
                    $start = $slotEnd;
                }

                return $slots;
            }

            // Use divideTimeSlots function to generate time slots
            $timeSlots = divideTimeSlots($available_from, $available_to, 60); // Assuming each slot is 60 minutes

            $sql_insert = "INSERT INTO TimeSlots (SlotDateTimeStart, SlotDateTimeEnd, DoctorID, AvailabilityID) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);

            foreach ($timeSlots as $slot) {
                $startTime = $slot['start'];
                $endTime = $slot['end'];
                $stmt->bind_param("ssii", $startTime, $endTime, $doctor_id, $availability_id); // Bind all parameters
                $stmt->execute();
            }

            if ($stmt) {
                echo "Appointment slots created successfully";
            } else {
                echo "Error: Appointment slot creation failed";
            }
        }

    } else {
        echo "All form fields are required";
    }
}

$sql = "SELECT * FROM TimeSlots";
$result = $conn->query($sql);


mysqli_close($conn);
?>
</body>
</html>
