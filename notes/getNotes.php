<?php
session_start();
include("../php/conf.php");

// Check if the user is authenticated
if (!isset($_SESSION['user_email']) || !isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$requestMethod = $_SERVER['REQUEST_METHOD'];

function getNoteList($conn) {
    $username = $_SESSION['user_name'];
    $userID = intval($_SESSION['user_id']); // Ensure it's an integer
    $stmt = $conn->prepare("SELECT `id`, `titles`, `messages`, `status`, `created_at` FROM `user_notes` WHERE `user_id`= $userID AND `username` = ?");
    $stmt->bind_param("s",$username);
    if ($stmt->execute()) {
        $stmt->bind_result($id, $titles, $messages, $status, $created_at);
        $data = [];
        while ($stmt->fetch()) {
            $data[] = [
                'id' => $id,
                'titles' => $titles,
                'messages' => $messages,
                'status' => $status,
                'created_at' => $created_at
            ];
        }
        $stmt->close();
        return [
            'status' => 'success',
            'data' => $data
        ];
    } else {
        // Debug: Print SQL error
        error_log("SQL Error: " . $stmt->error);
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
    header('HTTP/1.0 405 Method Not Allowed');
    echo json_encode($data);
}
?>