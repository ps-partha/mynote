<?php
include('conf.php');

// Set secure session cookie parameters
session_set_cookie_params([
    'httponly' => true,
    'secure' => true,
    'samesite' => 'Strict'
]);

session_start();

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize_input($_POST['email']);
    $password = sanitize_input($_POST['password']);
    $checkbox = isset($_POST['remember-me']) ? sanitize_input($_POST['remember-me']) : '';

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = array("status" => "error", "message" => "Invalid email format.");
        echo json_encode($response);
        exit;
    }

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT `id`, `username`, `password`, `name` FROM `users` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashedPassword, $name);
        $stmt->fetch();
        
        if (password_verify($password, $hashedPassword)) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $username;
            $_SESSION['user_email'] = $email;
            $_SESSION['name'] = $name;
            
            if ($checkbox === "checked") {
                $token = bin2hex(random_bytes(32));
                $token_data = json_encode(['user_id' => $id, 'token' => $token]);
                setcookie('remember_me', base64_encode($token_data), time() + (86400 * 30), "/", "", true, true);
            }
            $response = array("status" => "success", "message" => "User login successful.");
        } else {
            $response = array("status" => "error", "message" => "Password does not match.");
        }
    } else {
        $response = array("status" => "error", "message" => "Email not found.");
    }

    $stmt->close();
    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} else {
    $response = array("status" => "error", "message" => "Invalid request method.");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
