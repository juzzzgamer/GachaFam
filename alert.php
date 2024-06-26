<?php
//https://youtu.be/l4OnNbU9VDo?si=jjxscur59Nd5kev5
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                swal('Success', '{$success_message}', 'success');
            });
          </script>";
    unset($_SESSION['success_message']);
}


if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                swal('Error', '{$error_message}', 'error');
            });
          </script>";
    unset($_SESSION['error_message']);
}
?>