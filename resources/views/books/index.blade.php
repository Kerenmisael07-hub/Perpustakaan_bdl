<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku - Toshokan</title>
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
        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        }
        .book-card {
            background-color: #ffffff;
            border-radius: 12px; /* Lebih rounded */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08); /* Bayangan lebih halus */
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
            cursor: pointer;
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
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
    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100 {{ request()->routeIs('dashboard') ? 'bg-white/30 font-semibold opacity-100' : '' }}">
        <span class="text-xl">🏠</span><span>Dashboard</span>
    </a>
    
    {{-- Kelola Buku --}}
    <a href="{{ route('books.index') }}" class="flex items-center space-x-3 p-3 rounded-lg bg-white/20 font-semibold transition hover:bg-white/30">
        <span class="text-xl">📚</span><span>Kelola Buku</span>
    </a>
    
    {{-- Kelola Peminjaman --}}
    <a href="{{ route('borrowings.index') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100 {{ request()->routeIs('borrowings.index') ? 'bg-white/30 font-semibold opacity-100' : '' }}">
        <span class="text-xl">📋</span><span>Kelola Peminjaman</span>
    </a>
    
    {{-- Buku Terlambat --}}
    <a href="{{ route('borrowings.overdue') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100 {{ request()->routeIs('borrowings.overdue') ? 'bg-white/30 font-semibold opacity-100' : '' }}">
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
        <div class="max-w-7xl mx-auto">
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

            {{-- Header --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Kelola Buku Perpustakaan</h1>
                <a href="{{ route('books.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-plus mr-2"></i> Tambah Buku Baru
                </a>
            </div>

            {{-- Search and Filters --}}
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('books.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="flex-1">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Buku</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" 
                               placeholder="Cari berdasarkan judul, penulis, atau ISBN..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    
                    <div class="flex-1">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                        <select id="type" name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Semua Jenis</option>
                            <option value="light_novel" {{ request('type') == 'light_novel' ? 'selected' : '' }}>Light Novel</option>
                            <option value="manga" {{ request('type') == 'manga' ? 'selected' : '' }}>Manga</option>
                        </select>
                    </div>
                    
                    <div class="flex-1">
                        <label for="available" class="block text-sm font-medium text-gray-700 mb-2">Ketersediaan</label>
                        <select id="available" name="available" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Semua</option>
                            <option value="1" {{ request('available') == '1' ? 'selected' : '' }}>Hanya Tersedia</option>
                        </select>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-semibold transition">Cari</button>
                        @if(request()->anyFilled(['search', 'type', 'available']))
                            <a href="{{ route('books.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold transition">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Books Grid --}}
            @if($books->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
                    @foreach($books as $book)
                        <div class="book-card">
                            <div class="h-64 flex items-center justify-center overflow-hidden bg-gray-100 rounded-t-xl">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                        alt="{{ $book->title }}" 
                                        class="w-full h-full object-cover">
                                @else
                                    <span class="text-gray-400 text-center px-4">Tidak Ada Gambar</span>
                                @endif
                            </div>
                            
                            <div class="p-4">
                                <h3 class="font-bold text-lg text-gray-900 mb-1 line-clamp-2">{{ $book->title }}</h3>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-user mr-1"></i>{{ $book->author }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-calendar-alt mr-1"></i>{{ $book->publication_date ? $book->publication_date->format('Y') : 'N/A' }}
                                </p>
                                <p class="text-sm text-gray-500 mt-2">
                                    <i class="fas fa-book mr-1"></i>{{ ucfirst(str_replace('_', ' ', $book->type)) }}
                                </p>
                                <p class="text-sm mt-2">
                                    <span class="inline-block px-2 py-1 text-xs rounded-full {{ $book->available_copies > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $book->available_copies > 0 ? 'Tersedia (' . $book->available_copies . ')' : 'Tidak Tersedia' }}
                                    </span>
                                </p>
                                
                                {{-- Admin Actions --}}
                                <div class="mt-3 flex gap-2">
                                    <a href="{{ route('books.edit', $book) }}" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-2 rounded text-center text-sm font-semibold transition">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <form method="POST" action="{{ route('books.destroy', $book) }}" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-1 px-2 rounded text-sm font-semibold transition">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            {{-- Admin tidak memiliki tombol pinjam --}}
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($books->hasPages())
                    <div class="flex justify-center items-center gap-4 mt-8">
                        @if(!$books->onFirstPage())
                            <a href="{{ $books->previousPageUrl() }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                                <i class="fas fa-chevron-left mr-2"></i> Sebelumnya
                            </a>
                        @endif
                        
                        <span class="text-gray-700 font-semibold">Halaman {{ $books->currentPage() }} dari {{ $books->lastPage() }}</span>
                        
                        @if($books->hasMorePages())
                            <a href="{{ $books->nextPageUrl() }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                                Selanjutnya <i class="fas fa-chevron-right mr-2"></i>
                            </a>
                        @endif
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                    <i class="fas fa-books text-6xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Buku Ditemukan</h3>
                    @if(request()->anyFilled(['search', 'type', 'available']))
                        <p class="text-gray-600 mb-4">Tidak ada buku yang sesuai dengan kriteria pencarian Anda.</p>
                        <a href="{{ route('books.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-list mr-2"></i> Lihat Semua Buku
                        </a>
                    @else
                        <p class="text-gray-600 mb-4">Belum ada buku dalam koleksi perpustakaan.</p>
                        <a href="{{ route('books.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-plus mr-2"></i> Tambah Buku Pertama
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </main>

    {{-- Font Awesome untuk icons --}}
    <script src="https://kit.fontawesome.com/your-kit-id.js" crossorigin="anonymous"></script>
</body>
</html>
