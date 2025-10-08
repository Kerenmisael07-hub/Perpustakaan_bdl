<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Peminjaman - Toshokan</title>
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
                    <h1 class="text-3xl font-black text-gray-800 mb-2">📋 Kelola Peminjaman</h1>
                    <p class="text-gray-600">Kelola semua peminjaman buku di perpustakaan</p>
                </div>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('borrowings.create') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 transition">
                        <i class="fas fa-plus mr-2"></i>
                        Buat Peminjaman Baru
                    </a>
                @endif
            </div>
            {{-- Filter Card --}}
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-filter text-blue-500 mr-2"></i>
                    Filter Peminjaman
                </h2>
                <form method="GET" action="{{ route('borrowings.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select id="status" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                            <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat Dikembalikan</option>
                        </select>
                    </div>
                    
                    @if(auth()->user()->isAdmin())
                    <div>
                        <label for="overdue" class="block text-sm font-semibold text-gray-700 mb-2">Hanya Terlambat</label>
                        <select id="overdue" name="overdue" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Peminjaman</option>
                            <option value="1" {{ request('overdue') == '1' ? 'selected' : '' }}>Hanya Terlambat</option>
                        </select>
                    </div>
                    @endif
                    
                    <div class="flex flex-col justify-end">
                        <div class="flex space-x-3">
                            <button type="submit" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                                <i class="fas fa-search mr-2"></i>
                                Filter
                            </button>
                            @if(request()->anyFilled(['status', 'overdue']))
                                <a href="{{ route('borrowings.index') }}" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg shadow hover:bg-gray-700 transition">
                                    <i class="fas fa-undo mr-2"></i>
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            {{-- Borrowings Table --}}
            @if($borrowings->count() > 0)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-max">
                            <thead>
                                <tr class="bg-blue-600 text-white">
                                    <th class="px-4 py-4 text-left font-semibold min-w-[150px]">User</th>
                                    <th class="px-4 py-4 text-left font-semibold min-w-[200px]">Buku</th>
                                    <th class="px-4 py-4 text-left font-semibold min-w-[100px]">Tipe</th>
                                    <th class="px-4 py-4 text-left font-semibold min-w-[120px]">Tanggal Pinjam</th>
                                    <th class="px-4 py-4 text-left font-semibold min-w-[140px]">Tanggal Jatuh Tempo</th>
                                    <th class="px-4 py-4 text-left font-semibold min-w-[120px]">Tanggal Kembali</th>
                                    <th class="px-4 py-4 text-left font-semibold min-w-[120px]">Status</th>
                                    <th class="px-4 py-4 text-left font-semibold min-w-[150px]">Denda</th>
                                    <th class="px-4 py-4 text-left font-semibold min-w-[180px]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($borrowings as $borrowing)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-4 py-4">
                                            <div class="font-semibold text-gray-800">{{ $borrowing->user->name }}</div>
                                            <div class="text-sm text-gray-600">{{ $borrowing->user->username }}</div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="font-semibold text-gray-800">{{ $borrowing->buku->title }}</div>
                                            <div class="text-sm text-gray-600">by {{ $borrowing->buku->author }}</div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold whitespace-nowrap">
                                                {{ ucfirst(str_replace('_', ' ', $borrowing->buku->type)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-gray-600 whitespace-nowrap">
                                            {{ $borrowing->tanggal_pinjam->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-gray-600 whitespace-nowrap">{{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}</div>
                                            @if($borrowing->isOverdue())
                                                <div class="text-red-600 font-semibold text-sm mt-1 whitespace-nowrap">
                                                    {{ $borrowing->getDaysOverdue() }} hari terlambat
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-gray-600 whitespace-nowrap">
                                            {{ $borrowing->tanggal_kembali_aktual ? $borrowing->tanggal_kembali_aktual->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-4">
                                            @if($borrowing->isOverdue())
                                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold whitespace-nowrap">
                                                    Terlambat
                                                </span>
                                            @elseif($borrowing->tanggal_kembali_aktual)
                                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold whitespace-nowrap">
                                                    Dikembalikan
                                                </span>
                                            @else
                                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold whitespace-nowrap">
                                                    Dipinjam
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            @if($borrowing->denda)
                                                <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold whitespace-nowrap inline-block">
                                                    Rp {{ number_format($borrowing->denda, 0, ',', '.') }}
                                                </span>
                                            @elseif($borrowing->isOverdue())
                                                <span class="bg-orange-600 text-white px-3 py-1 rounded-full text-sm font-semibold whitespace-nowrap inline-block">
                                                    Rp {{ number_format($borrowing->calculateFine(), 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 whitespace-nowrap">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex flex-col sm:flex-row gap-2">
                                                <a href="{{ route('borrowings.show', $borrowing->id) }}" class="bg-blue-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-600 transition-colors inline-flex items-center justify-center whitespace-nowrap">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Lihat
                                                </a>
                                                @if(!$borrowing->tanggal_kembali_aktual && auth()->user()->isAdmin())
                                                    <form method="POST" action="{{ route('borrowings.return', $borrowing->id) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="bg-green-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-green-600 transition-colors inline-flex items-center justify-center whitespace-nowrap w-full" onclick="return confirm('Tandai sebagai dikembalikan?')">
                                                            <i class="fas fa-undo mr-1"></i>
                                                            Kembalikan
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- Pagination --}}
                @if($borrowings->hasPages())
                    <div class="bg-white rounded-xl shadow-lg p-6 mt-8">
                        <div class="flex justify-center items-center gap-4">
                            @if(!$borrowings->onFirstPage())
                                <a href="{{ $borrowings->previousPageUrl() }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                                    <i class="fas fa-chevron-left mr-2"></i>
                                    Previous
                                </a>
                            @else
                                <span class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-600 font-semibold rounded-lg cursor-not-allowed">
                                    <i class="fas fa-chevron-left mr-2"></i>
                                    Previous
                                </span>
                            @endif
                            
                            <span class="text-gray-600 font-medium">
                                Page {{ $borrowings->currentPage() }} of {{ $borrowings->lastPage() }}
                            </span>
                            
                            @if($borrowings->hasMorePages())
                                <a href="{{ $borrowings->nextPageUrl() }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                                    Next
                                    <i class="fas fa-chevron-right ml-2"></i>
                                </a>
                            @else
                                <span class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-600 font-semibold rounded-lg cursor-not-allowed">
                                    Next
                                    <i class="fas fa-chevron-right ml-2"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <div class="text-6xl text-gray-400 mb-6">
                        📋
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Tidak ada peminjaman ditemukan</h3>
                    <p class="text-gray-600 mb-8 text-lg">Coba sesuaikan filter Anda atau buat peminjaman baru.</p>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('borrowings.create') }}" class="inline-flex items-center px-8 py-4 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 transition text-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Buat Peminjaman Baru
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </main>
</body>
</html>
        </div>
    </main>
</body>
</html>