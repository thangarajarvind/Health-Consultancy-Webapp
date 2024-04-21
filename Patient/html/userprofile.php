<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Consultancy - Edit Profile</title>
    <link rel="icon" type="image/x-icon" href="../css/doctor_favicon.png">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/doctor.css" type="text/css">
    
    <style>
        /* Add any additional styling here */
        .appointment-details {
            margin-top: 55px;
            margin-bottom: 20px;
            margin-left: 385px;
            padding: 30px;
            background-color: #f4f4f4;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 35%;
            text-align: center;
            left: 0;
            float: left;
        }
        h2{
            text-align: center;
        }
        .parent{
            margin:0 auto;
            padding: 40px;
        }
        #reviewForm{
            width: 99%;
            padding: 20px;
            margin-left: 4px;
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
        <a href="search.html">Book Appointment</a>
        <a class="active" href="userprofile.php.html">Edit Profile</a>
        <a href="futureapp.html">View Appointment</a>
        <a href="../../Reg_Login/php/logout.php">Log Out</a>
    </div>

    <?php
    //Obtain and change from db
        $userName = "John Doe";
        $userEmail = "john@example.com";
        $phone = '9089098789';
        $age = '21';
    ?>
    <section class="dashboard">
        <div class="parent" id="parent">
            <h2>Edit Profile</h2>
            <div class="appointment-details" id="appointmentReview">
                <form class="rating_form" id="reviewForm">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo $userName; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $userEmail; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Phone:</label>
                    <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Age:</label>
                    <input type="text" id="age" name="age" value="<?php echo $age; ?>">
                </div>
                </form>
                <br><br>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </div>
    </section>
</body>
</html>
