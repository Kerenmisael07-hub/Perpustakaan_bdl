<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
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

        // --- Ini Perbaikan yang Penting ---
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

    // ... method lainnya (show, create, store, edit, update, destroy, borrow)
    // Sisa kode di bawah ini tidak perlu diubah karena sudah benar.
    
    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        // ... kode store
    }
    
    public function show(Buku $buku)
    {
        // ... kode show
    }

    public function edit(Buku $buku)
    {
        // ... kode edit
    }

    public function update(Request $request, Buku $buku)
    {
        // ... kode update
    }
    
    public function destroy(Buku $buku)
    {
        // ... kode destroy
    }

    public function borrow(Request $request, Buku $buku)
    {
        // ... kode borrow
    }
}