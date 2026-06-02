<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ACC Agro Clima Care</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#eff5ef] min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-8 sm:p-10">
        
        <div class="flex flex-col items-center mb-6">
            <img src="path_ke_logo_kamu.png" alt="ACC Agro Clima Care Logo" class="h-20 object-contain mb-2">
            <p class="text-gray-600 text-sm sm:text-base text-center mt-2">Silahkan login untuk melanjutkan</p>
        </div>

        <form action="" method="POST" class="space-y-5">
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email Anda" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4ea951] focus:border-transparent transition-colors text-sm">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password Anda" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4ea951] focus:border-transparent transition-colors text-sm">
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember" 
                    class="h-4 w-4 text-[#4ea951] focus:ring-[#4ea951] border-gray-300 rounded cursor-pointer">
                <label for="remember" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                    Ingat saya
                </label>
            </div>

            <button type="submit" 
                class="w-full bg-[#4ea951] hover:bg-[#3f8c42] text-white font-semibold py-2.5 px-4 rounded-lg transition duration-200 mt-2">
                LOGIN
            </button>
            
        </form>

        <div class="mt-6 text-center text-sm text-gray-700">
            Belum punya akun? <a href="registerpage.php" class="text-[#4ea951] font-semibold hover:underline">Daftar di sini</a>
        </div>

    </div>

</body>
</html>