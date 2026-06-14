<?php
define('SECRET_KEY', 'rahasia123acc2026');

function create_auth_cookie($user) {
    $token = base64_encode(json_encode([
        'id_user'      => $user['id_user'],
        'username'     => $user['username'],
        'nama_lengkap' => $user['nama_lengkap'],
        'email'        => $user['email'],
        'exp'          => time() + 86400
    ]));

    $signature = hash_hmac('sha256', $token, SECRET_KEY);
    $cookie_value = $token . '.' . $signature;

    setcookie('acc_auth', $cookie_value, [
        'expires'  => time() + 86400,
        'path'     => '/',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

function verify_auth() {
    if (!isset($_COOKIE['acc_auth'])) return false;

    $parts = explode('.', $_COOKIE['acc_auth']);
    if (count($parts) !== 2) return false;

    [$token, $signature] = $parts;
    $expected = hash_hmac('sha256', $token, SECRET_KEY);

    if (!hash_equals($expected, $signature)) return false;

    $data = json_decode(base64_decode($token), true);
    if (!$data || $data['exp'] < time()) return false;

    return $data;
}
?>