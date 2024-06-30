<?php
include('conf.php');
session_name("Session1");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $checkbox = isset($_POST['remember_me']) ? $_POST['remember_me'] : '';

    $stmt = $conn->prepare("SELECT `id`, `username`, `password`, `name` FROM `administrator` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashedPassword, $name);
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            // Set session variables
            $_SESSION['Id'] = $id;
            $_SESSION['Username'] = $username;
            $_SESSION['Email'] = $email;
            $_SESSION['Name'] = $name;
            session_write_close();
            if ($checkbox === "checked") {
                $response = array("status" => "success", "message" => "Admin login successfully.", "checkbox" => "checked");
            } else {
                $response = array("status" => "success", "message" => "Admin login successfully.", "checkbox" => "unchecked");
            }
        } else {
            $response = array("status" => "error", "message" => "Invalid Password!");
        }
    } else {
        $response = array("status" => "error", "message" => "Invalid Email!");
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
