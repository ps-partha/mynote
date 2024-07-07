<?php
session_start();
include("conf.php");
header('Content-Type: application/json');

// Check if the user is authenticated
if (!isset($_SESSION['user_email']) || !isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    exit;
}

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = sanitize_input($_POST['id']);
    $messages = $_POST['messages'];
    $title = sanitize_input($_POST['title']);

    // Validate input
    if (!empty($id) && !empty($messages) && !empty($title)) {
        if (strlen($title) <= 255 && strlen($messages) <= 10000) {  // Arbitrary length checks, adjust as needed
            $stmt = $conn->prepare("UPDATE `user_notes` SET `messages`= ?, `titles`= ? WHERE `id`= ?");
            $stmt->bind_param("sss", $messages, $title, $id);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Note updated']);
            } else {
                // Log detailed error for debugging
                error_log("Failed to update note: " . $stmt->error);
                echo json_encode(['status' => 'error', 'message' => 'Failed to update note']);
            }
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Title or message is too long']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID, title, and message cannot be empty']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
