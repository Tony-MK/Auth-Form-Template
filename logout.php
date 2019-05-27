<?php
session_start();
unset($_SESSION['created']);
unset($_SESSION['userID']);
header('Location: login.php');
?>