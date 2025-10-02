@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div class="container">
    <div class="header-section">
        <h1>Koleksi Buku Perpustakaan</h1>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('books.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah Buku Baru
            </a>
        @endif
    </div>

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>Koleksi Buku Perpustakaan</h1>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('books.create') }}" class="btn btn-success">Tambah Buku Baru</a>
    @endif
</div>

<!-- Search and Filters -->
<div class="card">
    <form method="GET" action="{{ route('books.index') }}" style="display: flex; gap: 10px; align-items: end;">
        <div class="form-group" style="flex: 1; margin-bottom: 0;">
            <label for="search">Cari Buku</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}" 
                   placeholder="Cari berdasarkan judul, penulis, atau ISBN...">
        </div>
        
        <div class="form-group" style="margin-bottom: 0;">
            <label for="type">Jenis</label>
            <select id="type" name="type">
                <option value="">Semua Jenis</option>
                <option value="light_novel" {{ request('type') == 'light_novel' ? 'selected' : '' }}>Light Novel</option>
                <option value="manga" {{ request('type') == 'manga' ? 'selected' : '' }}>Manga</option>
            </select>
        </div>
        
        <div class="form-group" style="margin-bottom: 0;">
            <label for="available">Ketersediaan</label>
            <select id="available" name="available">
                <option value="">Semua</option>
                <option value="1" {{ request('available') == '1' ? 'selected' : '' }}>Hanya Tersedia</option>
            </select>
        </div>
        
        <button type="submit" class="btn">Cari</button>
        @if(request()->anyFilled(['search', 'type', 'available']))
            <a href="{{ route('books.index') }}" class="btn" style="background: #6c757d;">Reset</a>
        @endif
    </form>
</div>

<!-- Books Grid -->
@if($books->count() > 0)
    <div class="books-grid">
        @foreach($books as $book)
        <div class="book-card">
            <div class="book-cover">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" 
                         alt="{{ $book->title }}" 
                         class="cover-image">
                @else
                    <div class="no-cover">
                        <i class="fas fa-book fa-3x"></i>
                        <span>Tidak Ada Gambar</span>
                    </div>
                @endif
            </div>
            
            <div class="book-info">
                <h4 class="book-title">{{ $book->title }}</h4>
                <div class="book-details">
                    <p>
                        <i class="fas fa-user"></i>
                        <strong>Penulis:</strong> {{ $book->author }}
                    </p>
                    <p>
                        <i class="fas fa-bookmark"></i>
                        <strong>Jenis:</strong> {{ ucfirst(str_replace('_', ' ', $book->type)) }}
                    </p>
                    <p>
                        <i class="fas fa-books"></i>
                        <strong>Tersedia:</strong> 
                        <span class="availability {{ $book->available_copies > 0 ? 'available' : 'unavailable' }}">
                            {{ $book->available_copies }}/{{ $book->total_copies }}
                        </span>
                    </p>
                    
                    @if($book->price)
                        <p>
                            <i class="fas fa-tag"></i>
                            <strong>Harga:</strong> Rp {{ number_format($book->price, 0, ',', '.') }}
                        </p>
                    @endif
                </div>
                
                <div class="book-actions">
                    <a href="{{ route('books.show', $book) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-info-circle"></i> Lihat Detail
                    </a>
                    
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('books.edit', $book) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('books.destroy', $book) }}" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    @else
                        @if($book->isAvailable())
                            <form method="POST" action="{{ route('books.borrow', $book) }}" class="inline-form">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-hand-holding"></i> Pinjam
                                </button>
                            </form>
                        @else
                            <span class="btn btn-secondary btn-sm disabled">
                                <i class="fas fa-times-circle"></i> Tidak Tersedia
                            </span>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="pagination-section">
        @if($books->hasPages())
            <div class="pagination-container">
                @if(!$books->onFirstPage())
                    <a href="{{ $books->previousPageUrl() }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </a>
                @endif
                
                <span class="pagination-info">
                    Halaman {{ $books->currentPage() }} dari {{ $books->lastPage() }}
                </span>
                
                @if($books->hasMorePages())
                    <a href="{{ $books->nextPageUrl() }}" class="btn btn-primary btn-sm">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </a>
                @endif
            </div>
        @endif
    </div>
