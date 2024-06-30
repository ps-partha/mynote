<?php
include('conf.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $checkbox = isset($_POST['remember-me']) ? $_POST['remember-me'] : '';
    $stmt = $conn->prepare("SELECT `id`, `username`, `password`,`name`,`status` FROM `users` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashedPassword,$name,$status);
        $stmt->fetch();
        if($status == "inactive"){
          $response = array("status" => "inactive", "message" => "Your Account Not Activated By Admin. Please Wait a Few Minutes or contact Admin.");
        }else if (password_verify($password, $hashedPassword)) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $username;
            $_SESSION['user_email'] = $email;
            $_SESSION['name'] = $name;
            if ($checkbox === "checked") {
                $response = array("status" => "success", "message" => "User login successfully.", "checkbox" => "checked");
            } else {
                $response = array("status" => "success", "message" => "User login successfully.", "checkbox" => "unchecked");
            }
        } else {
            $response = array("status" => "error", "message" => "Password Not Matching!.");
        }
    } else {
        $response = array("status" => "error", "message" => "Email Not Matching!.");
    }

    $stmt->close();
    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} else {
    header('Content-Type: application/json');
    echo json_encode(array("status" => "error", "message" => "Invalid request method."));
    exit;
}
?>