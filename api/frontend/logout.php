<?php
// logout.php
session_start();

// Hapus semua data session
session_unset();
session_destroy();

// Hapus cookie remember me jika ada
if (isset($_COOKIE['username'])) {
    setcookie("username", "", time() - 3600, "/");
}

// Redirect ke halaman login
header("Location: /index.html");
exit();
?>