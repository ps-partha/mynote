<?php
include('conf.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $create_datetime = date("Y-m-d H:i:s");
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT `id` FROM `users` WHERE `username` = ? OR `email` = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        // Username or email already exists
        $response = array("status" => "error", "message" => "Username or email already exists.");
    } else {
        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO `users` (`username`, `email`, `password`, `create_datetime`, `name`) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $password, $create_datetime, $name);
        if ($stmt->execute()) {
            $response = array("status" => "success", "message" => "User registered successfully.");
        } else {
            $response = array("status" => "error", "message" => "User registration failed.");
        }
    }
    $stmt->close();
    $conn->close();
    
    // Set the content type to JSON
    header('Content-Type: application/json');
    // Return the response as JSON
    echo json_encode($response);
    exit;
} else {
    header('Content-Type: application/json');
    echo json_encode(array("status" => "error", "message" => "Invalid request method."));
    exit;
}
?>
