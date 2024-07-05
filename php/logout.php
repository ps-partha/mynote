<?php
session_start();
session_destroy();
// header("Location: ../index");
// exit;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
  <body></body>
  <script>
    function deleteCookie(name) {
      document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    }
    function logout() {
      deleteCookie("user_email");
      deleteCookie("user_password");
      window.location.href = "http://localhost/partha-sarker/sign-in";
    }
    logout();
  </script>
</html>
