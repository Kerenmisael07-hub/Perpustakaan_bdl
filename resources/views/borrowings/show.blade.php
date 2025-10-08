<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peminjaman - Toshokan</title>
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

            {{-- Header --}}
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-black text-gray-800 mb-2">📋 Detail Peminjaman</h1>
                    <p class="text-gray-600">Informasi lengkap peminjaman buku</p>
                </div>
                <a href="{{ auth()->user()->isAdmin() ? route('borrowings.index') : route('borrowings.my') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>

            {{-- Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Book Information Card --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-book text-blue-500 mr-2"></i>
                        Informasi Buku
                    </h3>
                    
                    @if($borrowing->buku->cover_image)
                        <div class="flex justify-center mb-6">
                            <img src="{{ asset('storage/' . $borrowing->buku->cover_image) }}" 
                                 alt="{{ $borrowing->buku->title }}" 
                                 class="w-48 h-64 object-cover rounded-lg shadow-md">
                        </div>
                    @endif
                    
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-lg font-bold text-gray-800">{{ $borrowing->buku->title }}</h4>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 font-semibold">Penulis:</span>
                                <span class="text-gray-800">{{ $borrowing->buku->author }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 font-semibold">Jenis:</span>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ ucfirst(str_replace('_', ' ', $borrowing->buku->type)) }}
                                </span>
                            </div>
                            
                            @if($borrowing->buku->publisher)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-gray-600 font-semibold">Penerbit:</span>
                                    <span class="text-gray-800">{{ $borrowing->buku->publisher }}</span>
                                </div>
                            @endif
                            
                            @if($borrowing->buku->isbn)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-gray-600 font-semibold">ISBN:</span>
                                    <span class="text-gray-800 font-mono">{{ $borrowing->buku->isbn }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-6">
                            <a href="{{ route('books.show', $borrowing->buku) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg shadow hover:bg-gray-700 transition">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Lihat Detail Buku
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Borrowing Information Card --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-clipboard-list text-green-500 mr-2"></i>
                        Informasi Peminjaman
                    </h3>
                    
                    <div class="space-y-4">
                        @if(auth()->user()->isAdmin())
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-blue-800 mb-2">Informasi Peminjam</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-blue-600">Nama:</span>
                                        <span class="text-blue-800 font-semibold">{{ $borrowing->user->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-600">Username:</span>
                                        <span class="text-blue-800">{{ $borrowing->user->username }}</span>
                                    </div>
                                    @if($borrowing->user->email)
                                        <div class="flex justify-between">
                                            <span class="text-blue-600">Email:</span>
                                            <span class="text-blue-800">{{ $borrowing->user->email }}</span>
                                        </div>
                                    @endif
                                    @if($borrowing->user->phone)
                                        <div class="flex justify-between">
                                            <span class="text-blue-600">Telepon:</span>
                                            <span class="text-blue-800">{{ $borrowing->user->phone }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <div class="grid grid-cols-1 gap-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 font-semibold">Tanggal Pinjam:</span>
                                <span class="text-gray-800">{{ $borrowing->tanggal_pinjam->format('d M Y') }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 font-semibold">Tanggal Jatuh Tempo:</span>
                                <span class="text-gray-800">{{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}</span>
                            </div>
                            
                            @if($borrowing->tanggal_kembali_aktual)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-gray-600 font-semibold">Tanggal Kembali:</span>
                                    <span class="text-gray-800">{{ $borrowing->tanggal_kembali_aktual->format('d M Y') }}</span>
                                </div>
                            @endif
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 font-semibold">Status:</span>
                                <span>
                                    @if($borrowing->status == 'dipinjam' && $borrowing->isOverdue())
                                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">
                                            Terlambat ({{ $borrowing->getDaysOverdue() }} hari)
                                        </span>
                                    @elseif($borrowing->status == 'dipinjam')
                                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                                            Dipinjam
                                        </span>
                                    @elseif($borrowing->status == 'dikembalikan')
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                            Dikembalikan
                                        </span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-semibold">
                                            {{ ucfirst($borrowing->status) }}
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        
                        {{-- Overdue Notice --}}
                        @if($borrowing->status == 'dipinjam' && $borrowing->isOverdue())
                            <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-exclamation-triangle text-red-600 text-lg mr-2"></i>
                                    <h4 class="font-bold text-red-800">Peringatan Keterlambatan</h4>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-red-600">Hari Terlambat:</span>
                                        <span class="text-red-800 font-bold">{{ $borrowing->getDaysOverdue() }} hari</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-red-600">Denda Saat Ini:</span>
                                        <span class="text-red-800 font-bold">Rp {{ number_format($borrowing->calculateFine(), 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-red-600">Tarif Denda:</span>
                                        <span class="text-red-800">Rp 7.000 per hari</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Fine Amount --}}
                        @if($borrowing->denda > 0)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600 font-semibold">Denda Dibayar:</span>
                                <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    Rp {{ number_format($borrowing->denda, 0, ',', '.') }}
                                </span>
                            </div>
                        @endif
                        
                        {{-- Notes --}}
                        @if($borrowing->catatan)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-800 mb-2">Catatan Admin:</h4>
                                <p class="text-gray-600">{{ $borrowing->catatan }}</p>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Actions --}}
                    <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                        @if($borrowing->status == 'dipinjam' && auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('borrowings.return', $borrowing) }}">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 transition" onclick="return confirm('Tandai sebagai dikembalikan?')">
                                    <i class="fas fa-undo mr-2"></i>
                                    Kembalikan Buku
                                </button>
                            </form>
                        @endif
                        
                        @if(auth()->user()->isAdmin())
                            <button onclick="toggleNotesForm()" class="w-full inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg shadow hover:bg-gray-700 transition">
                                <i class="fas fa-sticky-note mr-2"></i>
                                {{ $borrowing->catatan ? 'Edit' : 'Tambah' }} Catatan
                            </button>
                            
                            <div id="notesForm" class="{{ $borrowing->catatan ? '' : 'hidden' }} mt-4 p-4 bg-gray-50 rounded-lg">
                                <form method="POST" action="{{ route('borrowings.update', $borrowing) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-2">Catatan Admin</label>
                                        <textarea id="catatan" name="catatan" rows="3" 
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                  placeholder="Tambahkan catatan tentang peminjaman ini...">{{ $borrowing->catatan }}</textarea>
                                    </div>
                                    <div class="flex space-x-3">
                                        <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                                            <i class="fas fa-save mr-2"></i>
                                            Simpan Catatan
                                        </button>
                                        <button type="button" onclick="toggleNotesForm()" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg shadow hover:bg-gray-700 transition">
                                            <i class="fas fa-times mr-2"></i>
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function toggleNotesForm() {
            const form = document.getElementById('notesForm');
            form.classList.toggle('hidden');
        }
    </script>
</body>
</html>