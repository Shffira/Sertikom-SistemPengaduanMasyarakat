<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
{
    $search = $request->get('search');
    $status = $request->get('status'); // filter tambahan by status

    $complaint = Complaint::with('user')
        ->when($search, function ($query, $search) {
            $query->where('judul', 'like', "%{$search}%")
        ->orWhere('lokasi', 'like', "%{$search}%")
        ->orWhereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        });
        })
        ->when($status, function ($query, $status) {
            $query->where('status', $status);
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    // Hitung statistik
    $totalComplaint = Complaint::count();
    $totalPending   = Complaint::where('status', 'pending')->count();
    $totalDiproses  = Complaint::where('status', 'diproses')->count();
    $totalSelesai   = Complaint::where('status', 'selesai')->count();
    $totalDitolak   = Complaint::where('status', 'ditolak')->count();

    return view('admin.dashboard', compact(
        'complaint', 'search', 'status',
        'totalComplaint', 'totalPending', 'totalDiproses', 'totalSelesai', 'totalDitolak'
    ));
}
}
