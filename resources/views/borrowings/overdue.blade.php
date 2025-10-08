<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Terlambat - Toshokan</title>
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
            <a href="{{ route('borrowings.index') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                <span class="text-xl">📋</span><span>Kelola Peminjaman</span>
            </a>
            
            {{-- Buku Terlambat --}}
            <a href="{{ route('borrowings.overdue') }}" class="flex items-center space-x-3 p-3 rounded-lg bg-white/20 font-semibold transition hover:bg-white/30">
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
    <main class="flex-1 overflow-y-auto">
        <div class="p-6 lg:p-8">
            {{-- Header --}}
            <header class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-black text-gray-800 mb-2">
                            ⚠️ Buku Terlambat
                        </h1>
                        <p class="text-gray-600">Total {{ $overdueBorrowings->count() }} buku terlambat dikembalikan</p>
                    </div>
                    <a href="{{ route('borrowings.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Peminjaman
                    </a>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($overdueBorrowings->count() > 0)
                {{-- Warning Notice --}}
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mr-3"></i>
                        <div>
                            <p class="font-semibold">Catatan Penting:</p>
                            <p>Buku-buku ini telah melewati tanggal jatuh tempo. Denda dihitung Rp 7.000 per hari.</p>
                        </div>
                    </div>
                </div>
                
                {{-- Overdue Books Table --}}
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-red-600 text-white">
                                    <th class="px-6 py-4 text-left font-semibold">User</th>
                                    <th class="px-6 py-4 text-left font-semibold">Contact</th>
                                    <th class="px-6 py-4 text-left font-semibold">Buku</th>
                                    <th class="px-6 py-4 text-left font-semibold">Tanggal Jatuh Tempo</th>
                                    <th class="px-6 py-4 text-left font-semibold">Hari Terlambat</th>
                                    <th class="px-6 py-4 text-left font-semibold">Denda</th>
                                    <th class="px-6 py-4 text-left font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($overdueBorrowings as $borrowing)
                                <tr class="bg-red-50 hover:bg-red-100 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-800">{{ $borrowing->user->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $borrowing->user->username }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($borrowing->user->email)
                                            <div class="text-sm text-gray-600">{{ $borrowing->user->email }}</div>
                                        @endif
                                        @if($borrowing->user->phone)
                                            <div class="text-sm text-gray-600">{{ $borrowing->user->phone }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-800">{{ $borrowing->buku->title }}</div>
                                        <div class="text-sm text-gray-600">by {{ $borrowing->buku->author }}</div>
                                        <div class="text-sm text-blue-600">{{ ucfirst(str_replace('_', ' ', $borrowing->buku->type)) }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">
                                            {{ $borrowing->getDaysOverdue() }} hari
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                            Rp {{ number_format($borrowing->calculateFine(), 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('borrowings.show', $borrowing) }}" class="bg-blue-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-blue-600 transition-colors">
                                                <i class="fas fa-eye mr-1"></i>
                                                Lihat
                                            </a>
                                            <form method="POST" action="{{ route('borrowings.return', $borrowing) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-green-600 transition-colors" onclick="return confirm('Paksa pengembalian buku ini?')">
                                                    <i class="fas fa-undo mr-1"></i>
                                                    Paksa Kembali
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50 border-t-2 border-gray-200">
                                    <td colspan="5" class="px-6 py-4 text-right font-bold text-gray-800">Total Denda:</td>
                                    <td class="px-6 py-4">
                                        <span class="bg-red-600 text-white px-4 py-2 rounded-full font-bold text-lg">
                                            Rp {{ number_format($overdueBorrowings->sum(function($b) { return $b->calculateFine(); }), 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                {{-- Quick Actions --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        Aksi Cepat
                    </h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('borrowings.index', ['status' => 'dipinjam']) }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                            <i class="fas fa-list mr-2"></i>
                            Lihat Semua Peminjaman Aktif
                        </a>
                        <a href="{{ route('borrowings.create') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 transition">
                            <i class="fas fa-plus mr-2"></i>
                            Buat Peminjaman Baru
                        </a>
                    </div>
                </div>
            @else
                {{-- No Overdue Books --}}
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <div class="text-6xl text-green-500 mb-6">
                        🎉
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Tidak Ada Buku Terlambat!</h3>
                    <p class="text-gray-600 mb-8 text-lg">Semua buku yang dipinjam dikembalikan tepat waktu atau masih dalam periode peminjaman.</p>
                    <a href="{{ route('borrowings.index') }}" class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition text-lg">
                        <i class="fas fa-list mr-2"></i>
                        Lihat Semua Peminjaman
                    </a>
                </div>
            @endif
        </div>
    </main>
</body>
</html>