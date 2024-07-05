<?php
$conn = new mysqli('localhost', 'root', '', 'forgetpass');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>