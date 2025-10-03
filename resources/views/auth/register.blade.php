<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar</title>

    <!-- Memuat Tailwind CSS dan Font Inter (Penting untuk tampilan modern) -->
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
        
        <!-- Card Register Utama -->
        <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-xl shadow-lg border border-gray-100">
            <div>
                <!-- Header Card -->
                <p class="text-sm font-medium text-gray-500">
                    Silakan isi detail Anda untuk mendaftar
                </p>
                <h2 class="mt-1 text-3xl font-extrabold text-gray-900">
                    Daftar ke Sistem Perpustakaan
                </h2>
            </div>

            <form class="space-y-6" method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="space-y-4">
                    <!-- Input Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required 
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('name') border-red-500 @enderror"
                               placeholder="Masukkan nama lengkap Anda">
                        @error('name')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Input Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input id="username" name="username" type="text" value="{{ old('username') }}" required 
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('username') border-red-500 @enderror"
                               placeholder="Pilih username">
                        @error('username')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Input Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('email') border-red-500 @enderror"
                               placeholder="Masukkan alamat email Anda">
                        @error('email')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Input Nomor Telepon -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required 
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('phone') border-red-500 @enderror"
                               placeholder="Masukkan nomor telepon Anda">
                        @error('phone')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Input Alamat -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea id="address" name="address" rows="3" required 
                                  class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('address') border-red-500 @enderror"
                                  placeholder="Masukkan alamat Anda">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Input Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                        <input id="password" name="password" type="password" required 
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('password') border-red-500 @enderror"
                               placeholder="Buat kata sandi">
                        @error('password')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Input Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Kata Sandi</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('password_confirmation') border-red-500 @enderror"
                               placeholder="Konfirmasi kata sandi Anda">
                        @error('password_confirmation')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tombol Daftar -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out transform hover:scale-[1.01] active:scale-95">
                        Daftar
                    </button>
                </div>
            </form>
            
            <!-- Link Masuk -->
            <p class="text-center text-sm text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>
</body>
</html>