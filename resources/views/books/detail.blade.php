<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $buku->title }} - Toshokan</title>
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
                    <h1 class="text-3xl font-black text-gray-800 mb-2">📚 Detail Buku</h1>
                    <p class="text-gray-600">Informasi lengkap tentang buku</p>
                </div>
                <a href="{{ route('books.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Kelola Buku
                </a>
            </div>

            {{-- Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Book Cover --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-center mb-6">
                            @if($buku->cover_image)
                                <img src="{{ asset('storage/' . $buku->cover_image) }}" 
                                     alt="{{ $buku->title }}" 
                                     class="w-full max-w-sm h-auto object-cover rounded-lg shadow-md">
                            @else
                                <div class="w-full max-w-sm h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <div class="text-center text-gray-500">
                                        <i class="fas fa-book text-4xl mb-2"></i>
                                        <p>Tidak ada cover</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Status & Availability --}}
                        <div class="text-center space-y-3">
                            <div>
                                <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold {{ $buku->available_copies > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $buku->available_copies > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <strong>{{ $buku->available_copies }}</strong> dari <strong>{{ $buku->total_copies }}</strong> copy tersedia
                            </div>
                        </div>

                        {{-- Admin Actions --}}
                        @if(auth()->user()->isAdmin())
                            <div class="mt-6 space-y-3">
                                <a href="{{ route('books.edit', $buku) }}" class="w-full inline-flex items-center justify-center px-4 py-3 bg-yellow-600 text-white font-semibold rounded-lg shadow hover:bg-yellow-700 transition">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit Buku
                                </a>
                                <form method="POST" action="{{ route('books.destroy', $buku) }}" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-600 text-white font-semibold rounded-lg shadow hover:bg-red-700 transition" onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                        <i class="fas fa-trash mr-2"></i>
                                        Hapus Buku
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Book Information --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ $buku->title }}</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Basic Information --}}
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                    Informasi Dasar
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-semibold">Penulis:</span>
                                        <span class="text-gray-800">{{ $buku->author }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-semibold">Jenis:</span>
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                            {{ ucfirst(str_replace('_', ' ', $buku->type)) }}
                                        </span>
                                    </div>
                                    
                                    @if($buku->publisher)
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <span class="text-gray-600 font-semibold">Penerbit:</span>
                                            <span class="text-gray-800">{{ $buku->publisher }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($buku->publication_date)
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <span class="text-gray-600 font-semibold">Tanggal Terbit:</span>
                                            <span class="text-gray-800">{{ $buku->publication_date->format('d M Y') }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($buku->isbn)
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <span class="text-gray-600 font-semibold">ISBN:</span>
                                            <span class="text-gray-800 font-mono">{{ $buku->isbn }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($buku->price)
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <span class="text-gray-600 font-semibold">Harga:</span>
                                            <span class="text-gray-800">Rp {{ number_format($buku->price, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Stock Information --}}
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                    <i class="fas fa-warehouse text-green-500 mr-2"></i>
                                    Informasi Stok
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-semibold">Total Copy:</span>
                                        <span class="text-gray-800">{{ $buku->total_copies }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-semibold">Copy Tersedia:</span>
                                        <span class="text-gray-800">{{ $buku->available_copies }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-semibold">Copy Dipinjam:</span>
                                        <span class="text-gray-800">{{ $buku->total_copies - $buku->available_copies }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-semibold">Ditambahkan:</span>
                                        <span class="text-gray-800">{{ $buku->created_at->format('d M Y') }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-gray-600 font-semibold">Terakhir Update:</span>
                                        <span class="text-gray-800">{{ $buku->updated_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        @if($buku->description)
                            <div class="mt-8">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                    <i class="fas fa-align-left text-purple-500 mr-2"></i>
                                    Deskripsi
                                </h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-gray-700 leading-relaxed">{{ $buku->description }}</p>
                                </div>
                            </div>
                        @endif

                        {{-- Borrowing History --}}
                        @if($buku->peminjaman->count() > 0)
                            <div class="mt-8">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                    <i class="fas fa-history text-orange-500 mr-2"></i>
                                    Riwayat Peminjaman
                                </h3>
                                <div class="bg-gray-50 rounded-lg overflow-hidden">
                                    <div class="overflow-x-auto">
                                        <table class="w-full">
                                            <thead class="bg-gray-200">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Peminjam</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tanggal Pinjam</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tanggal Kembali</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach($buku->peminjaman->take(10) as $peminjaman)
                                                    <tr class="hover:bg-gray-100">
                                                        <td class="px-4 py-3 text-sm text-gray-800">{{ $peminjaman->user->name }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-600">
                                                            {{ $peminjaman->tanggal_kembali_aktual ? $peminjaman->tanggal_kembali_aktual->format('d M Y') : '-' }}
                                                        </td>
                                                        <td class="px-4 py-3 text-sm">
                                                            @if($peminjaman->status == 'dipinjam' && $peminjaman->isOverdue())
                                                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">
                                                                    Terlambat
                                                                </span>
                                                            @elseif($peminjaman->status == 'dipinjam')
                                                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-semibold">
                                                                    Dipinjam
                                                                </span>
                                                            @else
                                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">
                                                                    Dikembalikan
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($buku->peminjaman->count() > 10)
                                        <div class="px-4 py-3 bg-gray-100 text-center">
                                            <span class="text-sm text-gray-600">Dan {{ $buku->peminjaman->count() - 10 }} peminjaman lainnya...</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>