@else
    <div class="empty-state">
        <div class="empty-state-content">
            <i class="fas fa-books fa-3x"></i>
            <h3>Tidak Ada Buku Ditemukan</h3>
            @if(request()->anyFilled(['search', 'type', 'available']))
                <p>Tidak ada buku yang sesuai dengan kriteria pencarian Anda.</p>
                <a href="{{ route('books.index') }}" class="btn btn-primary">
                    <i class="fas fa-list"></i> Lihat Semua Buku
                </a>
            @else
                <p>Belum ada buku dalam koleksi perpustakaan.</p>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('books.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tambah Buku Pertama
                    </a>
                @endif
            @endif
        </div>
    </div>
@endif
@endsection

<!-- Search and Filters -->
<div class="card">
    <form method="GET" action="{{ route('books.index') }}" style="display: flex; gap: 10px; align-items: end;">
        <div class="form-group" style="flex: 1; margin-bottom: 0;">
            <label for="search">Cari Buku</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}" 
                   placeholder="Cari berdasarkan judul, penulis, atau ISBN...">
        </div>
        
        <div class="form-group" style="margin-bottom: 0;">
            <label for="type">Jenis</label>
            <select id="type" name="type">
                <option value="">Semua Jenis</option>
                <option value="light_novel" {{ request('type') == 'light_novel' ? 'selected' : '' }}>Light Novel</option>
                <option value="manga" {{ request('type') == 'manga' ? 'selected' : '' }}>Manga</option>
            </select>
        </div>
        
        <div class="form-group" style="margin-bottom: 0;">
            <label for="available">Ketersediaan</label>
            <select id="available" name="available">
                <option value="">Semua</option>
                <option value="1" {{ request('available') == '1' ? 'selected' : '' }}>Hanya Tersedia</option>
            </select>
        </div>
        
        <button type="submit" class="btn">Cari</button>
        @if(request()->anyFilled(['search', 'type', 'available']))
            <a href="{{ route('books.index') }}" class="btn" style="background: #6c757d;">Reset</a>
        @endif
    </form>
</div>

<!-- Books Grid -->
@if($books->count() > 0)
    <div class="grid grid-4">
        @foreach($books as $book)
        <div class="card">
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" 
                     style="width: 100%; height: 200px; object-fit: cover; margin-bottom: 15px;">
            @else
                <div style="width: 100%; height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; border: 1px solid #ddd;">
                    <span style="color: #6c757d;">📚 No Image</span>
                </div>
            @endif
            
            <h4>{{ $book->title }}</h4>
            <p><strong>Author:</strong> {{ $book->author }}</p>
            <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $book->type)) }}</p>
            <p><strong>Available:</strong> {{ $book->available_copies }}/{{ $book->total_copies }}</p>
            
            @if($book->price)
                <p><strong>Price:</strong> Rp {{ number_format($book->price, 0, ',', '.') }}</p>
            @endif
            
            <div class="mt-20">
                <a href="{{ route('books.show', $book) }}" class="btn btn-sm">View Details</a>
                
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('books.edit', $book) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form method="POST" action="{{ route('books.destroy', $book) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('Are you sure you want to delete this book?')">Delete</button>
                    </form>
                @else
                    @if($book->isAvailable())
                        <form method="POST" action="{{ route('books.borrow', $book) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Borrow</button>
                        </form>
                    @else
                        <span class="btn btn-sm" style="background: #ccc;">Not Available</span>
                    @endif
                @endif
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="text-center mt-20">
        @if($books->hasPages())
            <div style="display: flex; justify-content: center; align-items: center; gap: 10px;">
                @if($books->onFirstPage())
                    <span class="btn" style="background: #ccc;">Previous</span>
                @else
                    <a href="{{ $books->previousPageUrl() }}" class="btn">Previous</a>
                @endif
                
                <span>Page {{ $books->currentPage() }} of {{ $books->lastPage() }}</span>
                
                @if($books->hasMorePages())
                    <a href="{{ $books->nextPageUrl() }}" class="btn">Next</a>
                @else
                    <span class="btn" style="background: #ccc;">Next</span>
                @endif
            </div>
        @endif
    </div>  
@else
    <div class="card text-center">
        <h3>No books found</h3>
        <p>{{ request()->hasAny(['search', 'type', 'available']) ? 'Try adjusting your search criteria.' : 'No books have been added yet.' }}</p>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('books.create') }}" class="btn btn-success">Add First Book</a>
        @endif
    </div>
@endif
@endsection