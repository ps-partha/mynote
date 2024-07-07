<?php
session_start();
include("../php/conf.php");

// Check if the user is authenticated
if (!isset($_SESSION['user_email']) || !isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    exit;
}

// Set appropriate headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$requestMethod = $_SERVER['REQUEST_METHOD'];

function getNoteList($conn) {
    $username = $_SESSION['user_name'];
    $userID = intval($_SESSION['user_id']); // Ensure it's an integer

    $stmt = $conn->prepare("SELECT `id`, `titles`, `messages`, `status`, `created_at` FROM `user_notes` WHERE `user_id` = ? AND `username` = ?");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        return [
            'status' => 'error',
            'message' => 'Failed to prepare statement'
        ];
    }

    $stmt->bind_param("is", $userID, $username);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();
        return [
            'status' => 'success',
            'data' => $data
        ];
    } else {
        error_log("Execute failed: " . $stmt->error);
        return [
            'status' => 'error',
            'message' => 'Failed to retrieve notes'
        ];
    }
}

if ($requestMethod == "GET") {
    $noteList = getNoteList($conn);
    echo json_encode($noteList);
} else {
    $data = [
        'status' => 405,
        'message' => $requestMethod . ' Method Not Allowed'
    ];
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode($data);
}
?>
