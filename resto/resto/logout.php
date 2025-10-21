<?php
include 'config.php';

session_destroy();
showNotification('Logout berhasil!', 'success');
header("Location: login.php");
exit();
?>