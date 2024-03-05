<head>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>

<?php
session_start();
session_destroy();
echo '.';
pop("Successfully logged out","success","../html/login.html","");

function pop($t, $i, $l, $x){
     echo "<script type='text/javascript'>";
     echo "Swal.fire({
          title: '$t',
          text: '$x',
          icon: '$i',   
          confirmButtonColor: '#55c2da',
     }).then((result) => {
          if (result.isConfirmed) {
               window.location.href = '$l'
          }
     });";
     echo "</script>";
}
?>