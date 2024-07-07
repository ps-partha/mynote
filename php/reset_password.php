<?php
include("conf.php");
header('Content-Type: application/json');
$msg = "";   

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = sanitize_input($_POST['token']);
    $newPassword = $_POST['password'];

    // Check if token and new password are provided
    if (empty($token) || empty($newPassword)) {
        echo json_encode(['status' => 'error', 'message' => 'Token and password are required']);
        exit;
    }

    // Validate password strength (example: minimum 8 characters, at least one letter and one number)
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $newPassword)) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters long and include at least one letter and one number']);
        exit;
    }

    // Check if token exists in the password_resets table
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ?");
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($email);
        $stmt->fetch();

        // Update user's password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        if ($update_stmt === false) {
            echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
            exit;
        }
        $update_stmt->bind_param("ss", $hashedPassword, $email);
        $update_stmt->execute();

        // Delete the token from password_resets table
        $delete_stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        if ($delete_stmt === false) {
            echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
            exit;
        }
        $delete_stmt->bind_param("s", $email);
        $delete_stmt->execute();

        $url = '../sign-in?status=successful';
        header("Location: $url");
        exit;
    } else {
        $msg = "Invalid token.";
        echo json_encode(['status' => 'error', 'message' => $msg]);
    }

    $stmt->close();
    $conn->close();
} else {
    if (isset($_GET['token'])) {
        $token = sanitize_input($_GET['token']);
    } else {
        die("Token not provided.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
      rel="stylesheet"
    />
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <title>Forget Password</title>
  </head>

  <body class="bg-gray-200">
    <div class="contanear">
      <div class="login-section">
        <h3>Forget Password</h3>
        <form action="reset_password" method="post">
          <p id="massage"><?php echo $msg;?></p>
           <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
          <input
            type="password"
            name="password"
            id="password"
            placeholder="Enter new password"
            required
          />
          <div class="button">
            <button type="submit" class="btn">Reset Password</button>
          </div>
          <div class="accCreate">
            <a href="../sign-in?status=signin"
              >Don't have an account? <span class="signBtn">Sign up</span></a
            >
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
