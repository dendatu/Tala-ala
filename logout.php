<?php
//Log out user by destorying session and redirecting to login
session_start();
session_destroy();
header("Location: login.php");
exit();
?>
