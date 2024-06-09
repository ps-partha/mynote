<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Link Sent</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div class="sent-mail">
      <p>Your Password Reset Link Has Been Sent To your Email</p>
      <span class="email"><?php echo $_GET['email']?></span>
      <p><a href="https://mail.google.com">Open Email</a></p>
    </div>
  </body>
</html>
