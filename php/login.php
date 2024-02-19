<?php
    session_start();

    $user = $_POST['user'];
    $pass  = $_POST['pass'];
    $status = $_POST['status'];

    if (empty($user) || empty($pass) )
    {
        die('Username or password missing');
    }

    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "healthConsultancy";

    // Create connection
    $conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);

    if (mysqli_connect_error()){
    die('Connect Error ('. mysqli_connect_errno() .') '
        . mysqli_connect_error());
    }

    else{
        $SELECT = "SELECT email From users Where Email = ? Limit 1";

        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->bind_result($user);
        $stmt->store_result();
        $rnum = $stmt->num_rows;
        $stmt->close();

        if ($rnum==1) {
            $result = mysqli_query($conn,"SELECT * FROM users where Email='" . $_POST['user'] . "'");
            $row = mysqli_fetch_assoc($result);
            $dbpass = $row['Password'];
            //$pass = md5($pass);
            $uid = $row['UID'];
            $dbstatus = $row['Status'];
            if($status == $dbstatus){
                if($pass == $dbpass){
                    $_SESSION['uid'] = $uid;
                    $_SESSION['email'] = $user;
                    if($status == 'doc'){
                        header('Location: ../html/doc.html');
                    }
                    else{
                        header('Location: ../html/pat.html');
                    }
                }
                else{
                    echo '<script>alert("Wrong password")</script>'; 
                }
            }
            else{
                    echo '<script>alert("User does not exist")</script>'; 
            }
        }
        if($rnum!=1){
            echo '<script>alert("User does not exist")</script>'; 
        }
    }
?>