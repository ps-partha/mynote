<?php
include('conf.php');
$mag = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $create_datetime = date("Y-m-d H:i:s");
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // Insert user into the database
    $stmt = $conn->prepare("INSERT INTO `users` (`username`,`email`, `password`,`create_datetime`) VALUES (?, ?,?,?)");
    $stmt->bind_param("ssss",$username, $email, $password,$create_datetime);

    if ($stmt->execute()) {
        $mag =  "Registration successful!";
    } else {
        $mag =  "Registration unsuccessful!";
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
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
      rel="stylesheet"
    />
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <link
      rel="apple-touch-icon"
      sizes="76x76"
      href="./assets/img/apple-icon.png"
    />
    <title>Registration</title>
  </head>

  <body class="bg-gray-200">
    <div class="contanear">
      <div class="login-section">
        <h3>Registration</h3>
        <div class="errorbox">
          <!-- <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> -->
        <div class="error" id="errorMessage"></div>
        <p class="errer"><?php echo $mag;?></p>
        </div>
        <form
          id="registration_form" method="post"
        >
          <input
            type="text"
            name="username"
            id="user"
            placeholder="Enter username"
            required
          />
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
          <input
            type="password"
            name="re-password"
            id="re-password"
            placeholder="Enter password again"
            required
          />

          <div class="button" style="margin-top: -15px">
            <button class="btn">Create Account</button>
          </div>
          <div class="accCreate">
            <a href="sign-in.php?status=signin"
              >I have an account? <span class="signBtn">Sign In</span></a
            >
          </div>
        </form>
      </div>
    </div>
  </body>
  <script>
    $(document).ready(function() {
    $('#registration_form').on('submit', function(event) {
        event.preventDefault();
        let password = $('#password').val();
        let repassword = $('#re-password').val();
        if (password === repassword) {
            this.submit();
        } else {
            $('#errorMessage').text("Passwords do not match");
        }
    });
});
  </script>
</html>