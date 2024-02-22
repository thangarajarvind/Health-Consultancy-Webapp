<?php

$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "healthConsultancy";

$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = md5($_POST['pass']);
    $cpass = md5($_POST['conpass']);
    
    $select = " SELECT * FROM users WHERE Email = '$email' ";
    $result = mysqli_query($conn, $select);
    

    if (mysqli_num_rows($result) > 0) {
        if($pass != $cpass){
            $error[] = 'password not matched!';
            
        }
        elseif(strlen($_POST['pass'])<6){
            $error[] = 'Password should be at least 6 characters long';
        }
        else{
        $update = " UPDATE users set Password = '$pass' where Email = '$email' ";
        mysqli_query($conn, $update);
        echo "<script>alert('Password updated successfully');</script>";
    echo "<script>window.location = '../html/login.html';</script>";

    }} else 
        
        {
        $error[] = 'user does not exist!';
        
    }
    

}
;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <h1> Health Consultancy</h1>
    <div class="form-container">

        <form action="" method="post">
            <h3>Reset Password</h3>
            <?php
            if (isset($error)) {
                foreach ($error as $error) {
                    echo '<span class="error-msg">' . $error . '</span>';
                }
                ;
            }
            ;
            ?>
            <label for="email">Email:</label><br>
            <input type="email" name="email" required><br>
            <label for="pass">New Password:</label><br>
            <input type="password" name="pass" required><br>
            <label for="conpass">Confirm Password:</label><br>
            <input type="password" name="conpass" required><br>
            <input type="submit" name="submit" value="Submit" class="form-btn">
        </form>

    </div>
</body>

</html>
