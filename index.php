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
