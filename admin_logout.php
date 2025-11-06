<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear session and destroy it
$_SESSION = [];
session_destroy();

// Redirect to main homepage instead of admin login
header('Location: index.php');
exit;
?>
