<?php

session_start();

$code = 123;
$email = "thangrajarvind@gmail.com";
$name = "Arvind";

require '/Applications/XAMPP/xamppfiles/lib/php/PHPMailer/src/Exception.php';
require '/Applications/XAMPP/xamppfiles/lib/php/PHPMailer/src/PHPMailer.php';
require '/Applications/XAMPP/xamppfiles/lib/php/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'sandbox.smtp.mailtrap.io';
$mail->SMTPAuth = true;
$mail->Port = 2525;
$mail->Username = '34fca8e46805cf';
$mail->Password = 'aea572039bfb25';

$mail->IsHTML(true);
$mail->AddAddress($email, $name);
$mail->SetFrom("cse63234@gmail.com", "Duty Management");
$mail->AddReplyTo("cse63234@gmail.com", "Duty Management");
$mail->Subject = "Reg:Password reset";
$content = "<b>Use the following code to reset your password - ".$code."</b>";

$mail->MsgHTML($content); 


if(!$mail->Send()) {
  echo "Error while sending Email.";
  var_dump($mail);
} else {
  echo "Mail sent.";
}