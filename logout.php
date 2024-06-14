<?php
session_start(); // Ensure the session is started
include('con.php');
include('functions.php');

// Unset specific session variables
unset($_SESSION['UID']);
unset($_SESSION['UNAME']);
//unset($_SESSION['UROLE']); // Add this if you also set the user role in the session

// Optionally destroy the session entirely
session_destroy();

// Redirect to the index page
redirect('index.php');
?>
