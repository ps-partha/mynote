<?php
include('conf.php');
session_start();
$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT `id`, `username`, `password` FROM `users` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashedPassword);
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            function encrypt_decrypt($action, $string) {
              $output = false;
              $encrypt_method = "AES-256-CBC";
              $secret_key = "8g8D1|Cs9hr0~QbB''4|";
              $secret_iv = '12';
              $key = hash('sha256', $secret_key);
              $iv = substr(hash('sha256', $secret_iv), 0, 10);
              if ( $action == 'encrypt' ) {
                  $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
                  $output = base64_encode($output);
              } else if( $action == 'decrypt' ) {
                  $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
              }
              return $output;
            }
            $hashed = encrypt_decrypt("encrypt",$username);
            header("Location: dashboard.php?user=$hashed");
            exit;
        } else {
            $msg =  "Invalid Password!";
        }
    } else {
        $msg =  "Invalid Email!";
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
    <title>Login</title>
  </head>
  <body class="bg-gray-200">
    <div class="contanear">
      <div class="login-section">
        <h3>Login</h3>
        <form action="" method="post">
          <p class="error"><?php echo $msg;?></p>
          <input
            type="email"
            name="email"
            id="email"
            placeholder="Enter your email"
            required
          />
          <br />
          <input
            type="password"
            name="password"
            id="password"
            placeholder="Enter your password"
            required
          />
          <br />
          <div class="rememberME">
            <input type="checkbox" name="remember-me" id="remember-me" />
            <label for="checkbox">Remember Me</label>
            <a href="forgot_password.php" class="forgotPass">Forget Password</a>
          </div>
          <div class="button">
            <button type="submit" class="btn">Log In</button>
          </div>
          <div class="accCreate">
            <a href="sign-up.php?status=signup"
              >Don't have an account? <span class="signBtn">Sign up</span></a
            >
          </div>
        </form>
      </div>
    </div>
  </body>
  <script>
  $(document).ready(function(){
  $("email").click(function(){
    $(".error").css("dispaly","none");
  });
  $("password").click(function(){
    $(".error").css("dispaly","none");
  });
});
  </script>
</html>
