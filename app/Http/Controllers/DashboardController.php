<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }
        
        return $this->userDashboard();
    }

    private function adminDashboard()
    {
        $data = [
            'totalBooks' => Buku::count(),
            'totalUsers' => User::where('role', 'user')->count(),
            'activeBorrowings' => Peminjaman::active()->count(),
            'overdueBorrowings' => Peminjaman::overdue()->count(),
            'recentBorrowings' => Peminjaman::with(['user', 'buku'])
                ->latest()
                ->take(5)
                ->get(),
            'overdueList' => Peminjaman::overdue()
                ->with(['user', 'buku'])
                ->get(),
        ];

        return view('dashboard.admin', $data);
    }

    private function userDashboard()
    {
        $user = auth()->user();
        
        $data = [
            'totalBooks' => Buku::count(),
            'borrowedBooks' => Peminjaman::where('user_id', $user->id)
                ->where('status', 'dipinjam')
                ->count(),
            'totalMembers' => User::count(),
            'overdueBooks' => Peminjaman::where('user_id', $user->id)
                ->where('status', 'dipinjam')
                ->where('tanggal_kembali_rencana', '<', now())
                ->count(),
            'latestActivities' => $user->peminjaman()
                ->with('buku')
                ->latest()
                ->take(5)
                ->get(),
            'activeBorrowings' => $user->activeBorrowings()->with('buku')->get(),
            'borrowingHistory' => $user->peminjaman()
                ->with('buku')
                ->latest()
                ->take(10)
                ->get(),
            'totalFines' => $user->peminjaman()
                ->where('denda', '>', 0)
                ->sum('denda'),
            'availableBooks' => Buku::active()->available()->take(6)->get(),
        ];

        return view('dashboard.user', $data);
    }
}
