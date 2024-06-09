<?php
include('conf.php');
$msg = "";   
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['password'];
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($email);
        $stmt->fetch();
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $url = 'sign-in.php?status=successful';
        header("Location: $url");
        exit;
    } else {
        $msg =  "Invalid token.";
    }

    $stmt->close();
    $conn->close();
} else {
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
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
    <link rel="stylesheet" href="style.css" />
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
        <form action="reset_password.php" method="post">
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
            <a href="sign-in.php?status=signin"
              >Don't have an account? <span class="signBtn">Sign up</span></a
            >
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
