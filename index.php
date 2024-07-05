<?php
session_start();
include("./php/conf.php");
if (!isset($_SESSION['user_email']) && !isset($_SESSION['user_name']) && !isset($_SESSION['user_id'])) {
    header("Location: ./sign-in");
    exit;
}else{
    header("Location: ./notes/");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    
</body>
<script>
    function getCookie(name) {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop().split(";").shift();
    }
    const userEmail = getCookie("user_email");
    const userPassword = getCookie("user_password");

    if (userEmail && userPassword) {
        window.location.href = "http://localhost/partha-sarker/notes/";
    }
    else{
        window.location.href = "http://localhost/partha-sarker/sign-in"; 
    }
</script>
</html>