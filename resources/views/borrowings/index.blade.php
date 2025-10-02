@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    
    body, html {
        margin: 0;
        padding: 0;
        height: 100vh;
        width: 100vw;
        overflow-x: hidden;
    }

    .font-sans { font-family: 'Inter', sans-serif; }
    
    .gradient-sidebar {
        background: linear-gradient(180deg, #0093E9 0%, #37a0ff 100%);
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
    }

    .sidebar {
        height: 100vh;
        position: sticky;
        top: 0;
        overflow-y: auto;
    }
</style>

<div class="font-sans layout-container bg-gray-50">
    {{-- SIDEBAR KIRI --}}
    <aside class="hidden lg:block sidebar w-64 gradient-sidebar text-white shadow-2xl">
        <div class="p-6">
            <div class="mb-8">
                <h1 class="text-2xl font-black">Perpustakaan</h1>
                <p class="text-xs opacity-80 mt-1">BDL Indonesia</p>
            </div>

            <div class="flex flex-col items-center mb-6 border-b border-white/20 pb-4">
                <img src="{{ asset('assets/img/logo_bdl.png') }}" alt="Avatar" class="w-20 h-20 rounded-full border-4 border-white shadow-lg">
                <p class="mt-3 font-semibold text-lg">{{ auth()->user()->name }}</p>
                <p class="text-xs opacity-75">{{ now()->locale('id')->isoFormat('dddd, D MMM Y') }}</p>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                    <i class="fas fa-home w-5"></i>
                    <span>Dashboard</span>
                </a>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('users.index') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                    <i class="fas fa-users w-5"></i>
                    <span>Users</span>
                </a>
                @endif
                <a href="{{ route('books.index') }}" class="flex items-center space-x-3 p-3 rounded-lg bg-white/20 font-semibold transition hover:bg-white/30">
                    <i class="fas fa-book w-5"></i>
                    <span>Books</span>
                </a>
                <a href="{{ route('borrowings.index') }}" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-white/10 hover:opacity-100">
                    <i class="fas fa-file-alt w-5"></i>
                    <span>Borrowings</span>
                </a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center space-x-3 p-3 rounded-lg opacity-80 transition hover:bg-red-500/50 hover:opacity-100">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </nav>
        </div>
    </aside>

    {{-- MAIN CONTENT AREA --}}
    <main class="main-content">
        <div class="min-h-full bg-white shadow-xl">
            {{-- MOBILE HEADER --}}
            <div class="lg:hidden gradient-sidebar p-4 flex justify-between items-center shadow-lg text-white">
                <h2 class="text-xl font-bold">iLibrary</h2>
                <p class="text-sm">{{ now()->locale('id')->isoFormat('D MMM Y') }}</p>
            </div>

            {{-- CONTENT WRAPPER --}}
            <div class="p-4 lg:p-6">
                {{-- Search & Filter --}}
                <div class="mb-6">
                    <div class="flex flex-wrap gap-4 items-center justify-between">
                        <h1 class="text-2xl font-bold text-gray-800">Katalog Buku</h1>
                        <div class="flex gap-4">
                            <div class="relative">
                                <input type="text" placeholder="Cari buku..." class="pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                <span class="absolute left-3 top-2.5">🔍</span>
                            </div>
                            <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="">Semua Kategori</option>
                                <option value="fiksi">Fiksi</option>
                                <option value="non_fiksi">Non Fiksi</option>
                                <!-- Add more categories -->
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Books Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse($books as $book)
                    <div class="bg-white rounded-xl shadow-lg border hover:shadow-xl transition-shadow">
                        <div class="h-48 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-t-xl flex items-center justify-center text-white text-5xl">
                            📚
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2">{{ $book->title }}</h3>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><span class="font-medium">Penulis:</span> {{ $book->author }}</p>
                                <p><span class="font-medium">Kategori:</span> {{ ucfirst(str_replace('_', ' ', $book->type)) }}</p>
                                <p><span class="font-medium">Status:</span> 
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $book->isAvailable() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $book->isAvailable() ? 'Tersedia' : 'Dipinjam' }}
                                    </span>
                                </p>
                            </div>
                            <div class="mt-4 flex justify-between items-center">
                                @if($book->isAvailable())
                                    <form method="POST" action="{{ route('books.borrow', $book) }}">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                            Pinjam Buku
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed">
                                        Tidak Tersedia
                                    </button>
                                @endif
                                <a href="{{ route('books.show', $book) }}" class="text-blue-600 hover:text-blue-800">
                                    Detail →
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-12">
                        <div class="text-5xl mb-4">📚</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Buku</h3>
                        <p class="text-gray-500">Belum ada buku yang tersedia saat ini.</p>
                    </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($books->hasPages())
                <div class="mt-6">
                    {{ $books->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection