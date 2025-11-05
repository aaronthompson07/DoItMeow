<?php
$host = 'localhost';
$user = 'root';
$pass = 'Khaleesi2025!';
$dbname = 'doitmeow';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) { die('Database connection failed: ' . $conn->connect_error); }
$conn->set_charset('utf8mb4');
?>
