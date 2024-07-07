<?php

include("conf.php");
header('Content-Type: application/json');
session_set_cookie_params([
    'httponly' => true,
    'secure' => true,
    'samesite' => 'Strict'
]);

session_start();
// Check if the user is authenticated
if (!isset($_SESSION['user_email']) || !isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    exit;
}

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Function to update note status
function updateNoteStatus($noteId, $newStatus) {
    global $conn;

    // Determine the status to be set
    $statusMap = [
        'pinned' => 'unpinned',
        'restore' => 'unpinned',
        'unarchive' => 'unpinned',
        'unpinned' => 'pinned',
        'archive' => 'archived'
    ];

    $status = $statusMap[$newStatus] ?? $newStatus;

    $stmt = $conn->prepare("UPDATE user_notes SET status = ? WHERE id = ?");
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("si", $status, $noteId);
    if ($stmt->execute()) {
        redirectBasedOnStatus($newStatus);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating record: ' . $stmt->error]);
    }

    $stmt->close();
}

// Function to delete note
function deleteNote($noteId) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM user_notes WHERE id = ?");
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("i", $noteId);
    if ($stmt->execute()) {
        header("Location: ../notes/trash");
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting record: ' . $stmt->error]);
    }

    $stmt->close();
}

// Function to redirect based on status
function redirectBasedOnStatus($status) {
    switch ($status) {
        case 'restore':
            header("Location: ../notes/trash");
            break;
        case 'unarchive':
            header("Location: ../notes/archive");
            break;
        case 'archive':
            header("Location: ../notes/");
            break;
        default:
            header("Location: ../notes/");
            break;
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id']) && isset($_GET['status'])) {
        $noteId = intval(sanitize_input($_GET['id']));
        $newStatus = sanitize_input($_GET['status']);
        
        if (in_array($newStatus, ['pinned', 'unpinned', 'restore', 'trash', 'archive', 'unarchive'])) {
            updateNoteStatus($noteId, $newStatus);
        } elseif ($newStatus == 'delete') {
            deleteNote($noteId);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid status']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>
