<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Peminjaman Baru - Toshokan</title>
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
            <a href="{{ route('books.index') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                <span class="text-xl">📚</span><span>Kelola Buku</span>
            </a>
            
            {{-- Kelola Peminjaman --}}
            <a href="{{ route('borrowings.index') }}" class="flex items-center space-x-3 p-3 rounded-lg bg-white/20 font-semibold transition hover:bg-white/30">
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
        <div class="max-w-6xl mx-auto">
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
                <h1 class="text-3xl font-bold text-gray-900">Buat Peminjaman Baru</h1>
                <a href="{{ route('borrowings.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Peminjaman
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Form Peminjaman --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-6">Form Peminjaman Baru</h2>
                        
                        <form method="POST" action="{{ route('borrowings.store') }}">
                            @csrf
                            
                            {{-- Pilih Pengguna --}}
                            <div class="form-group mb-6">
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Pengguna <span class="text-red-500">*</span></label>
                                <select id="user_id" name="user_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Pilih pengguna yang akan meminjam</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->username }})
                                            @if($user->email) - {{ $user->email }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            {{-- Pilih Buku --}}
                            <div class="form-group mb-6">
                                <label for="buku_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Buku <span class="text-red-500">*</span></label>
                                <select id="buku_id" name="buku_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Pilih buku yang akan dipinjam</option>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}" {{ old('buku_id') == $book->id ? 'selected' : '' }}
                                                data-available="{{ $book->available_copies }}">
                                            {{ $book->title }} oleh {{ $book->author }}
                                            ({{ ucfirst(str_replace('_', ' ', $book->type)) }}) 
                                            - Tersedia: {{ $book->available_copies }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('buku_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            {{-- Periode Peminjaman --}}
                            <div class="form-group mb-6">
                                <label for="borrow_days" class="block text-sm font-medium text-gray-700 mb-2">Periode Peminjaman <span class="text-red-500">*</span></label>
                                <select id="borrow_days" name="borrow_days" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="7" {{ old('borrow_days', 7) == 7 ? 'selected' : '' }}>7 hari (Standar)</option>
                                    <option value="3" {{ old('borrow_days') == 3 ? 'selected' : '' }}>3 hari (Jangka pendek)</option>
                                    <option value="14" {{ old('borrow_days') == 14 ? 'selected' : '' }}>14 hari (Diperpanjang)</option>
                                    <option value="21" {{ old('borrow_days') == 21 ? 'selected' : '' }}>21 hari (Jangka panjang)</option>
                                    <option value="30" {{ old('borrow_days') == 30 ? 'selected' : '' }}>30 hari (Maksimal)</option>
                                </select>
                                <p class="mt-1 text-sm text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Denda: Rp 7.000 per hari setelah tanggal jatuh tempo
                                </p>
                                @error('borrow_days')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            {{-- Ringkasan Peminjaman --}}
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                <h3 class="font-semibold text-blue-800 mb-2">
                                    <i class="fas fa-calendar-alt mr-2"></i>Ringkasan Peminjaman
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-blue-700"><strong>Tanggal Pinjam:</strong> {{ now()->format('d M Y') }}</p>
                                        <p class="text-blue-700" id="due-date"><strong>Tanggal Kembali:</strong> {{ now()->addDays(7)->format('d M Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-blue-700"><strong>Status:</strong> Dipinjam</p>
                                        <p class="text-blue-700"><strong>Denda (jika terlambat):</strong> Rp 7.000/hari</p>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Submit Buttons --}}
                            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                                <a href="{{ route('borrowings.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition">
                                    <i class="fas fa-times mr-2"></i>Batal
                                </a>
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-semibold transition">
                                    <i class="fas fa-plus mr-2"></i>Buat Peminjaman
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Sidebar Informasi --}}
                <div class="lg:col-span-1 space-y-6">
                    {{-- Statistik --}}
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="font-semibold text-gray-800 mb-4">
                            <i class="fas fa-chart-bar mr-2"></i>Statistik
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Buku Tersedia</span>
                                <span class="font-semibold text-green-600">{{ $books->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Pengguna Aktif</span>
                                <span class="font-semibold text-blue-600">{{ $users->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Peminjaman</span>
                                <span class="font-semibold text-purple-600">{{ $totalBorrowings ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Buku Populer --}}
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="font-semibold text-gray-800 mb-4">
                            <i class="fas fa-fire mr-2"></i>Buku Tersedia
                        </h3>
                        @if($books->count() > 0)
                            <div class="space-y-3 max-h-64 overflow-y-auto">
                                @foreach($books->take(8) as $book)
                                    <div class="flex items-center space-x-3 p-2 bg-gray-50 rounded-lg">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-white text-xs font-bold">{{ $book->available_copies }}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $book->title }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $book->author }}</p>
                                        </div>
                                    </div>
                                @endforeach
                                @if($books->count() > 8)
                                    <p class="text-xs text-gray-500 text-center">... dan {{ $books->count() - 8 }} lainnya</p>
                                @endif
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Tidak ada buku tersedia untuk dipinjam.</p>
                        @endif
                    </div>

                    {{-- Pengguna Aktif --}}
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="font-semibold text-gray-800 mb-4">
                            <i class="fas fa-users mr-2"></i>Pengguna Aktif
                        </h3>
                        @if($users->count() > 0)
                            <div class="space-y-2 max-h-48 overflow-y-auto">
                                @foreach($users->take(6) as $user)
                                    <div class="flex items-center space-x-3 p-2 bg-gray-50 rounded-lg">
                                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-white text-xs font-bold">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $user->username }}</p>
                                        </div>
                                    </div>
                                @endforeach
                                @if($users->count() > 6)
                                    <p class="text-xs text-gray-500 text-center">... dan {{ $users->count() - 6 }} lainnya</p>
                                @endif
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Tidak ada pengguna tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- JavaScript untuk update tanggal jatuh tempo --}}
    <script>
        document.getElementById('borrow_days').addEventListener('change', function() {
            const days = parseInt(this.value);
            const dueDate = new Date();
            dueDate.setDate(dueDate.getDate() + days);
            
            const options = { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric',
                locale: 'id-ID'
            };
            
            document.getElementById('due-date').innerHTML = 
                '<strong>Tanggal Kembali:</strong> ' + dueDate.toLocaleDateString('id-ID', options);
        });
    </script>
</body>
</html>