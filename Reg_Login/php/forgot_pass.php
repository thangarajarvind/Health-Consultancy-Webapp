<?php

$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "healthConsultancy";

$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

if (isset($_POST['submit'])) {
    // Retrieve the email address
    $email = $_POST['email'];
    
    $select = " SELECT * FROM users WHERE Email = '$email' ";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
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