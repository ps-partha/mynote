<?php
session_start();
include("conf.php");
header('Content-Type: application/json');

// Log errors to a file instead of displaying them
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/php-error.log');

$response = ['status' => 'error', 'message' => 'An unknown error occurred'];

if (!isset($_SESSION['user_email']) && !isset($_SESSION['user_name']) && !isset($_SESSION['user_id'])) {
    $response['message'] = 'User not authenticated';
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];  // Correct parameter name
        $messages = $_POST['messages'];  // Correct parameter name
        $username = $_SESSION['user_name'];
        $userId = $_SESSION['user_id'];
        if (!empty($title) && !empty($messages)) {
            $stmt = $conn->prepare("INSERT INTO `user_notes` (`username`, `titles`, `messages`, `status`,`user_id`) VALUES (?, ?, ?, 'unpinned',?)");
            $stmt->bind_param("ssss", $username, $title, $messages,$userId);

            if ($stmt->execute()) {
                $response = array("status" => "success", "message" => "saved");
            } else {
                $response = array("status" => "error", "message" => "Failed to save note");
            }

            $stmt->close();
        }
    } else {
        $response['message'] = 'Invalid request method';
    }
}

echo json_encode($response);
exit;
?>
