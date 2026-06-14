<?php
setcookie('acc_auth', '', [
    'expires'  => time() - 3600,
    'path'     => '/',
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);

header("Location: /api/frontend/loginpage.php");
exit();
?>