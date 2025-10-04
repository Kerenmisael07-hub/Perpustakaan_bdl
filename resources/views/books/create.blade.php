<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku Baru - Toshokan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-z1w1d/r6Q6a1g+D99+X3P8sA8+f0I/N3n7+T3w9a/kE5e4rFvG2rV1V1aB1e5+fN7k2i8W6/5+a7H7/x+cQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
        .font-sans { font-family: 'Inter', sans-serif; }
        .gradient-sidebar {
            background: linear-gradient(180deg, #0093E9 0%, #37a0ff 100%);
        }
        .text-shadow-light {
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="font-sans min-h-screen bg-gray-50 flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside class="hidden lg:block w-64 gradient-sidebar text-white p-6 shadow-2xl flex-shrink-0 overflow-y-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-black text-shadow-light">Toshokan</h1>
            <p class="text-xs opacity-80 mt-1">Dashboard Admin</p>
        </div>

        <div class="flex flex-col items-center mb-6 border-b border-white/20 pb-4">
            <img src="https://placehold.co/80x80/FFFFFF/000?text=ADMIN" alt="Avatar" class="w-20 h-20 rounded-full border-4 border-white shadow-lg">
            <p class="mt-3 font-semibold text-lg">{{ auth()->user()->name }}</p>
            <p class="text-xs opacity-75">{{ now()->locale('id')->isoFormat('dddd, D MMM Y') }}</p>
        </div>

        <nav class="space-y-2">
            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                <span class="text-xl">🏠</span><span>Dashboard</span>
            </a>
            
            {{-- Kelola Buku --}}
            <a href="{{ route('books.index') }}" class="flex items-center space-x-3 p-3 rounded-lg bg-white/20 font-semibold transition hover:bg-white/30">
                <span class="text-xl">📚</span><span>Kelola Buku</span>
            </a>
            
            {{-- Kelola Peminjaman --}}
            <a href="{{ route('borrowings.index') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                <span class="text-xl">📋</span><span>Kelola Peminjaman</span>
            </a>
            
            {{-- Buku Terlambat --}}
            <a href="{{ route('borrowings.overdue') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                <span class="text-xl">⚠️</span><span>Buku Terlambat</span>
            </a>
            
            {{-- Keluar --}}
            <a href="{{ route('logout') }}" 
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-red-500/50 hover:opacity-100">
                <span class="text-xl">🚪</span><span>Keluar</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 p-4 sm:p-8 overflow-y-auto">
        <div class="max-w-4xl mx-auto">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span class="font-semibold">Terdapat kesalahan dalam pengisian form:</span>
                    </div>
                    <ul class="list-disc list-inside ml-6">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Header --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Tambah Buku Baru</h1>
                <a href="{{ route('books.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Buku
                </a>
            </div>

            {{-- Form Card --}}
            <div class="bg-white rounded-xl shadow-lg p-6">
                <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Judul --}}
                        <div class="form-group">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required 
                                   placeholder="Masukkan judul buku"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Penulis --}}
                        <div class="form-group">
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-2">Penulis <span class="text-red-500">*</span></label>
                            <input type="text" id="author" name="author" value="{{ old('author') }}" required 
                                   placeholder="Masukkan nama penulis"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('author')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- ISBN --}}
                        <div class="form-group">
                            <label for="isbn" class="block text-sm font-medium text-gray-700 mb-2">ISBN</label>
                            <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}" 
                                   placeholder="Masukkan ISBN (opsional)"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('isbn')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Jenis --}}
                        <div class="form-group">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Jenis <span class="text-red-500">*</span></label>
                            <select id="type" name="type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Pilih jenis</option>
                                <option value="light_novel" {{ old('type') == 'light_novel' ? 'selected' : '' }}>Light Novel</option>
                                <option value="manga" {{ old('type') == 'manga' ? 'selected' : '' }}>Manga</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Penerbit --}}
                        <div class="form-group">
                            <label for="publisher" class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                            <input type="text" id="publisher" name="publisher" value="{{ old('publisher') }}" 
                                   placeholder="Masukkan nama penerbit"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('publisher')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Tanggal Terbit --}}
                        <div class="form-group">
                            <label for="publication_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Terbit</label>
                            <input type="date" id="publication_date" name="publication_date" value="{{ old('publication_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('publication_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Jumlah Eksemplar --}}
                        <div class="form-group">
                            <label for="total_copies" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Eksemplar <span class="text-red-500">*</span></label>
                            <input type="number" id="total_copies" name="total_copies" value="{{ old('total_copies', 1) }}" 
                                   min="1" required placeholder="Jumlah eksemplar"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('total_copies')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Harga --}}
                        <div class="form-group">
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                            <input type="number" id="price" name="price" value="{{ old('price') }}" 
                                   min="0" step="100" placeholder="Masukkan harga"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Deskripsi --}}
                    <div class="form-group mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="description" name="description" rows="4" 
                                  placeholder="Masukkan deskripsi buku"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Cover Image --}}
                    <div class="form-group mt-6">
                        <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Sampul</label>
                        <input type="file" id="cover_image" name="cover_image" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="mt-1 text-sm text-gray-500">Maksimal 2MB. Format yang didukung: JPEG, PNG, JPG, GIF</p>
                        @error('cover_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Submit Buttons --}}
                    <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('books.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-save mr-2"></i>Buat Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>