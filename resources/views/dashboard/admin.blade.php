{{-- resources/views/dashboard/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
        .font-sans { font-family: 'Inter', sans-serif; }
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
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg bg-white/20 font-semibold transition hover:bg-white/30">
                <span class="text-xl">🏠</span><span>Dashboard</span>
            </a>
            <a href="{{ route('books.index') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                <span class="text-xl">📚</span><span>Kelola Buku</span>
            </a>
            <a href="{{ route('borrowings.index') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                <span class="text-xl">📋</span><span>Kelola Peminjaman</span>
            </a>
            <a href="{{ route('borrowings.overdue') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                <span class="text-xl">⚠️</span><span>Buku Terlambat</span>
            </a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-red-500/50 hover:opacity-100">
                <span class="text-xl">🚪</span><span>Keluar</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 p-4 sm:p-8 overflow-y-auto">
        
        {{-- Statistik Utama --}}
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden w-full mb-6">
            <div class="px-8 pt-6 pb-4">
                <h2 class="text-xl font-bold text-gray-700">Statistik Sistem</h2>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 px-8 pt-0 pb-6">
                @php
                    $stats = [
                        ['label' => 'Total Buku', 'value' => $totalBooks, 'icon' => '📚', 'color' => 'bg-blue-500'],
                        ['label' => 'Total Pengguna', 'value' => $totalUsers, 'icon' => '👥', 'color' => 'bg-green-500'],
                        ['label' => 'Sedang Dipinjam', 'value' => $activeBorrowings, 'icon' => '📖', 'color' => 'bg-yellow-500'],
                        ['label' => 'Terlambat', 'value' => $overdueBorrowings, 'icon' => '⚠️', 'color' => 'bg-red-500'],
                    ];
                @endphp

                @foreach($stats as $stat)
                    <div class="flex flex-col items-center bg-white p-3 rounded-lg shadow-lg border-b-2 border-gray-200 text-center transition hover:shadow-xl">
                        <div class="stat-icon {{ $stat['color'] }} mb-2">
                            <span class="text-xl">{{ $stat['icon'] }}</span>
                        </div>
                        <p class="text-xl font-extrabold text-gray-800 leading-none">{{ $stat['value'] }}</p>
                        <p class="text-xs text-gray-500 font-medium mt-1 uppercase">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Detail Dashboard --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Peminjaman Terbaru --}}
            <div class="md:col-span-2 bg-white p-5 rounded-lg shadow">
                <h2 class="font-semibold text-lg mb-3">Peminjaman Terbaru</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    @if($recentBorrowings->isEmpty())
                        <div class="text-center text-gray-500">
                            <p class="mb-3">Belum ada peminjaman terbaru.</p>
                            <a href="{{ route('borrowings.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Buat Peminjaman Baru
                            </a>
                        </div>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach($recentBorrowings as $borrowing)
                                <li class="py-3 flex justify-between items-start">
                                    <div>
                                        <span class="font-medium text-gray-900">{{ $borrowing->buku->title }}</span>
                                        <p class="text-sm text-gray-500 mt-1">Dipinjam oleh: {{ $borrowing->user->name }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $borrowing->tanggal_pinjam->format('d M Y') }}</p>
                                        @if($borrowing->isOverdue())
                                            <p class="text-xs text-red-600 font-medium mt-1">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Terlambat {{ $borrowing->getDaysOverdue() }} hari
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm {{ $borrowing->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                            Kembali: {{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}
                                        </span>
                                        @if($borrowing->isOverdue())
                                            <p class="text-xs text-red-600 mt-1">
                                                Denda: Rp {{ number_format($borrowing->calculateFine(), 0, ',', '.') }}
                                            </p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            {{-- Sistem Informasi --}}
            <div class="bg-white p-5 rounded-lg shadow">
                <h2 class="font-semibold text-lg mb-3">Sistem Informasi</h2>
                <div class="p-4 rounded-lg bg-indigo-600 text-white flex justify-between items-center">
                    <div>
                        <p>Hari ini</p>
                        <p class="text-xl font-bold">{{ now()->locale('id')->translatedFormat('l') }}</p>
                    </div>
                    <div class="text-3xl font-bold">{{ now()->format('d M') }}</div>
                </div>
                
                <div class="mt-4 space-y-3">
                    <div class="p-3 bg-gray-100 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-700">Total Koleksi</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ $totalBooks }} Buku</p>
                    </div>
                    
                    <div class="p-3 bg-gray-100 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-700">Anggota Aktif</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ $totalUsers }} Pengguna</p>
                    </div>
                    
                    @if($overdueBorrowings > 0)
                        <div class="p-3 bg-red-100 rounded-lg">
                            <h3 class="text-sm font-medium text-red-700">Perlu Perhatian</h3>
                            <p class="text-lg font-semibold text-red-900">{{ $overdueBorrowings }} Buku Terlambat</p>
                            <a href="{{ route('borrowings.overdue') }}" class="text-xs text-red-600 hover:text-red-800 underline">
                                Lihat Detail →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Daftar Buku Terlambat --}}
        @if($overdueList->count() > 0)
            <div class="mt-6 bg-white p-5 rounded-lg shadow">
                <h2 class="font-semibold text-lg mb-3 text-red-600">⚠️ Buku Terlambat Dikembalikan</h2>
                <div class="bg-red-50 p-4 rounded-lg">
                    <div class="space-y-3">
                        @foreach($overdueList->take(5) as $overdue)
                            <div class="flex items-start space-x-3 p-3 bg-white rounded-lg border-l-4 border-red-500">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-exclamation text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $overdue->buku->title }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Dipinjam oleh: {{ $overdue->user->name }} • 
                                        Terlambat {{ $overdue->getDaysOverdue() }} hari
                                    </p>
                                    <p class="text-xs text-red-600 mt-1">
                                        Denda: Rp {{ number_format($overdue->calculateFine(), 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('borrowings.show', $overdue) }}" class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                        Tindak Lanjut
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($overdueList->count() > 5)
                            <div class="text-center pt-3">
                                <a href="{{ route('borrowings.overdue') }}" class="text-red-600 hover:text-red-700 text-sm font-medium">
                                    Lihat Semua ({{ $overdueList->count() }}) →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Quick Actions --}}
        <div class="mt-6 bg-white p-5 rounded-lg shadow">
            <h2 class="font-semibold text-lg mb-3">Aksi Cepat</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('borrowings.create') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <span class="text-2xl mb-2">➕</span>
                    <span class="text-sm font-medium text-blue-700">Buat Peminjaman</span>
                </a>
                
                <a href="{{ route('books.create') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <span class="text-2xl mb-2">📚</span>
                    <span class="text-sm font-medium text-green-700">Tambah Buku</span>
                </a>
                
                <a href="{{ route('borrowings.index') }}" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                    <span class="text-2xl mb-2">📋</span>
                    <span class="text-sm font-medium text-yellow-700">Kelola Peminjaman</span>
                </a>
                
                <a href="{{ route('borrowings.overdue') }}" class="flex flex-col items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                    <span class="text-2xl mb-2">⚠️</span>
                    <span class="text-sm font-medium text-red-700">Buku Terlambat</span>
                </a>
            </div>
        </div>
    </main>
</body>
</html>
