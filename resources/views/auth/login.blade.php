<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login</title>

    <!-- Memuat Tailwind CSS dan Font Inter (Penting untuk tampilan) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Mengatur font Inter */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        
        <!-- Card Login Utama -->
        <div class="w-full max-w-md p-10 space-y-8 bg-white rounded-xl shadow-lg border border-gray-100">
            <div>
                <!-- Header Card -->
                <p class="text-sm font-medium text-gray-500">
                    Silakan masukkan detail Anda
                </p>
                <h2 class="mt-1 text-3xl font-extrabold text-gray-900">
                    Selamat datang kembali
                </h2>
            </div>

            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <!-- Input Email -->
                    <div>
                        <label for="email" class="sr-only">Alamat Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('email') border-red-500 @enderror"
                            placeholder="Alamat Email">
                        @error('email')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Input Password -->
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('password') border-red-500 @enderror"
                            placeholder="Password">
                        @error('password')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Remember Me dan Forgot Password -->
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="remember_me" class="ml-2 block text-gray-900">
                            Ingat saya selama 30 hari
                        </label>
                    </div>
                    <div class="text-blue-600 hover:text-blue-500">
                        <a href="#" class="font-medium">
                            Lupa kata sandi?
                        </a>
                    </div>
                </div>

                <!-- Tombol Utama (Sign In) -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out transform hover:scale-[1.01] active:scale-95">
                        Masuk
                    </button>
                </div>
            </form>
            
            <!-- Link Daftar (Sign Up) -->
            <p class="mt-6 text-center text-sm text-gray-600">
                Belum punya akun?
                <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                    Daftar sekarang
                </a>
            </p>

        </div>
    </div>
</body>
</html>