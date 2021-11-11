<?php
session_start();
if (isset($_SESSION['username'])){
$username = $_SESSION['username'];
} else {
$path = $_SERVER['PHP_SELF'];
$file = basename ($path);
if ($file !== 'index.php') header('location: index.php');
}
?>
