<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
include('conf.php');
require 'vendor/autoload.php';
$msg = "";
$mail = new PHPMailer(true);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        $resetLink = "http://localhost/partha-sarker/reset_password.php?token=" . $token;
  try{
    $mail->SMTPDebug = false;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'feeling2point0@gmail.com';
    $mail->Password   = 'wyqdiihrbmxancpe';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;
    // Recipients
    $mail->setFrom('feeling2point0@gmail.com', 'Mailer');
    $mail->addAddress($email);              
    $mail->isHTML(true);
    $mail->Subject = 'Password Reset';
    $mail->Body    = "Click on the following link to reset your password: $resetLink";
    if ($mail->send()){
      $url = "sent-email.php?email=$email";
      header("Location: $url");
      exit;
    }
  }catch(Exception $e) {
    $msg = "Email not sent!";
  }
}
    $stmt->close();
    $conn->close();
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
      <div class="login-section" id="forget_pass">
        <h3>Forget Password</h3>
        <form action="forgot_password.php" method="post"  >
          <p id="massage"><?php echo $msg;?></p>
          <input
            type="email"
            name="email"
            id="email"
            placeholder="Enter your email"
            required
          />
          <div class="button">
            <button type="submit" class="btn">Get Password</button>
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
