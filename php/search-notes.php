<?php
header('Content-Type: application/json');
include("conf.php");
// Set secure session cookies
session_set_cookie_params([
    'httponly' => true,
    'secure' => true,
    'samesite' => 'Strict'
]);
session_start();
// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}


// Check if the user is authenticated
if (!isset($_SESSION['user_email']) || !isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

if (isset($_POST['query'])) {
    $username = sanitize_input($_SESSION['user_name']);
    $userID = sanitize_input($_SESSION['user_id']);
    $query = sanitize_input($_POST['query']);

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
