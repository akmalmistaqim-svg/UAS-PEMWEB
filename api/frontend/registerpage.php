<?php
// =====================================================
// PROSES REGISTER - registerpage.php
// =====================================================

session_start();

// ✅ Jika sudah login, langsung ke dashboard
if (isset($_SESSION['id_user'])) {
    header("Location: /api/frontend/dashboard.php");
    exit();
}

// ✅ Path koneksi
include __DIR__ . '/../../backend/koneksi.php';

$error_message   = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama             = trim($_POST['nama'] ?? '');
    $username         = trim($_POST['username'] ?? '');
    $phone            = trim($_POST['phone'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($nama) || empty($username) || empty($phone) ||
        empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "❌ Semua field harus diisi!";
    } elseif ($password !== $confirm_password) {
        $error_message = "❌ Password dan Konfirmasi Password tidak sama!";
    } elseif (strlen($password) < 6) {
        $error_message = "❌ Password harus minimal 6 karakter!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "❌ Format email tidak valid!";
    } else {

        // Cek username
        $stmt = $koneksi->prepare("SELECT id_user FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "❌ Username sudah terdaftar! Gunakan username lain.";
            $stmt->close();
        } else {
            $stmt->close();

            // Cek email
            $stmt = $koneksi->prepare("SELECT id_user FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error_message = "❌ Email sudah terdaftar! Gunakan email lain.";
                $stmt->close();
            } else {
                $stmt->close();

                // Insert user baru
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $koneksi->prepare(
                    "INSERT INTO users (nama_lengkap, username, email, nomor_telepon, password)
                     VALUES (?, ?, ?, ?, ?)"
                );
                $stmt->bind_param("sssss", $nama, $username, $email, $phone, $password_hash);

                if ($stmt->execute()) {
                    $success_message = "✓ Pendaftaran berhasil! Silakan login dengan akun Anda.";
                    // Kosongkan form
                    $nama = $username = $phone = $email = '';
                } else {
                    $error_message = "❌ Terjadi kesalahan saat pendaftaran. Silakan coba lagi.";
                }
                $stmt->close();
            }
        }
    }
}

$koneksi->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register ACC Agro Clima Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        .register-container {
            width: 100%;
            max-width: 450px;
            padding: 2.5rem;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0px 8px 24px rgba(0,0,0,0.08);
        }
        .btn-custom-green { background-color: #4CAF50; border-color: #4CAF50; color: #fff; font-weight: 500; }
        .btn-custom-green:hover { background-color: #43a047; border-color: #43a047; color: #fff; }
        .text-custom-green { color: #4CAF50; }
        .text-custom-green:hover { color: #43a047; }
        .logo-leaf-icon { height: 65px; width: auto; }
        .brand-title { font-size: 2.2rem; line-height: 0.9; letter-spacing: 0.5px; }
        .brand-subtitle { font-size: 1.1rem; color: #3b3b3b; font-weight: 600; line-height: 1; }
    </style>
</head>
<body>
<div class="register-container">
    <div class="text-center mb-4">
        <div class="d-flex align-items-center justify-content-center mb-3" style="gap:12px;">
            <!-- ✅ Path gambar diperbaiki -->
            <img src="/LogoWeb.png" alt="Logo ACC" class="logo-leaf-icon img-fluid">
            <div class="text-start d-flex flex-column justify-content-center mt-1">
                <h1 class="text-custom-green fw-bold mb-0 brand-title">ACC</h1>
                <span class="brand-subtitle mt-1">Agro Clima Care</span>
            </div>
        </div>
        <p class="text-muted mt-2" style="font-size:0.9rem;">Buat akun baru untuk mulai menggunakan layanan</p>
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
            <br><small>Silakan <a href="/api/frontend/loginpage.php" class="text-custom-green fw-bold">login di sini</a></small>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label" style="font-size:0.9rem;">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama"
                   placeholder="Masukkan nama lengkap Anda"
                   value="<?php echo isset($nama) ? htmlspecialchars($nama) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label" style="font-size:0.9rem;">Username</label>
            <input type="text" class="form-control" name="username"
                   placeholder="Buat username Anda"
                   value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label" style="font-size:0.9rem;">Nomor Telepon</label>
            <input type="tel" class="form-control" name="phone"
                   placeholder="Masukkan nomor telepon Anda"
                   value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label" style="font-size:0.9rem;">Email Address</label>
            <input type="email" class="form-control" name="email"
                   placeholder="Masukkan email valid Anda"
                   value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label" style="font-size:0.9rem;">Password</label>
            <input type="password" class="form-control" name="password"
                   placeholder="Buat password yang kuat (min. 6 karakter)" required>
        </div>
        <div class="mb-4">
            <label class="form-label" style="font-size:0.9rem;">Konfirmasi Password</label>
            <input type="password" class="form-control" name="confirm_password"
                   placeholder="Ulangi password Anda" required>
        </div>
        <button type="submit" class="btn btn-custom-green w-100 py-2 mb-3 fw-bold">DAFTAR SEKARANG</button>
        <div class="text-center">
            <p class="mb-0" style="font-size:0.85rem;">Sudah punya akun?
                <a href="/api/frontend/loginpage.php" class="text-decoration-none text-custom-green fw-bold">Login di sini</a>
            </p>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>