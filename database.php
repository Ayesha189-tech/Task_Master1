<?php
$host = "localhost"; 
$user = "root"; 
$password = ""; 
$dbname = "noor"; 

// Create Connection
$con = new mysqli($host, $user, $password, $dbname);

// Check Connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
