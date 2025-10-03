{{-- resources/views/borrowings/my.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Saya - iLibrary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
        .font-sans { font-family: 'Inter', sans-serif; }
        .gradient-sidebar {
            background: linear-gradient(180deg, #0093E9 0%, #37a0ff 100%);
        }
    </style>
</head>
<body class="font-sans min-h-screen bg-gray-50 flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside class="hidden lg:block w-64 gradient-sidebar text-white p-6 shadow-2xl flex-shrink-0 overflow-y-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-black">iLibrary</h1>
            <p class="text-xs opacity-80 mt-1">Dashboard Pengguna</p>
        </div>

        <div class="flex flex-col items-center mb-6 border-b border-white/20 pb-4">
            <img src="https://placehold.co/80x80/FFFFFF/000?text=USER" 
                 alt="Avatar" 
                 class="w-20 h-20 rounded-full border-4 border-white shadow-lg">
            <p class="mt-3 font-semibold text-lg">{{ auth()->user()->name }}</p>
            <p class="text-xs opacity-75">{{ now()->locale('id')->isoFormat('dddd, D MMM Y') }}</p>
        </div>

        <nav class="space-y-2">
    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100 {{ request()->routeIs('dashboard') ? 'bg-white/30 font-semibold opacity-100' : '' }}">
        <span class="text-xl">🏠</span><span>Dashboard</span>
    </a>
    <a href="{{ route('books.index') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100 {{ request()->routeIs('books.index') ? 'bg-white/30 font-semibold opacity-100' : '' }}">
        <span class="text-xl">📚</span><span>Jelajahi Buku</span>
    </a>
    <a href="{{ route('borrowings.my') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100 {{ request()->routeIs('borrowings.my') ? 'bg-white/30 font-semibold opacity-100' : '' }}">
        <span class="text-xl">📜</span><span>Peminjaman Saya</span>
    </a>
    <a href="{{ route('logout') }}" 
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
       class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-red-500/50 hover:opacity-100">
        <span class="text-xl">🚪</span><span>Keluar</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
</nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 p-6 overflow-y-auto">
        <h1 class="text-2xl font-bold mb-6">Peminjaman Saya</h1>

        {{-- SEDANG DIPINJAM --}}
        @if($activeBorrowings->count() > 0)
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Sedang Dipinjam ({{ $activeBorrowings->count() }})</h3>
            
            <div class="grid md:grid-cols-2 gap-4">
                @foreach($activeBorrowings as $borrowing)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-600">{{ $borrowing->buku->title }}</h4>
                    <p><strong>Penulis:</strong> {{ $borrowing->buku->author }}</p>
                    <p><strong>Jenis:</strong> {{ ucfirst(str_replace('_', ' ', $borrowing->buku->type)) }}</p>
                    <p><strong>Dipinjam:</strong> {{ $borrowing->tanggal_pinjam->format('d M Y') }}</p>
                    <p><strong>Jatuh Tempo:</strong> {{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}</p>
                    
                    @if($borrowing->isOverdue())
                        <div class="bg-red-100 text-red-700 p-3 rounded mt-3">
                            <strong>TERLAMBAT!</strong> {{ $borrowing->getDaysOverdue() }} hari<br>
                            <strong>Denda:</strong> Rp {{ number_format($borrowing->calculateFine(), 0, ',', '.') }}
                        </div>
                    @endif
                    
                    <div class="mt-3 flex space-x-2">
                        <a href="{{ route('borrowings.show', $borrowing) }}" class="px-3 py-1 text-sm bg-blue-500 text-white rounded">Lihat Detail</a>
                        <form method="POST" action="{{ route('borrowings.return', $borrowing) }}">
                            @csrf
                            <button type="submit" class="px-3 py-1 text-sm bg-green-500 text-white rounded">Return Book</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-white text-center rounded-xl shadow-md p-6 mb-6">
            <h3 class="font-semibold">Tidak Ada Pinjaman Aktif</h3>
            <p class="text-gray-500 mb-3">Anda belum meminjam buku apapun.</p>
            <a href="{{ route('books.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded">Jelajahi Buku</a>
        </div>
        @endif

        {{-- RIWAYAT PEMINJAMAN --}}
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">Riwayat Peminjaman</h3>
            
            @if($borrowingHistory->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Buku</th>
                                <th class="px-4 py-2 text-left">Penulis</th>
                                <th class="px-4 py-2 text-left">Dipinjam</th>
                                <th class="px-4 py-2 text-left">Jatuh Tempo / Dikembalikan</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowingHistory as $borrowing)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $borrowing->buku->title }}</td>
                                <td class="px-4 py-2">{{ $borrowing->buku->author }}</td>
                                <td class="px-4 py-2">{{ $borrowing->tanggal_pinjam->format('d M Y') }}</td>
                                <td class="px-4 py-2">
                                    @if($borrowing->tanggal_kembali_aktual)
                                        {{ $borrowing->tanggal_kembali_aktual->format('d M Y') }}
                                    @else
                                        {{ $borrowing->tanggal_kembali_rencana->format('d M Y') }}
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    @if($borrowing->status == 'dipinjam' && $borrowing->isOverdue())
                                        <span class="px-2 py-1 text-xs bg-red-500 text-white rounded">Overdue</span>
                                    @elseif($borrowing->status == 'dipinjam')
                                        <span class="px-2 py-1 text-xs bg-yellow-500 text-white rounded">Dipinjam</span>
                                    @elseif($borrowing->status == 'dikembalikan')
                                        <span class="px-2 py-1 text-xs bg-green-500 text-white rounded">Dikembalikan</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-gray-500 text-white rounded">{{ ucfirst($borrowing->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    @if($borrowing->denda > 0)
                                        Rp {{ number_format($borrowing->denda, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex justify-center items-center gap-3 mt-4">
                    @if($borrowingHistory->onFirstPage())
                        <span class="px-3 py-1 bg-gray-300 rounded">Previous</span>
                    @else
                        <a href="{{ $borrowingHistory->previousPageUrl() }}" class="px-3 py-1 bg-blue-500 text-white rounded">Previous</a>
                    @endif
                    
                    <span>Halaman {{ $borrowingHistory->currentPage() }} dari {{ $borrowingHistory->lastPage() }}</span>
                    
                    @if($borrowingHistory->hasMorePages())
                        <a href="{{ $borrowingHistory->nextPageUrl() }}" class="px-3 py-1 bg-blue-500 text-white rounded">Next</a>
                    @else
                        <span class="px-3 py-1 bg-gray-300 rounded">Next</span>
                    @endif
                </div>
            @else
                <p class="text-gray-500">Belum ada riwayat peminjaman.</p>
            @endif
        </div>
    </main>
</body>
</html>
