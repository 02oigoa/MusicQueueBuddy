<?php
// DB Config
$host = 'db';  // DB service name in docker compsose 
$dbname = 'spotily'; // change if needed
$username = 'your_ub_user';
$password = 'your_db_pass'; 

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
} else {
}
