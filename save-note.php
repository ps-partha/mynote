<?php
session_start();
include("conf.php");

header('Content-Type: application/json');

if (!isset($_SESSION['user_email']) && !isset($_SESSION['user_name']) && !isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['Title'];
    $messages = $_POST['Messages'];
    $username = $_SESSION['user_name'];

    if (!empty($title) && !empty($messages)) {
        $stmt = $conn->prepare("INSERT INTO `user_notes` (`username`, `titles`, `messages`, `status`) VALUES (?, ?, ?, 'unpinned')");
        $stmt->bind_param("sss", $username, $title, $messages);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Note saved successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save note']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Title and message cannot be empty']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
