<?php

$host = 'localhost'; 
$username = 'root';  
$password = '';     
$dbname = 'todo_app'; 

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erorr Conenction" . $conn->connect_error);
}
?>
