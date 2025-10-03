<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Peminjaman - iLibrary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'primary': '#10b981', // green-500
                        'warning': '#f59e0b', // amber-500
                        'danger': '#ef4444', // red-500
                        'info': '#3b82f6', // blue-500
                        'secondary': '#6b7280', // gray-500
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom styles to match the structure from the Blade file */
        body {
            background-color: #f3f4f6; /* bg-gray-100 */
            font-family: 'Inter', sans-serif;
            padding: 2rem;
        }

        .card {
            background-color: #ffffff;
            border-radius: 0.5rem; /* rounded-lg */
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem; /* rounded-md */
            font-weight: 500;
            transition: background-color 0.15s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 1.25rem;
            gap: 0.5rem;
        }

        .btn-success { background-color: #10b981; color: white; }
        .btn-success:hover { background-color: #059669; }

        .btn-warning { background-color: #f59e0b; color: white; }
        .btn-danger { background-color: #ef4444; color: white; }
        .btn-info { background-color: #3b82f6; color: white; }

        .btn:not(.btn-success, .btn-warning, .btn-danger, .btn-info) {
            background-color: #4b5563; /* gray-600 */
            color: white;
        }
        .btn:not(.btn-success, .btn-warning, .btn-danger, .btn-info):hover { background-color: #374151; }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            line-height: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.25rem;
            font-weight: 500;
            color: #4b5563; /* text-gray-600 */
            font-size: 0.875rem;
        }

        .form-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db; /* border-gray-300 */
            border-radius: 0.375rem;
            background-color: #ffffff;
            appearance: none;
        }
        
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background-color: #f9fafb; /* bg-gray-50 */
            border-bottom: 2px solid #e5e7eb;
        }

        th {
            text-align: left;
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.75rem;
            color: #4b5563; /* text-gray-600 */
            text-transform: uppercase;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6; /* border-gray-100 */
            font-size: 0.875rem;
            color: #374151; /* text-gray-700 */
            vertical-align: middle;
        }
        
        tbody tr:hover {
            background-color: #f9fafb;
        }

        /* Responsive Grid for Filters */
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1.5rem;
        }

        @media (min-width: 640px) { /* sm */
            .grid-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        /* Sidebar Styles (dari dashboard) */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap');
        .font-sans { font-family: 'Inter', sans-serif; }
        .gradient-sidebar {
            background: linear-gradient(180deg, #0093E9 0%, #37a0ff 100%);
        }
        .text-shadow-light {
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        body, html {
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
            overflow-x: hidden;
        }
        .layout-container {
            min-height: 100vh;
            display: flex;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        .main-content {
            flex: 1;
            min-height: 100vh;
            overflow-y: auto;
            padding: 1rem; /* Sesuaikan padding body asli */
        }
        .sidebar {
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
        }
    </style>
</head>
<body class="font-sans layout-container bg-gray-100">

    {{-- SIDEBAR KIRI (dari dashboard.blade.php - iLibrary, dengan emoji icons) --}}
    <aside class="hidden lg:block sidebar w-64 gradient-sidebar text-white p-6 shadow-2xl flex-shrink-0 overflow-y-auto">
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
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                <span class="text-xl">🏠</span><span>Dashboard</span>
            </a>
            <a href="{{ route('books.index') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                <span class="text-xl">📚</span><span>Jelajahi Buku</span>
            </a>
            <a href="{{ route('borrowings.my') }}" class="flex items-center space-x-3 p-3 rounded-lg bg-white/20 font-semibold transition hover:bg-white/30">
                <span class="text-xl">📜</span><span>Riwayat Pinjaman</span>
            </a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-red-500/50 hover:opacity-100">
                <span class="text-xl">🚪</span><span>Keluar</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </nav>
    </aside>

    {{-- MAIN CONTENT AREA --}}
    <main class="main-content">
        {{-- MOBILE HEADER (tanpa navbar) --}}
        <div class="lg:hidden gradient-sidebar p-4 flex justify-between items-center shadow-lg text-white mb-6">
            <h2 class="text-xl font-bold">iLibrary</h2>
            <p class="text-sm">{{ now()->locale('id')->isoFormat('D MMM Y') }}</p>
        </div>

        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Semua Peminjaman</h1>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('borrowings.create') }}" class="btn btn-success text-sm md:text-base">
                        <i class="fas fa-plus"></i> Buat Peminjaman Baru
                    </a>
                @endif
            </div>

            <div class="card">
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Filter Peminjaman</h2>
                <form method="GET" action="{{ route('borrowings.index') }}" class="grid-3">
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                            <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat Dikembalikan</option>
                        </select>
                    </div>
                    
                    @if(auth()->user()->role === 'admin')
                    <div class="form-group">
                        <label for="overdue">Hanya Terlambat</label>
                        <select id="overdue" name="overdue">
                            <option value="">Semua Peminjaman</option>
                            <option value="1" {{ request('overdue') == '1' ? 'selected' : '' }}>Hanya Terlambat</option>
                        </select>
                    </div>
                    @endif
                    
                    <div class="form-group flex flex-col justify-end">
                        <label class="hidden sm:block opacity-0">&nbsp;</label>
                        <div class="flex space-x-3">
                            <button type="submit" class="btn bg-blue-500 hover:bg-blue-600 text-white flex-grow">Filter</button>
                            @if(request()->anyFilled(['status', 'overdue']))
                                <a href="{{ route('borrowings.index') }}" class="btn bg-secondary hover:bg-gray-700 text-white flex-grow">Reset</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- Borrowings Table --}}
            @if($borrowings->count() > 0)
                <div class="card overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Book</th>
                                <th>Type</th>
                                <th>Borrowed</th>
                                <th>Due Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
                                <th>Fine</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowings as $borrowing)
                                <tr>
                                    <td>{{ $borrowing->user->name }}</td>
                                    <td>
                                        <strong class="text-gray-900">{{ $borrowing->book->title }}</strong><br>
                                        <small class="text-gray-500">by {{ $borrowing->book->author }}</small>
                                    </td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $borrowing->book->type)) }}</td>
                                    <td>{{ $borrowing->borrowed_at->format('d M Y') }}</td>
                                    <td>
                                        {{ $borrowing->due_date->format('d M Y') }}
                                        @if($borrowing->isOverdue())
                                            <br><small style="color: #ef4444;" class="font-semibold">{{ $borrowing->overdueDays() }} days overdue</small>
                                        @endif
                                    </td>
                                    <td>{{ $borrowing->returned_at ? $borrowing->returned_at->format('d M Y') : '-' }}</td>
                                    <td>
                                        @if($borrowing->isOverdue())
                                            <span class="btn btn-sm btn-danger">Overdue</span>
                                        @elseif($borrowing->returned_at)
                                            <span class="btn btn-sm btn-info">Dikembalikan</span>
                                        @else
                                            <span class="btn btn-sm btn-warning">Dipinjam</span>
                                        @endif
                                    </td>
                                    <td>{{ $borrowing->fine ? 'Rp ' . number_format($borrowing->fine, 0, ',', '.') : '-' }}</td>
                                    <td class="whitespace-nowrap">
                                        <a href="{{ route('borrowings.show', $borrowing) }}" class="btn btn-sm bg-gray-300 text-gray-800 hover:bg-gray-400">View</a>
                                        @if(!$borrowing->returned_at && auth()->user()->role === 'admin')
                                            <form method="POST" action="{{ route('borrowings.return', $borrowing) }}" style="display: inline;" class="ml-1">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Kembalikan buku ini?')">Return</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    {{-- Pagination --}}
                    @if($borrowings->hasPages())
                        <div class="text-center mt-6">
                            <div class="flex justify-center items-center gap-2">
                                @if(!$borrowings->onFirstPage())
                                    <a href="{{ $borrowings->previousPageUrl() }}" class="btn bg-blue-500 hover:bg-blue-600 text-white">Previous</a>
                                @else
                                    <span class="btn bg-gray-300 text-gray-600 cursor-not-allowed">Previous</span>
                                @endif
                                
                                <span class="text-sm text-gray-600">Page {{ $borrowings->currentPage() }} of {{ $borrowings->lastPage() }}</span>
                                
                                @if($borrowings->hasMorePages())
                                    <a href="{{ $borrowings->nextPageUrl() }}" class="btn bg-blue-500 hover:bg-blue-600 text-white">Next</a>
                                @else
                                    <span class="btn bg-gray-300 text-gray-600 cursor-not-allowed">Next</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @else
                {{-- Empty State --}}
                <div class="card text-center py-10">
                    <i class="fas fa-file-alt text-5xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Tidak ada peminjaman ditemukan</h3>
                    <p class="text-gray-500 mb-4">Coba sesuaikan filter Anda atau buat peminjaman baru.</p>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('borrowings.create') }}" class="btn btn-success">Buat Peminjaman Baru</a>
                    @endif
                </div>
            @endif
        </div>
    </main>
</body>
</html>