<?php
include("conf.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '.\vendor\autoload.php';
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
        $resetLink = "http://localhost/partha-sarker/php/reset_password?token=" . $token;
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
    $mail->setFrom('feeling2point0@gmail.com', 'Admin');
    $mail->addAddress($email);              
    $mail->isHTML(true);
    $mail->Subject = 'Password Reset';
    $mail->Body    = "<html lang='en-US'>
  <head>
    <meta content='text/html; charset=utf-8' http-equiv='Content-Type' />
    <title>Reset Password Email Template</title>
    <meta name='description' content='Reset Password' />
    <style type='text/css'>
      a:hover {
        text-decoration: underline !important;
      }
    </style>
  </head>

  <body
    marginheight='0'
    topmargin='0'
    marginwidth='0'
    style='margin: 0px; background-color: #f2f3f8'
    leftmargin='0'
  >
    <table
      cellspacing='0'
      border='0'
      cellpadding='0'
      width='100%'
      bgcolor='#f2f3f8'
      style='
        @import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700);
        font-family: 'Open Sans', sans-serif;
      '
    >
      <tr>
        <td>
          <table
            style='background-color: #f2f3f8; max-width: 670px; margin: 0 auto'
            width='100%'
            border='0'
            align='center'
            cellpadding='0'
            cellspacing='0'
          >
            <tr>
              <td style='height: 80px'>&nbsp;</td>
            </tr>
            <tr></tr>
            <tr>
              <td style='height: 20px'>&nbsp;</td>
            </tr>
            <tr>
              <td>
                <table
                  width='95%'
                  border='0'
                  align='center'
                  cellpadding='0'
                  cellspacing='0'
                  style='
                    max-width: 670px;
                    background: #fff;
                    border-radius: 3px;
                    text-align: center;
                    -webkit-box-shadow: 0 6px 18px 0 rgba(0, 0, 0, 0.06);
                    -moz-box-shadow: 0 6px 18px 0 rgba(0, 0, 0, 0.06);
                    box-shadow: 0 6px 18px 0 rgba(0, 0, 0, 0.06);
                  '
                >
                  <tr>
                    <td style='height: 40px'>&nbsp;</td>
                  </tr>
                  <tr>
                    <td style='padding: 0 35px'>
                      <h1
                        style='
                          color: #1e1e2d;
                          font-weight: 500;
                          margin: 0;
                          font-size: 32px;
                          font-family: 'Rubik', sans-serif;
                        '
                      >
                        You have requested to reset your password
                      </h1>
                      <span
                        style='
                          display: inline-block;
                          vertical-align: middle;
                          margin: 29px 0 26px;
                          border-bottom: 1px solid #cecece;
                          width: 100px;
                        '
                      ></span>
                      <p
                        style='
                          color: #455056;
                          font-size: 15px;
                          line-height: 24px;
                          margin: 0;
                        '
                      >
                        We are unable to send you the previous password alone. A
                        one-of-a-kind link has been generated for you to use to
                        reset your password. Click the link below and follow the
                        steps to reset your password.
                      </p>
                      <a
                        href='$resetLink'
                        style='
                          background: #20e277;
                          text-decoration: none !important;
                          font-weight: 500;
                          margin-top: 35px;
                          color: #fff;
                          text-transform: uppercase;
                          font-size: 14px;
                          padding: 10px 24px;
                          display: inline-block;
                          border-radius: 50px;
                        '
                        >Reset Password</a
                      >
                    </td>
                  </tr>
                  <tr>
                    <td style='height: 40px'>&nbsp;</td>
                  </tr>
                </table>
              </td>
            </tr>

            <tr>
              <td style='height: 20px'>&nbsp;</td>
            </tr>
            <tr>
              <td style='text-align: center'>
                <p
                  style='
                    font-size: 14px;
                    color: rgba(69, 80, 86, 0.7411764705882353);
                    line-height: 18px;
                    margin: 0 0 0;
                  '
                ></p>
              </td>
            </tr>
            <tr>
              <td style='height: 80px'>&nbsp;</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>";
    if ($mail->send()){
      $url = "sent-email?email=$email";
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
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
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
        <form action="forgot_password" method="post"  >
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
            <a href="sign-in?status=signin"
              >Don't have an account? <span class="signBtn">Sign up</span></a
            >
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
