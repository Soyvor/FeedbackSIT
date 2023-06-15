<?php

session_start(); // start the session
 session_unset(); // unset all session variables
 session_destroy(); // destroy the session


  


  
  // Prevent caching of the page by adding cache-control headers
  header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
  header("Pragma: no-cache"); // HTTP 1.0
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
  
  // Redirect the user to the login page
  header("Location: index.php");
  exit();

?>