{{-- resources/views/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengguna</title>
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
            <p class="text-xs opacity-80 mt-1">Dashboard Pengguna</p>
        </div>

        <div class="flex flex-col items-center mb-6 border-b border-white/20 pb-4">
            <img src="https://placehold.co/80x80/FFFFFF/000?text=USER" alt="Avatar" class="w-20 h-20 rounded-full border-4 border-white shadow-lg">
            <p class="mt-3 font-semibold text-lg">{{ auth()->user()->name }}</p>
            <p class="text-xs opacity-75">{{ now()->locale('id')->isoFormat('dddd, D MMM Y') }}</p>
        </div>

        <nav class="space-y-2">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg bg-white/20 font-semibold transition hover:bg-white/30">
                <span class="text-xl">🏠</span><span>Dashboard</span>
            </a>
            <a href="{{ route('books.browse') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                <span class="text-xl">📚</span><span>Jelajahi Buku</span>
            </a>
            <a href="{{ route('borrowings.my') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                <span class="text-xl">📜</span><span>Riwayat Pinjaman</span>
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
                <h2 class="text-xl font-bold text-gray-700">Statistik Utama</h2>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 px-8 pt-0 pb-6">
                @php
                    $stats = [
                        ['label' => 'Sedang Dipinjam', 'value' => $activeBorrowings->count(), 'icon' => '📚', 'color' => 'bg-green-500'],
                        ['label' => 'Total Dipinjam', 'value' => $borrowingHistory->count(), 'icon' => '📑', 'color' => 'bg-yellow-500'],
                        ['label' => 'Denda Total', 'value' => 'Rp '.number_format($totalFines, 0, ',', '.'), 'icon' => '💰', 'color' => 'bg-red-500'],
                        ['label' => 'Buku Tersedia', 'value' => $availableBooks->count(), 'icon' => '📖', 'color' => 'bg-indigo-500'],
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

        {{-- Detail Dashboard (seperti gambar kedua) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Buku yang Sedang Anda Pinjam --}}
            <div class="md:col-span-2 bg-white p-5 rounded-lg shadow">
                <h2 class="font-semibold text-lg mb-3">Buku yang Sedang Anda Pinjam</h2>
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    @if($activeBorrowings->isEmpty())
                        <p class="text-gray-500 mb-3">Anda tidak sedang meminjam buku apapun.</p>
                        <a href="{{ route('books.browse') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Jelajahi Buku
                        </a>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach($activeBorrowings as $borrowing)
                                <li class="py-3 flex justify-between items-start">
                                    <div>
                                        <span class="font-medium text-gray-900">{{ $borrowing->buku->title }}</span>
                                        <p class="text-sm text-gray-500 mt-1">{{ $borrowing->buku->author }}</p>
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

            {{-- Jadwal Pengembalian --}}
            <div class="bg-white p-5 rounded-lg shadow">
                <h2 class="font-semibold text-lg mb-3">Jadwal Pengembalian</h2>
                <div class="p-4 rounded-lg bg-indigo-600 text-white flex justify-between items-center">
                    <div>
                        <p>Hari ini</p>
                        <p class="text-xl font-bold">{{ now()->locale('id')->translatedFormat('l') }}</p>
                    </div>
                    <div class="text-3xl font-bold">{{ now()->format('d M') }}</div>
                </div>
                
                @if($activeBorrowings->isEmpty())
                    <div class="mt-4 text-center text-gray-500">
                        Tidak ada pinjaman aktif.
                    </div>
                @else
                    <div class="mt-4 space-y-2">
                        @php
                            $upcomingReturns = $activeBorrowings->sortBy('tanggal_kembali_rencana')->take(3);
                        @endphp
                        
                        @foreach($upcomingReturns as $borrowing)
                            @php
                                $daysLeft = now()->diffInDays($borrowing->tanggal_kembali_rencana, false);
                                $isOverdue = $borrowing->isOverdue();
                            @endphp
                            
                            <div class="flex justify-between items-center p-2 rounded {{ $isOverdue ? 'bg-red-100' : 'bg-gray-100' }}">
                                <div>
                                    <p class="text-sm font-medium truncate" title="{{ $borrowing->buku->title }}">
                                        {{ strlen($borrowing->buku->title) > 25 ? substr($borrowing->buku->title, 0, 25) . '...' : $borrowing->buku->title }}
                                    </p>
                                    <p class="text-xs {{ $isOverdue ? 'text-red-600' : 'text-gray-500' }}">
                                        {{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    @if($isOverdue)
                                        <span class="text-xs bg-red-500 text-white px-2 py-1 rounded">
                                            Terlambat
                                        </span>
                                    @elseif($daysLeft <= 2)
                                        <span class="text-xs bg-yellow-500 text-white px-2 py-1 rounded">
                                            {{ $daysLeft }} hari
                                        </span>
                                    @else
                                        <span class="text-xs bg-green-500 text-white px-2 py-1 rounded">
                                            {{ $daysLeft }} hari
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            {{-- Aktivitas Terbaru --}}
            <div class="md:col-span-2 bg-white p-5 rounded-lg shadow">
                <h2 class="font-semibold text-lg mb-3">Aktivitas Terbaru</h2>
                @if($borrowingHistory->isEmpty())
                    <div class="bg-gray-50 p-4 rounded-lg text-center text-gray-500">
                        Belum ada riwayat peminjaman.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($borrowingHistory->take(5) as $borrowing)
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    @if($borrowing->status === 'dikembalikan')
                                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check text-white text-sm"></i>
                                        </div>
                                    @elseif($borrowing->status === 'terlambat')
                                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-clock text-white text-sm"></i>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-book text-white text-sm"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $borrowing->buku->title }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ ucfirst($borrowing->status) }} • 
                                        {{ $borrowing->tanggal_pinjam->format('d M Y') }}
                                        @if($borrowing->tanggal_kembali_aktual)
                                            - {{ $borrowing->tanggal_kembali_aktual->format('d M Y') }}
                                        @endif
                                    </p>
                                    @if($borrowing->denda > 0)
                                        <p class="text-xs text-red-600 mt-1">
                                            Denda: Rp {{ number_format($borrowing->denda, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        
                        @if($borrowingHistory->count() > 5)
                            <div class="text-center pt-3">
                                <a href="{{ route('borrowings.my') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                    Lihat Semua Riwayat
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Buku Tersedia (Favorit Anda) --}}
            <div class="bg-white p-5 rounded-lg shadow">
                <h2 class="font-semibold text-lg mb-3">Buku Tersedia (Favorit Anda)</h2>
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <p class="text-gray-500 mb-3">Tidak ada buku tersedia saat ini.</p>
                    <a href="{{ route('books.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Jelajahi Semua Buku
                    </a>
                </div>
            </div>
        </div>

        {{-- Statistik Peminjaman Bulanan --}}
        {{-- <div class="bg-white p-5 rounded-lg shadow mt-6">
            <h2 class="font-semibold text-lg mb-3">Statistik Peminjaman Bulanan</h2>
            <canvas id="borrowChart" height="100"></canvas>
        </div> --}}
    </main>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- <script>
        const ctx = document.getElementById('borrowChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: [12, 19, 3, 5, 8, 13, 20, 15, 18, 4, 2, 6], // contoh data
                    backgroundColor: '#6366F1',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script> --}}
</body>
</html>
