<?php
session_start();

if (!isset($_SESSION["username"])) {
//    header("Location: http://localhost/kurs/auth/login.php");
//    exit();
}
