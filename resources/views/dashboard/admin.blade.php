@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dashboard Admin</h2>

    <div class="row">
        <div class="col-md-3">Total Buku: {{ $totalBooks }}</div>
        <div class="col-md-3">Total Pengguna: {{ $totalUsers }}</div>
        <div class="col-md-3">Sedang Dipinjam: {{ $activeBorrowings }}</div>
        <div class="col-md-3">Terlambat: {{ $overdueBorrowings }}</div>
    </div>

    <h3>Peminjaman Terbaru</h3>
    <ul>
        @foreach($recentBorrowings as $borrow)
            <li>{{ $borrow->user->name }} meminjam {{ $borrow->buku->judul }}</li>
        @endforeach
    </ul>

    <h3>Daftar Terlambat</h3>
    <ul>
        @foreach($overdueList as $overdue)
            <li>{{ $overdue->user->name }} - {{ $overdue->buku->judul }}</li>
        @endforeach
    </ul>
</div>
@endsection
