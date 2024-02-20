<?php
session_start();

$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "healthConsultancy";

$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

if(isset($_POST['submit'])){
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $pass = md5($_POST['password']);

   $select = " SELECT * FROM users WHERE Email = '$email' && Password = '$pass' ";

   $result = mysqli_query($conn, $select);

   if(mysqli_num_rows($result) > 0){

      $row = mysqli_fetch_array($result);

      if($row['Status'] == 'doctor'){

         $_SESSION['user_name'] = $row['UID'];
         header('location:../html/doctor_page.html');

      }
      elseif($row['Status'] == 'patient'){

         $_SESSION['user_name'] = $row['UID'];
         header('location:../html/html/patient_page.html');

      }
     
   }else{
      //$error[] = 'incorrect email or password!';
      echo '<span class="error-msg">incorrect email or password!</span>';
   }
}
?>