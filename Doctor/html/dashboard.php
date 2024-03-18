<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Consultancy</title>
    <link rel="icon" type="image/x-icon" href="../css/doctor_favicon.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/doctor.css" type="text/css">
    
    <style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    /* Background image styling */
    .main-content {
        background-image: url("https://www.semiosissoftware.com/wp-content/uploads/2020/02/Doctor-Appointment-System-1536x840.jpg");
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        height: 150vh;
        width: 200vh;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    table {
        width: 1000px;
        border-collapse: collapse;
        text-align: left;
        margin-top: 20px;
    }

    table, th, td {
        border: 1px solid white;
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
    h2{
        padding:0px;
        text-align:center;
        margin-top: -160px;
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
    <a class="active" href="../html/createtimeslot.html">Create Time Slot</a>
    <a href="slots.php">Cancel Time Slot</a>
    <a href="../../Reg_Login/php/logout.php">Log Out</a>
</div>

<div class="container">
    <section class="dashboard">
<h2 >Appointment Slots</h2>
<br><br>
        <?php
        include('config.php');
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
        ?>
        </tbody>
    </table>
    <input type="submit" name="submit" value="Cancel">
    </form>
    </section>
</div>
</body>
</html>
