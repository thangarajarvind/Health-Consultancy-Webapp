<?php

$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "healthConsultancy";

require '/Applications/XAMPP/xamppfiles/lib/php/PHPMailer/src/Exception.php';
require '/Applications/XAMPP/xamppfiles/lib/php/PHPMailer/src/PHPMailer.php';
require '/Applications/XAMPP/xamppfiles/lib/php/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);





    if (isset($_POST['submit'])) {
    // Retrieve the email address
    $email = $_POST['email'];
    
    $select = " SELECT * FROM users WHERE Email = '$email' ";
    $result = mysqli_query($conn, $select);

    $row = mysqli_fetch_array($result);
    $name = $row['Name'];

    if (mysqli_num_rows($result) > 0) {
        $mail = new PHPMailer();
          $mail->isSMTP();
          $mail->Host = 'sandbox.smtp.mailtrap.io';
          $mail->SMTPAuth = true;
          $mail->Port = 2525;
          $mail->Username = '34fca8e46805cf';
          $mail->Password = 'aea572039bfb25';

          $mail->IsHTML(true);
          $mail->AddAddress($email,$name);
          $mail->SetFrom("healthConsult@gmail.com", "Health Consultancy");
          $mail->AddReplyTo("healthConsult@gmail.com", "Health Consultancy");
          $mail->Subject = "Reg:Password change";

          $content= "<b>Dear ".$name.",<br><br>

               I hope this message finds you well.<br><br>
               click this link to<a href='https://6324dev.000webhostapp.com/php/reset_pass.php'> reset your Password.</a>
               Thank You."
        $mail->MsgHTML($content); 
    if(!$mail->Send()) {
          echo "Error while sending Email.";
          var_dump($mail);
     } else {
        echo "<script>alert('Password reset link sent');</script>";
    echo "<script>window.location = '../html/login.html';</script>";
        
    }
    else{
        $error[] = 'User is not registered';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <h1> Health Consultancy</h1>
    <div class="form-container">

        <form action="" method="post">
            <h3>Forgot Password</h3>
            <?php
            if (isset($error)) {
                foreach ($error as $error) {
                    echo '<span class="error-msg">' . $error . '</span>';
                }
            }
            ?>
            <label for="email">Enter your email:</label><br>
            <input type="email" id="email" name="email" required><br>
            <input type="submit" name="submit" value="Submit" class="form-btn">
            <a href="../html/login.html">
                <input type="button" value="Cancel" class="form-btn" />
            </a>
            

        </form>

    </div>
</body>

</html>
