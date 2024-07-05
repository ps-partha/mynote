<?php
session_start();
include("conf.php");

if (!isset($_SESSION['user_email']) && !isset($_SESSION['user_name']) && !isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    exit;
}

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
        $noteId = intval($_GET['id']);
        $newStatus = $_GET['status'];
        if (in_array($newStatus, ['pinned', 'unpinned', 'restore', 'trash', 'archive', 'unarchive'])) {
            updateNoteStatus($noteId, $newStatus);
        } elseif ($newStatus == 'delete') {
            deleteNote($noteId);
        }else {
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
