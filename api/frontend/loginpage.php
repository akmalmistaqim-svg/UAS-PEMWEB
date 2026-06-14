<?php
// =====================================================
// PROSES LOGIN - loginpage.php
// =====================================================

session_start();

// ✅ Jika sudah login, langsung redirect ke dashboard
if (isset($_SESSION['id_user'])) {
    header("Location: /api/frontend/dashboard.php");
    exit();
}

// ✅ Path koneksi disesuaikan (koneksi.php ada di folder backend)
include $_SERVER['DOCUMENT_ROOT'] . '/api/backend/koneksi.php';

$error_message   = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username   = trim($_POST['username'] ?? '');
    $password   = $_POST['password'] ?? '';
    $remember_me = isset($_POST['remember_me']) ? 1 : 0;

    if (empty($username) || empty($password)) {
        $error_message = "❌ Username dan Password harus diisi!";
    } else {

        $sql  = "SELECT id_user, username, nama_lengkap, email, nomor_telepon,
                        password, created_at, updated_at, status
                 FROM users WHERE username = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // ✅ Cek status akun
            if ($user['status'] !== 'aktif') {
                $error_message = "❌ Akun Anda tidak aktif. Hubungi administrator.";
            } elseif (password_verify($password, $user['password'])) {

                // Simpan ke session
                $_SESSION['id_user']      = $user['id_user'];
                $_SESSION['username']     = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['email']        = $user['email'];
                $_SESSION['nomor_telepon']= $user['nomor_telepon'];
                $_SESSION['created_at']   = $user['created_at'];
                $_SESSION['updated_at']   = $user['updated_at'];

                if ($remember_me == 1) {
                    setcookie("username", $username, time() + (86400 * 30), "/");
                }

                header("Location: /api/frontend/dashboard.php");
                exit();

            } else {
                $error_message = "❌ Password salah! Silakan coba lagi.";
            }
        } else {
            $error_message = "❌ Username tidak terdaftar! Silakan daftar terlebih dahulu.";
        }

        $stmt->close();
    }
}

$koneksi->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login ACC Agro Clima Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0px 8px 24px rgba(0,0,0,0.08);
        }
        .btn-custom-green { background-color: #4CAF50; border-color: #4CAF50; color: #fff; font-weight: 500; }
        .btn-custom-green:hover { background-color: #43a047; border-color: #43a047; color: #fff; }
        .text-custom-green { color: #4CAF50; }
        .text-custom-green:hover { color: #43a047; }
        .logo-area { gap: 12px; }
        .logo-leaf-icon { height: 65px; width: auto; }
        .brand-title { font-size: 2.2rem; line-height: 0.9; letter-spacing: 0.5px; }
        .brand-subtitle { font-size: 1.1rem; color: #3b3b3b; font-weight: 600; line-height: 1; }
    </style>
</head>
<body>
<div class="login-container">
    <div class="text-center mb-4">
        <div class="logo-area d-flex align-items-center justify-content-center mb-3">
            <div>
                <!-- ✅ Path gambar diperbaiki -->
                <img src="/LogoWeb.png" alt="Logo ACC" class="logo-leaf-icon img-fluid">
            </div>
            <div class="text-start d-flex flex-column justify-content-center mt-1">
                <h1 class="text-custom-green fw-bold mb-0 brand-title">ACC</h1>
                <span class="brand-subtitle mt-1">Agro Clima Care</span>
            </div>
        </div>
        <p class="text-muted mt-2" style="font-size:0.9rem;">Silakan login untuk melanjutkan</p>
    </div>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="usernameInput" class="form-label" style="font-size:0.9rem;">Username</label>
            <input type="text" class="form-control" id="usernameInput" name="username"
                   placeholder="Masukkan username Anda"
                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="passwordInput" class="form-label" style="font-size:0.9rem;">Password</label>
            <input type="password" class="form-control" id="passwordInput" name="password"
                   placeholder="Masukkan password Anda" required>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me">
                <label class="form-check-label" for="rememberMe" style="font-size:0.85rem;">Ingat Saya</label>
            </div>
        </div>
        <button type="submit" class="btn btn-custom-green w-100 py-2 mb-3 fw-bold">LOGIN</button>
        <div class="text-center">
            <p class="mb-0" style="font-size:0.85rem;">Belum punya akun?
                <a href="/api/frontend/registerpage.php" class="text-decoration-none text-custom-green fw-bold">Daftar di sini</a>
            </p>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>