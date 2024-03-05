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
  $status = $_POST['status'];

   $select = " SELECT * FROM users WHERE Email = '$email' && Password = '$pass' ";

   $result = mysqli_query($conn, $select);

   if(mysqli_num_rows($result) > 0){

      $row = mysqli_fetch_array($result);

      if($row['Status'] == 'doctor'){
         if($status == 'doctor'){
            $_SESSION['doc_id'] = $row['UID'];
            $_SESSION['logged_in'] = true;
            header('location:../../Doctor/html/slots.php');
         }
         else{
            echo "<script>alert('User does not exist')
            window.location.href='../html/login.html';
            </script>";
         }
      }
      elseif($row['Status'] == 'patient'){
         if($status == 'patient'){
            $_SESSION['user_name'] = $row['UID'];
            header('location:../html/patient_page.html');
         }
         else{
            echo "<script>alert('User does not exist')
            window.location.href='../html/login.html';
            </script>";
         }
      }
   }else{
      echo "<script>alert('Incorrect email or password!')
      window.location.href='../html/login.html';
      </script>";
   }
}
?>