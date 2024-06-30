<?php
session_start();
include("conf.php");

header('Content-Type: application/json');

if (!isset($_SESSION['user_email']) && !isset($_SESSION['user_name']) && !isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $messages = $_POST['messages'];

    if (!empty($id) && !empty($messages)) {
        $stmt = $conn->prepare("UPDATE `user_notes` SET `messages`= ? WHERE `id`= ?");
        $stmt->bind_param("ss",$messages,$id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Note updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to updated note']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'id and message cannot be empty']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
