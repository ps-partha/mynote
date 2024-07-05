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
    $title = $_POST['title'];

    if (!empty($id) && !empty($messages)) {
        $stmt = $conn->prepare("UPDATE `user_notes` SET `messages`= ?, `titles`= ? WHERE `id`= ?");
        $stmt->bind_param("sss",$messages,$title,$id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'updated']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to updated']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'id and message cannot be empty']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
