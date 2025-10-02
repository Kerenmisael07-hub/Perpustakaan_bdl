@extends('layouts.app')

@section('title', 'Dashboard Pengguna')

@section('content')
    {{-- Memuat Tailwind CSS untuk tampilan dan styling yang tepat --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Menggunakan font Inter dan warna dasar */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
        .font-sans { font-family: 'Inter', sans-serif; }
        .stat-icon {
            /* Gaya ikon bundar seperti desain iLibrary */
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        }
        /* Gradient khusus untuk card dan sidebar */
        .gradient-sidebar {
            background: linear-gradient(180deg, #0093E9 0%, #37a0ff 100%);
        }
        .text-shadow-light {
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        /* Gaya Bar Chart Simulasi */
        .bar-chart-container {
            height: 180px;
            display: flex;
            align-items: flex-end;
            gap: 8px;
            padding-bottom: 5px;
        }
    </style>

    {{-- KONTEN UTAMA DENGAN GRID: SIDEBAR + MAIN CONTENT --}}
    {{-- Menggunakan 'h-screen' dan 'overflow-hidden' pada div terluar untuk menonaktifkan scrolling body --}}
    <div class="font-sans min-h-screen bg-gray-50 flex h-screen overflow-hidden">
        
        {{-- SIDEBAR KIRI (Navigasi Utama Pengguna) --}}
        {{-- 'overflow-y-auto' di sidebar memungkinkan scroll jika tautan terlalu banyak --}}
        <aside class="hidden lg:block w-64 gradient-sidebar text-white p-6 shadow-2xl flex-shrink-0 overflow-y-auto">
            <div class="mb-8">
                <h1 class="text-2xl font-black text-shadow-light">iLibrary</h1>
                <p class="text-xs opacity-80 mt-1">Dashboard Pengguna</p>
            </div>

            <div class="flex flex-col items-center mb-6 border-b border-white/20 pb-4">
                <img src="https://placehold.co/80x80/FFFFFF/000?text=USER" alt="Avatar" class="w-20 h-20 rounded-full border-4 border-white shadow-lg">
                <p class="mt-3 font-semibold text-lg">{{ auth()->user()->name }}</p>
                <p class="text-xs opacity-75">{{ now()->locale('id')->isoFormat('dddd, D MMM Y') }}</p>
            </div>

            <nav class="space-y-2">
                {{-- Tautan Dashboard (Aktif) --}}
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg bg-white/20 font-semibold transition hover:bg-white/30">
                    <span class="text-xl">🏠</span>
                    <span>Dashboard</span>
                </a>
                {{-- Tautan Jelajahi Buku --}}
                <a href="{{ route('books.index') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                    <span class="text-xl">📚</span>
                    <span>Jelajahi Buku</span>
                </a>
                {{-- Tautan Riwayat Pinjaman --}}
                <a href="{{ route('borrowings.my') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                    <span class="text-xl">📜</span>
                    <span>Riwayat Pinjaman</span>
                </a>
                {{-- Tautan Keluar --}}
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-red-500/50 hover:opacity-100">
                    <span class="text-xl">🚪</span>
                    <span>Keluar</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            </nav>
        </aside>

        {{-- MAIN CONTENT AREA --}}
        {{-- Menambahkan 'overflow-y-auto' di sini untuk scroll konten utama saja --}}
        <main class="flex-1 p-4 sm:p-8 overflow-y-auto">
            <div class="bg-white rounded-xl shadow-2xl overflow-hidden w-full"> 
                
                {{-- MOBILE HEADER (Tampil saat desktop sidebar hilang) --}}
                <div class="lg:hidden gradient-header p-4 flex justify-between items-center shadow-lg text-white">
                    <h2 class="text-xl font-bold">iLibrary</h2>
                    <p class="text-sm">{{ now()->locale('id')->isoFormat('D MMM Y') }}</p>
                </div>

                {{-- DASHBOARD TITLE --}}
                <div class="px-8 pt-6 pb-4">
                    <h2 class="text-xl font-bold text-gray-700">Statistik Utama</h2>
                </div>

                {{-- STATS GRID (4 Kolom Penting Pengguna) --}}
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 px-8 pt-0">
                    
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

                {{-- MAIN CONTENT: RIWAYAT & BUKU FAVORIT --}}
                <div class="p-8 pt-4 grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    {{-- Kiri: Riwayat Peminjaman (Menggantikan Chart) --}}
                    <div class="lg:col-span-2 space-y-6">
                        
                        {{-- BUKU YANG SEDANG ANDA PINJAM (TABEL AKTIF) --}}
                        <div class="bg-white p-6 rounded-xl shadow-lg border">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Buku yang Sedang Anda Pinjam</h3>
                            
                            @if($activeBorrowings->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-slate-200">
                                        <thead class="bg-slate-50">
                                            <tr>
                                                <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Buku</th>
                                                <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Dipinjam</th>
                                                <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jatuh Tempo</th>
                                                <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                                <th class="px-3 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-slate-200">
                                            @foreach($activeBorrowings as $borrowing)
                                                @php
                                                    $isOverdue = $borrowing->isOverdue();
                                                @endphp
                                                <tr class="hover:bg-slate-50">
                                                    <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-slate-900">{{ $borrowing->buku->title }}</td>
                                                    <td class="px-3 py-3 whitespace-nowrap text-sm text-slate-500">{{ $borrowing->tanggal_pinjam->format('d M Y') }}</td>
                                                    <td class="px-3 py-3 whitespace-nowrap text-sm {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-slate-500' }}">
                                                        {{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}
                                                    </td>
                                                    <td class="px-3 py-3 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $isOverdue ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                            {{ $isOverdue ? 'Terlambat' : 'Dipinjam' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-3 py-3 whitespace-nowrap">
                                                        <form method="POST" action="{{ route('borrowings.return', $borrowing) }}" onsubmit="return confirm('Yakin ingin mengembalikan buku ini?')" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="text-xs px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition">Kembalikan</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-4">
                                    <a href="{{ route('borrowings.my') }}" class="inline-flex items-center px-4 py-2 border border-indigo-600 text-indigo-600 font-medium rounded-lg hover:bg-indigo-50 transition">
                                        Lihat Semua Pinjaman
                                    </a>
                                </div>
                            @else
                                <div class="text-center p-8 bg-slate-50 rounded-lg">
                                    <p class="text-slate-500 mb-4">Anda tidak sedang meminjam buku apapun.</p>
                                    <a href="{{ route('books.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:bg-indigo-700 transition">
                                        Jelajahi Buku
                                    </a>
                                </div>
                            @endif
                        </div>

                        {{-- RIWAYAT PEMINJAMAN TERAKHIR ANDA (TABEL AKTIVITAS TERBARU) --}}
                        <div class="bg-white p-6 rounded-xl shadow-lg border">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Aktivitas Terbaru</h3>
                            
                            @if($borrowingHistory->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-left">
                                        <thead class="text-xs text-slate-700 uppercase bg-slate-200">
                                            <tr>
                                                <th class="py-2 px-3">Buku</th>
                                                <th class="py-2 px-3">Anggota</th> {{-- Di dashboard pengguna, ini adalah nama pengguna itu sendiri --}}
                                                <th class="py-2 px-3">Dipinjam</th>
                                                <th class="py-2 px-3">Jatuh Tempo</th>
                                                <th class="py-2 px-3">Status</th>
                                                <th class="py-2 px-3">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($borrowingHistory->sortByDesc('tanggal_pinjam')->take(5) as $history)
                                                @php
                                                    $buku = $history->buku ?? (object)['title' => '(Buku Hilang)'];
                                                    // Status berdasarkan tanggal kembali aktual atau keterlambatan jika masih dipinjam
                                                    $isReturned = $history->status === 'dikembalikan';
                                                    $isOverdue = !$isReturned && $history->isOverdue();
                                                @endphp
                                                <tr class="border-t border-slate-200 hover:bg-slate-100 transition">
                                                    <td class="py-3 px-3 font-medium text-sm text-slate-800">{{ $buku->title }}</td>
                                                    <td class="py-3 px-3 text-sm text-slate-600">{{ auth()->user()->name }}</td> {{-- Nama anggota selalu pengguna --}}
                                                    <td class="py-3 px-3 text-sm">{{ $history->tanggal_pinjam->format('d/m/Y') }}</td>
                                                    <td class="py-3 px-3 text-sm {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-slate-600' }}">
                                                        {{ $history->tanggal_kembali_rencana->format('d/m/Y') }}
                                                    </td>
                                                    <td class="py-3 px-3">
                                                        @if($isReturned)
                                                            <span class="text-sm font-semibold text-green-600">Dikembalikan</span>
                                                        @elseif($isOverdue)
                                                            <span class="text-sm font-semibold text-red-600">Terlambat</span>
                                                        @else
                                                            <span class="text-sm font-semibold text-blue-600">Dipinjam</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-3 px-3">
                                                        @if(!$isReturned)
                                                            <form method="POST" action="{{ route('borrowings.return', $history) }}" style="display:inline;">
                                                                @csrf
                                                                <button type="submit" class="px-3 py-1 text-sm bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">Kembalikan</button>
                                                            </form>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-4">
                                    <a href="{{ route('borrowings.my') }}" class="inline-flex items-center px-4 py-2 border border-indigo-600 text-indigo-600 font-medium rounded-lg hover:bg-indigo-50 transition">
                                        Lihat Semua Riwayat
                                    </a>
                                </div>
                            @else
                                <div class="text-center p-8 bg-slate-50 rounded-lg">
                                    <p class="text-slate-500 mb-4">Belum ada riwayat peminjaman.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Riwayat Peminjaman Sederhana (Simulasi Bar Chart) --}}
                        <div class="bg-white p-6 rounded-xl shadow-lg border">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Statistik Peminjaman Bulanan</h3>
                            <div class="bar-chart-container">
                                @php
                                    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                                    // Hitung pinjaman per bulan (simulasi jika data kosong)
                                    $simulated_history = [
                                        $borrowingHistory->filter(fn($b) => $b->tanggal_pinjam->format('M') == 'Jan')->count(),
                                        $borrowingHistory->filter(fn($b) => $b->tanggal_pinjam->format('M') == 'Feb')->count(),
                                        $borrowingHistory->filter(fn($b) => $b->tanggal_pinjam->format('M') == 'Mar')->count(),
                                        $borrowingHistory->filter(fn($b) => $b->tanggal_pinjam->format('M') == 'Apr')->count(),
                                        $borrowingHistory->filter(fn($b) => $b->tanggal_pinjam->format('M') == 'May')->count(),
                                        $borrowingHistory->filter(fn($b) => $b->tanggal_pinjam->format('M') == 'Jun')->count(),
                                        $borrowingHistory->filter(fn($b) => $b->tanggal_pinjam->format('M') == 'Jul')->count(),
                                        $borrowingHistory->filter(fn($b) => $b->tanggal_pinjam->format('M') == 'Aug')->count(),
                                        $borrowingHistory->filter(fn($b) => $b->tanggal_pinjam->format('M') == 'Sep')->count(),
                                        $borrowingHistory->filter(fn($b) => $b->tanggal_pinjam->format('M') == 'Oct')->count(),
                                        $borrowingHistory->filter(fn($b) => $b->tanggal_pinjam->format('M') == 'Nov')->count(),
                                        $borrowingHistory->filter(fn($b) => $b->tanggal_pinjam->format('M') == 'Dec')->count(),
                                    ];
                                    
                                    // Gunakan data simulasi jika data historis kosong, agar grafik tetap terlihat
                                    if (array_sum($simulated_history) == 0) {
                                        $simulated_history = [2, 5, 1, 3, 4, 2, 6, 3, 5, 1, 0, 0];
                                    }

                                    $max_loan = max($simulated_history) > 0 ? max($simulated_history) : 1;
                                @endphp
                                <div class="bar-chart-container">
                                    @foreach($simulated_history as $index => $count)
                                        <div class="flex flex-col items-center justify-end h-full">
                                            <div class="w-6 sm:w-8 rounded-t-lg bg-indigo-500 hover:bg-indigo-600 transition duration-300" 
                                                style="height: {{ ($count / $max_loan) * 80 + 15 }}%;" 
                                                title="{{ $months[$index] }}: {{ $count }} kali">
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">{{ $months[$index] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-center text-xs text-gray-400 mt-4">Total {{ $borrowingHistory->count() }} pinjaman sepanjang waktu.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Kanan: Jadwal Pengembalian & Buku Tersedia --}}
                    <div class="lg:col-span-1 space-y-6">
                        
                        {{-- JADWAL PENGEMBALIAN --}}
                        <div class="bg-white p-6 rounded-xl shadow-lg border sticky top-8">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Jadwal Pengembalian</h3>
                            
                            {{-- Kalender Hari Ini --}}
                            @php
                                $today = now()->locale('id');
                                $overdueCount = $activeBorrowings->filter(fn($b) => $b->isOverdue())->count();
                            @endphp
                            <div class="bg-gradient-to-tr from-indigo-500 to-blue-500 text-white rounded-lg p-3 mb-4 flex items-center justify-between shadow-lg">
                                <div>
                                    <div class="text-xs font-semibold">Hari ini</div>
                                    <div class="text-lg font-bold">{{ $today->isoFormat('dddd') }}</div>
                                </div>
                                <div class="text-center bg-white/20 p-2 rounded-md">
                                    <div class="text-xl font-bold leading-none">{{ $today->format('d') }}</div>
                                    <div class="text-xs">{{ $today->isoFormat('MMM') }}</div>
                                </div>
                            </div>
                            
                            {{-- Peminjaman Dekat Jatuh Tempo / Terlambat --}}
                            <div class="mb-4 pt-2 border-t">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-sm font-bold text-slate-600">Peminjaman Aktif</div>
                                    <div class="text-xs font-semibold {{ $overdueCount > 0 ? 'text-red-600' : 'text-slate-400' }}">
                                        {{ $overdueCount }} Terlambat
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    @forelse($activeBorrowings->take(4) as $borrowing)
                                        @php
                                            $buku = $borrowing->buku ?? (object)['title' => '(Buku Hilang)', 'author' => ''];
                                            $isOverdue = $borrowing->isOverdue();
                                        @endphp
                                        <div 
                                            class="p-3 rounded-lg border transition-all {{ $isOverdue ? 'bg-red-50 border-red-200' : 'bg-slate-50 border-slate-200' }}"
                                        >
                                            <div class="text-sm font-medium">{{ $buku->title }}</div>
                                            <div class="text-xs text-slate-500">Oleh: {{ auth()->user()->name }}</div>
                                            <div class="text-xs font-semibold mt-1 {{ $isOverdue ? 'text-red-600' : 'text-slate-600' }}">
                                                Jatuh Tempo: {{ $borrowing->tanggal_kembali_rencana->format('d M Y') }} 
                                                @if($isOverdue)
                                                    <span class="font-normal">({{ $borrowing->getDaysOverdue() }} hari terlambat)</span>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center text-sm text-slate-400 p-4 border rounded-lg bg-slate-50">Tidak ada pinjaman aktif.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- Buku Tersedia (Favorit Anda) --}}
                        <div class="bg-white p-6 rounded-xl shadow-lg border">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Buku Tersedia (Favorit Anda)</h3>
                            <div class="space-y-4">
                                @forelse($availableBooks->take(4) as $book)
                                    <div class="flex space-x-3 items-start p-2 hover:bg-gray-50 rounded transition border border-gray-100">
                                        {{-- Simulasi Cover Buku --}}
                                        <div class="w-16 h-20 bg-gradient-to-br from-cyan-300 to-blue-400 text-white rounded-md shadow flex items-center justify-center text-xl shrink-0">
                                            📘
                                        </div>
                                        <div class="flex-grow">
                                            <h4 class="font-bold text-sm text-gray-800 leading-tight">{{ $book->title }}</h4>
                                            <p class="text-xs text-gray-500">Penulis: {{ $book->author }}</p>
                                            <p class="text-xs text-gray-500">
                                                <span class="font-semibold text-blue-600">{{ $book->available_copies }}</span> tersedia
                                            </p>
                                            <form method="POST" action="{{ route('books.borrow', $book) }}" class="mt-1">
                                                @csrf
                                                <button type="submit" class="text-xs px-2 py-0.5 bg-indigo-500 text-white rounded hover:bg-indigo-600 transition"
                                                    {{ $book->isAvailable() ? '' : 'disabled' }}>
                                                    Pinjam
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-sm text-gray-500 p-4 border rounded">Tidak ada buku tersedia saat ini.</div>
                                @endforelse
                                <a href="{{ route('books.index') }}" class="block text-center text-sm text-indigo-600 font-medium hover:text-indigo-800 pt-2 border-t mt-4">Jelajahi Semua Buku</a>
                            </div>
                        </div>
                    </div>
                </div> {{-- End of Main Content --}}

            </div>
        </main>
    </div>
@endsection
