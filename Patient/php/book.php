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
if(isset($_POST['submit'])){
    $slot_id = $_POST['timeslotID'];

    if($slot_id == null){
        echo '.';
        pop("Please select a time slot","error","../html/book.php","");
       }
    else{
        $_SESSION['slot_id'] = $slot_id;
        header('Location: '.'../html/details.html');
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