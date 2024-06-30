<?php
include('conf.php');

function updateUserStatus($userId, $newStatus) {
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("si", $newStatus, $userId);
    if ($stmt->execute()) {
        header("location: pending-account.php");
        exit(); // Stop further execution after redirection
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}

function rejectUser($userId) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        header("location: pending-account.php");
        exit(); // Stop further execution after redirection
    } else {
        echo "Error rejecting record: " . $stmt->error;
    }
    $stmt->close();
}

function updateNoteStatus($noteId, $newStatus) {
    global $conn;
    $stmt = $conn->prepare("UPDATE user_notes SET status = ? WHERE id = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("si", $newStatus, $noteId);
    if ($stmt->execute()) {
        header("location: dashboard.php");
        exit(); // Stop further execution after redirection
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}
function DeleteNote($noteId) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM `user_notes` WHERE `id`= ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i",$noteId);
    if ($stmt->execute()) {
        header("location: dashboard.php");
        exit(); // Stop further execution after redirection
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id']) && isset($_GET['status'])) {
        $userId = intval($_GET['id']);
        $newStatus = $_GET['status'];
        if ($newStatus == "reject") {
            rejectUser($userId);
        } elseif ($newStatus == "pinned" || $newStatus == "unpinned") {
            updateNoteStatus($userId, $newStatus);
        }elseif ($newStatus == "delete") {
            DeleteNote($userId);
        } else {
            updateUserStatus($userId, $newStatus);
        }
    }
}else{
    echo "Invalid request";
}

$conn->close();
?>
