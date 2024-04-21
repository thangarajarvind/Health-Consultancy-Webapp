<!DOCTYPE html>
<html lang="en">
<?php
// Check if the appointmentID parameter exists in the URL
if (isset($_GET['appointmentID'])) {
    // Get the appointmentID from the URL
    $appointmentID = $_GET['appointmentID'];
    session_start();
    $_SESSION['appt_id'] = $appointmentID;

    // You can then use $appointmentID in your PHP code

} else {
    // If appointmentID parameter is not provided in the URL

    // You can handle this error condition as per your requirement
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Consultancy - Appointment Information</title>
    <link rel="icon" type="image/x-icon" href="../css/doctor_favicon.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/doctor.css" type="text/css">

    <style>
        /* Add any additional styling here */
        .appointment-details {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            padding-top: 12px;
            padding-bottom: 12px;
            background-color: #1f5fa0;
            color: white;
        }

        th:hover {
            background-color: #2c3e50;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .add-row-btn {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 5px;
        }

        #saveBtn {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 5px;
        }

        .deletebtn {
            background-color: red;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 5px;
        }

        .arrow {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="nav" id="mynavbar">
        <a href=""><b>Health Consultancy</b></a>
        <div class="nav-right" id="navbar-right">
            <a href="../../Reg_Login/php/logout.php">Logout</a>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </div>
    <div class="sidenav" id="mySidenav">
        <br>
        <a href="../html/viewAppointment.html">All Appointments</a>
        <a href="../html/createtimeslot.html">Create Time Slot</a>
        <a href="../php/slots.php">Cancel Time Slot</a>
        <a class="active" href="../html/viewAppointment.html">Priscription</a>
        <a href="../html/futureapp.html">Future Appointments</a>
        <a href="../../Reg_Login/php/logout.php">Log Out</a>

    </div>
    <section class="dashboard">
        <div class="container">
            <h2>Appointment Results</h2>
            <div class="appointment-details" id="appointmentDetails">
                <h3>Appointment ID:
                    <?php echo $appointmentID ?>
                </h3>
                <table id="myTable">
                    <tr>
                        <th>Medicine Name</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Morning</th>
                        <th>Afternoon</th>
                        <th>Night</th>
                        <th>Action</th>
                    </tr>
                </table>

                <button class="add-row-btn" onclick="addRow()">Add Row</button>
                <button id="saveBtn" onclick="saveData()">Save</button>

                <script>
                    var counter = 1; // Initialize counter

                    function addRow() {
                        var table = document.getElementById("myTable");
                        var row = table.insertRow(-1);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);
                        var cell4 = row.insertCell(3);
                        var cell5 = row.insertCell(4);
                        var cell6 = row.insertCell(5);
                        var cell7 = row.insertCell(6);
                        cell1.innerHTML = "<input type='text' value=''>";
                        cell2.innerHTML = "<input type='number' id='counterInput" + counter + "' value='1'>" +
                            "<span class='arrow' onclick='incrementCounter(this, \"counterInput" + counter + "\", true)'>&#x25B2;</span>" +
                            "<span class='arrow' onclick='incrementCounter(this, \"counterInput" + counter + "\", false)'>&#x25BC;</span>";
                        cell3.innerHTML = "<input type='text' value=''>";
                        cell4.innerHTML = "<input type='number' id='morningInput" + counter + "' value='1'>" +
                            "<span class='arrow' onclick='incrementCounter(this, \"morningInput" + counter + "\", true)'>&#x25B2;</span>" +
                            "<span class='arrow' onclick='incrementCounter(this, \"morningInput" + counter + "\", false)'>&#x25BC;</span>";
                        cell5.innerHTML = "<input type='number' id='afternoonInput" + counter + "' value='1'>" +
                            "<span class='arrow' onclick='incrementCounter(this, \"afternoonInput" + counter + "\", true)'>&#x25B2;</span>" +
                            "<span class='arrow' onclick='incrementCounter(this, \"afternoonInput" + counter + "\", false)'>&#x25BC;</span>";
                        cell6.innerHTML = "<input type='number' id='nightInput" + counter + "' value='1'>" +
                            "<span class='arrow' onclick='incrementCounter(this, \"nightInput" + counter + "\", true)'>&#x25B2;</span>" +
                            "<span class='arrow' onclick='incrementCounter(this, \"nightInput" + counter + "\", false)'>&#x25BC;</span>";
                        cell7.innerHTML = "<button class='deletebtn' onclick='deleteRow(this)'>Delete</button>";
                        counter++; // Increment counter for next row
                    }

                    function deleteRow(btn) {
                        var row = btn.parentNode.parentNode;
                        row.parentNode.removeChild(row);
                    }

                    function incrementCounter(element, inputId, isIncrement) {
                        var input = document.getElementById(inputId);
                        var value = parseInt(input.value);
                        if (isIncrement) {
                            input.value = value + 1;
                        } else {
                            input.value = value - 1 >= 0 ? value - 1 : 0;
                        }
                    }

                    function saveData() {

                        var table = document.getElementById("myTable");
                        var data = [];
                        for (var i = 1; i < table.rows.length; i++) {
                            var row = table.rows[i];
                            var rowData = {
                                medicineName: row.cells[0].querySelector("input").value,
                                amount: row.cells[1].querySelector("input").value,
                                description: row.cells[2].querySelector("input").value,
                                morning: row.cells[3].querySelector("input").value,
                                afternoon: row.cells[4].querySelector("input").value,
                                night: row.cells[5].querySelector("input").value
                            };

                            data.push(rowData);
                        }

                        // Send data to server using AJAX
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "../php/save_data.php", true);
                        xhr.setRequestHeader("Content-Type", "application/json");
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                var response = xhr.responseText;
                                alert(response); // Display server response
                            }
                        };
                        console.log('Response from appdata.php:', data);
                        xhr.send(JSON.stringify(data));

                    }
                </script>
            </div>
        </div>
    </section>
</body>

</html>