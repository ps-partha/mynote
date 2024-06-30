<?php
session_start();
include("conf.php");
if (!isset($_SESSION['user_email']) && !isset($_SESSION['user_name']) && !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_SESSION['user_name'];
    $stmt = $conn->prepare("SELECT `titles`, `messages` FROM `user_notes` WHERE `id` = ? AND `username` = ?");
    $stmt->bind_param("is", $id, $username); // 'i' for integer and 's' for string
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($titles, $messages);
        while ($stmt->fetch()) {
            $response = array("titles" => $titles, "messages" => $messages);
            echo json_encode($response);
            exit();
        }
    } else {
        echo "<br/>No notes available";
    }
    $stmt->close();
}
else {
    header('Content-Type: application/json');
    echo json_encode(array("status" => "error", "message" => "Invalid request method."));
    exit;
}
?>