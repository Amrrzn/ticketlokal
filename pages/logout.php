<?php
session_start();
session_destroy(); // Destroy all session data

// You can now safely redirect without executing any other output
header("Location: ../pages/home.php");
exit();
?>
