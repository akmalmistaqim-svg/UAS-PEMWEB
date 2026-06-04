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
            box-shadow: 0px 8px 24px rgba(0, 0, 0, 0.08);
        }

        .btn-custom-green {
            background-color: #4CAF50;
            border-color: #4CAF50;
            color: #ffffff;
            font-weight: 500;
        }

        .btn-custom-green:hover {
            background-color: #43a047;
            border-color: #43a047;
            color: #ffffff;
        }

        .text-custom-green {
            color: #4CAF50;
        }
        
        .text-custom-green:hover {
            color: #43a047;
        }

        .logo-area {
            gap: 12px;
        }
        
        .logo-leaf-icon {
            height: 65px;
            width: auto;
        }
        
        .brand-title {
            font-size: 2.2rem;
            line-height: 0.9;
            letter-spacing: 0.5px;
        }

        .brand-subtitle {
            font-size: 1.1rem;
            color: #3b3b3b;
            font-weight: 600;
            line-height: 1;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <div class="text-center mb-4">
            
            <div class="logo-area d-flex align-items-center justify-content-center mb-3">
                <div>
                    <img src="../LogoWeb.png" alt="Logo ACC" class="logo-leaf-icon img-fluid">
                </div>
                
                <div class="text-start d-flex flex-column justify-content-center mt-1">
                    <h1 class="text-custom-green fw-bold mb-0 brand-title">ACC</h1>
                    <span class="brand-subtitle mt-1">Agro Clima Care</span>
                </div>
            </div>
            
            <p class="text-muted mt-2" style="font-size: 0.9rem;">Buat akun baru untuk mulai menggunakan layanan</p>
        </div>

        <form>
            <div class="mb-3">
                <label for="namaInput" class="form-label" style="font-size: 0.9rem;">Nama Lengkap</label>
                <input type="text" class="form-control" id="namaInput" placeholder="Masukkan nama lengkap Anda" required>
            </div>

            <div class="mb-3">
                <label for="usernameInput" class="form-label" style="font-size: 0.9rem;">Username</label>
                <input type="text" class="form-control" id="usernameInput" placeholder="Buat username Anda" required>
            </div>
            
            <div class="mb-3">
                <label for="phoneInput" class="form-label" style="font-size: 0.9rem;">Nomor Telepon</label>
                <input type="tel" class="form-control" id="phoneInput" placeholder="Masukkan nomor telepon Anda" required>
            </div>
            
            <div class="mb-3">
                <label for="emailInput" class="form-label" style="font-size: 0.9rem;">Email Address</label>
                <input type="email" class="form-control" id="emailInput" placeholder="Masukkan email valid Anda" required>
            </div>
            
            <div class="mb-3">
                <label for="passwordInput" class="form-label" style="font-size: 0.9rem;">Password</label>
                <input type="password" class="form-control" id="passwordInput" placeholder="Buat password yang kuat" required>
            </div>

            <div class="mb-4">
                <label for="confirmPasswordInput" class="form-label" style="font-size: 0.9rem;">Konfirmasi Password</label>
                <input type="password" class="form-control" id="confirmPasswordInput" placeholder="Ulangi password Anda" required>
            </div>
            
            <button type="submit" class="btn btn-custom-green w-100 py-2 mb-3 fw-bold">DAFTAR SEKARANG</button>
            
            <div class="text-center">
                <p class="mb-0" style="font-size: 0.85rem;">Sudah punya akun? 
                    <a href="loginpage.html" class="text-decoration-none text-custom-green fw-bold">Login di sini</a>
                </p>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>