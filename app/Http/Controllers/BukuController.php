<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource for admin.
     */
    public function index(Request $request)
    {
        $query = Buku::query();

        // Logika untuk filter 'search'
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        // Logika untuk filter 'type'
        if ($request->filled('type') && in_array($request->input('type'), ['light_novel', 'manga'])) {
            $query->where('type', $request->input('type'));
        }

        // Logika untuk filter 'ketersediaan' (available)
        if ($request->input('available') == '1') {
            $query->where('available_copies', '>', 0);
        }

        // Atur pengurutan (sorting)
        $sort = $request->input('sort', 'title');
        $direction = $request->input('direction', 'asc');
        
        // Cek apakah kolom sort ada dan valid
        $validSorts = ['title', 'created_at', 'available_copies'];
        if (in_array($sort, $validSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('title', 'asc'); // Default sort
        }

        // Ambil data buku dengan pagination
        $books = $query->paginate(8)->withQueryString();

        return view('books.index', compact('books'));
    }

    /**
     * Display a listing of books for regular users to browse.
     */
    public function browse(Request $request)
    {
        $query = Buku::where('is_active', true);

        // Logika untuk filter 'search'
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        // Logika untuk filter 'type'
        if ($request->filled('type') && in_array($request->input('type'), ['light_novel', 'manga'])) {
            $query->where('type', $request->input('type'));
        }

        // Logika untuk filter 'ketersediaan' (available)
        if ($request->input('available') == '1') {
            $query->where('available_copies', '>', 0);
        }

        // Atur pengurutan (sorting)
        $sort = $request->input('sort', 'title');
        $direction = $request->input('direction', 'asc');
        
        // Cek apakah kolom sort ada dan valid
        $validSorts = ['title', 'created_at', 'available_copies'];
        if (in_array($sort, $validSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('title', 'asc'); // Default sort
        }

        // Ambil data buku dengan pagination
        $books = $query->paginate(8)->withQueryString();

        return view('books.show', compact('books'));
    }

    // ... method lainnya (show, create, store, edit, update, destroy, borrow)
    // Sisa kode di bawah ini tidak perlu diubah karena sudah benar.
    
    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:buku,isbn',
            'type' => 'required|in:light_novel,manga',
            'description' => 'nullable|string',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'total_copies' => 'required|integer|min:1|max:1000',
            'price' => 'nullable|numeric|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        try {
            // Handle file upload
            $coverImagePath = null;
            if ($request->hasFile('cover_image')) {
                $image = $request->file('cover_image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $coverImagePath = $image->storeAs('book_covers', $filename, 'public');
            }

            // Set available_copies sama dengan total_copies untuk buku baru
            $validated['available_copies'] = $validated['total_copies'];
            $validated['cover_image'] = $coverImagePath;
            $validated['is_active'] = true;

            // Buat buku baru
            $buku = Buku::create($validated);

            return redirect()->route('books.index')
                ->with('success', 'Buku "' . $buku->title . '" berhasil ditambahkan!');

        } catch (\Exception $e) {
            // Hapus file yang sudah diupload jika ada error
            if ($coverImagePath && Storage::disk('public')->exists($coverImagePath)) {
                Storage::disk('public')->delete($coverImagePath);
            }

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan buku. Silakan coba lagi.');
        }
    }
    
    public function show(Buku $buku)
    {
        // Load relasi yang diperlukan untuk tampilan detail
        $buku->load(['peminjaman.user']);
        
        return view('books.show', compact('buku'));
    }

    public function edit(Buku $buku)
    {
        return view('books.edit', compact('buku'));
    }

    public function update(Request $request, Buku $buku)
    {
        // Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:buku,isbn,' . $buku->id,
            'type' => 'required|in:light_novel,manga',
            'description' => 'nullable|string',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'total_copies' => 'required|integer|min:1|max:1000',
            'price' => 'nullable|numeric|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $oldCoverImage = $buku->cover_image;
            
            // Handle file upload jika ada
            if ($request->hasFile('cover_image')) {
                $image = $request->file('cover_image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $validated['cover_image'] = $image->storeAs('book_covers', $filename, 'public');
            }

            // Update available_copies jika total_copies berubah
            $currentAvailable = $buku->available_copies;
            $currentTotal = $buku->total_copies;
            $newTotal = $validated['total_copies'];
            
            if ($newTotal !== $currentTotal) {
                $borrowedCopies = $currentTotal - $currentAvailable;
                $validated['available_copies'] = max(0, $newTotal - $borrowedCopies);
            }

            // Update buku
            $buku->update($validated);

            // Hapus file cover lama jika ada file baru
            if ($request->hasFile('cover_image') && $oldCoverImage && Storage::disk('public')->exists($oldCoverImage)) {
                Storage::disk('public')->delete($oldCoverImage);
            }

            return redirect()->route('books.index')
                ->with('success', 'Buku "' . $buku->title . '" berhasil diperbarui!');

        } catch (\Exception $e) {
            // Hapus file baru jika ada error
            if (isset($validated['cover_image']) && Storage::disk('public')->exists($validated['cover_image'])) {
                Storage::disk('public')->delete($validated['cover_image']);
            }

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui buku. Silakan coba lagi.');
        }
    }
    
    public function destroy(Buku $buku)
    {
        try {
            // Cek apakah buku sedang dipinjam
            $activeBorrowings = $buku->activeBorrowings()->count();
            
            if ($activeBorrowings > 0) {
                return back()->with('error', 'Tidak dapat menghapus buku "' . $buku->title . '" karena sedang dipinjam oleh ' . $activeBorrowings . ' orang.');
            }

            $title = $buku->title;
            
            // Hapus file cover jika ada
            if ($buku->cover_image && Storage::disk('public')->exists($buku->cover_image)) {
                Storage::disk('public')->delete($buku->cover_image);
            }

            // Hapus buku
            $buku->delete();

            return redirect()->route('books.index')
                ->with('success', 'Buku "' . $title . '" berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus buku. Silakan coba lagi.');
        }
    }

    public function borrow(Request $request, Buku $buku)
    {
        $user = auth()->user();
        
        // Validasi apakah user adalah regular user (bukan admin)
        if (!$user->isUser()) {
            return back()->with('error', 'Hanya user biasa yang dapat meminjam buku.');
        }
        
        // Validasi apakah buku tersedia
        if (!$buku->isAvailable()) {
            return back()->with('error', 'Buku tidak tersedia untuk dipinjam.');
        }
        
        // Validasi apakah user sudah meminjam buku yang sama dan belum dikembalikan
        $existingBorrowing = $user->peminjaman()
            ->where('buku_id', $buku->id)
            ->where('status', 'dipinjam')
            ->first();
            
        if ($existingBorrowing) {
            return back()->with('error', 'Anda sudah meminjam buku ini. Kembalikan terlebih dahulu sebelum meminjam lagi.');
        }
        
        // Buat record peminjaman baru
        $borrowing = $user->peminjaman()->create([
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now(),
            'tanggal_kembali_rencana' => now()->addDays(7), // 7 hari dari sekarang
            'status' => 'dipinjam',
            'denda' => 0,
        ]);
        
        // Kurangi available_copies
        $buku->decrement('available_copies');
        
        return back()->with('success', 'Buku berhasil dipinjam! Harap kembalikan sebelum ' . 
            $borrowing->tanggal_kembali_rencana->format('d M Y') . '.');
    }
}