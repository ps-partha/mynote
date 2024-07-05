<?php
header('Content-Type: application/json');
session_start();
include("conf.php");

// Check if the user is authenticated
if (!isset($_SESSION['user_email']) || !isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

if (isset($_POST['query'])) {
    $username = $_SESSION['user_name'];
    $userID = $_SESSION['user_id'];
    $query = $_POST['query'];

    // Adjusted SQL query with proper parentheses around OR conditions
    $sql = "SELECT * FROM user_notes WHERE (`titles` LIKE ? OR `messages` LIKE ?) AND `username`= ? AND `user_id`= ?";
    $stmt = $conn->prepare($sql);

    $searchTerm = "%" . $query . "%";
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $username, $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    $notes = [];
    while ($row = $result->fetch_assoc()) {
        $notes[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $notes]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No search query provided']);
}
?>
