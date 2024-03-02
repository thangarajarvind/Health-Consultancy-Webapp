<?php

session_start();

$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "healthConsultancy";

$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);


if(isset($_POST['submit'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $pass = md5($_POST['password']);
    $cpass = md5($_POST['cpassword']);
    $user_type = $_POST['user_type'];
    $emptys = "";
 
    $select = " SELECT * FROM users WHERE Email = '$email' ";
 
    $result = mysqli_query($conn, $select);
 
    if(mysqli_num_rows($result) > 0){
       echo "<script>alert('user already exist!')
         window.location.href='../html/register.html';
         </script>";
 
    }else{
         if($pass != $cpass){
             echo "<script>alert('password not matched!')
            window.location.href='../html/register.html';
            </script>";
             
         }
         elseif(strlen($_POST['password'])<6){
             echo "<script>alert('Password should be at least 6 characters long')
            window.location.href='../html/register.html';
            </script>";
         }
         else{
          $insert = "INSERT INTO users(Name, Email, PhoneNumber, Status, Age, Password) VALUES('$name','$email','$phone','$user_type','$age','$pass')";
          $result = $conn->query($insert);
          if ($result) {
          echo "<script>alert('Account created!')
            window.location.href='../html/login.html';
            </script>";
          }
          else{
            echo "error";
            printf("%s\n", $conn->error);
          }
       }
    }
}
?>