<?php

// server info
$server = 'localhost';
$user = 'root';
$pass = 'password';
$db = 'dev_adoption';

// connect to the database
$mysqli = new mysqli($server, $user, $pass, $db);

// show errors (remove this line if on a live site)
mysqli_report(MYSQLI_REPORT_ERROR);

?>