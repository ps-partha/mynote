<?php
include('conf.php');

// Set secure session cookies
session_set_cookie_params([
    'httponly' => true,
    'secure' => true,
    'samesite' => 'Strict'
]);

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = sanitize_input($_POST['password']);
    $name = sanitize_input($_POST['name']);
    $create_datetime = date("Y-m-d H:i:s");

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = array("status" => "error", "message" => "Invalid email format.");
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

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
        $stmt->bind_param("sssss", $username, $email, $hashedPassword, $create_datetime, $name);
        if ($stmt->execute()) {
            $response = array("status" => "success", "message" => "User registered successfully.");
        } else {
            // Log error to a file for debugging
            error_log("User registration failed: " . $stmt->error);
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
