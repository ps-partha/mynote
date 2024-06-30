<?php
session_name("Session1");
session_start();
session_destroy();
header("Location: index.php");
exit;
?>
