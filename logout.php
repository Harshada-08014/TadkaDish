<?php
// Start the session
session_start();

// Destroy the session to log the user out
session_destroy();

// Redirect the user to the login page after logging out
header("Location: admin.php");
exit;
?>
