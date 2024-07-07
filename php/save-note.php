<?php
include("conf.php");
header('Content-Type: application/json');
// Log errors to a file instead of displaying them
session_set_cookie_params([
    'httponly' => true,
    'secure' => true,
    'samesite' => 'Strict'
]);
session_start();
$response = ['status' => 'error', 'message' => 'An unknown error occurred'];

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Check if the user is authenticated
if (!isset($_SESSION['user_email']) || !isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
    $response['message'] = 'User not authenticated';
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = sanitize_input($_POST['title']);
        $messages = sanitize_input($_POST['messages']);
        $username = sanitize_input($_SESSION['user_name']);
        $userId = sanitize_input($_SESSION['user_id']);

        // Validate input
        if (!empty($title) && !empty($messages)) {
            if (strlen($title) <= 255 && strlen($messages) <= 10000) {  // Arbitrary length checks, adjust as needed
                $stmt = $conn->prepare("INSERT INTO `user_notes` (`username`, `titles`, `messages`, `status`, `user_id`) VALUES (?, ?, ?, 'unpinned', ?)");
                $stmt->bind_param("ssss", $username, $title, $messages, $userId);

                if ($stmt->execute()) {
                    $response = ["status" => "success", "message" => "Note saved"];
                } else {
                    // Log detailed error for debugging
                    error_log("Failed to save note: " . $stmt->error);
                    $response['message'] = 'Failed to save note';
                }

                $stmt->close();
            } else {
                $response['message'] = 'Title or message is too long';
            }
        } else {
            $response['message'] = 'Title and message cannot be empty';
        }
    } else {
        $response['message'] = 'Invalid request method';
    }
}

echo json_encode($response);
exit;
?>
