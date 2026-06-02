<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ACC Agro Clima Care</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#eff5ef] min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-8 sm:p-10 my-8">
        
        <div class="flex flex-col items-center mb-6">
            <img src="path_ke_logo_kamu.png" alt="ACC Agro Clima Care Logo" class="h-20 object-contain mb-2">
            <p class="text-gray-600 text-sm sm:text-base text-center mt-2">Buat akun baru untuk mulai menggunakan layanan</p>
        </div>

        <form action="" method="POST" class="space-y-4">
            
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap Anda" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4ea951] focus:border-transparent transition-colors text-sm">
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" id="username" name="username" placeholder="Buat username Anda" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4ea951] focus:border-transparent transition-colors text-sm">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email valid Anda" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4ea951] focus:border-transparent transition-colors text-sm">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" placeholder="Buat password yang kuat" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4ea951] focus:border-transparent transition-colors text-sm">
            </div>

            <div>
                <label for="konfirmasi_password" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" id="konfirmasi_password" name="konfirmasi_password" placeholder="Ulangi password Anda" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4ea951] focus:border-transparent transition-colors text-sm">
            </div>

            <button type="submit" 
                class="w-full bg-[#4ea951] hover:bg-[#3f8c42] text-white font-semibold py-2.5 px-4 rounded-lg transition duration-200 mt-4">
                DAFTAR SEKARANG
            </button>
            
        </form>

        <div class="mt-6 text-center text-sm text-gray-700">
            Sudah punya akun? <a href="loginpage.php" class="text-[#4ea951] font-semibold hover:underline">Login di sini</a>
        </div>

    </div>

</body>
</html>