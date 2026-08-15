<?php
namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class DashboardController extends Controller {
    public function index() {
        if (auth()->user()->role === 'admin') {
            $total = Complaint::count();
            $pending = Complaint::where('status', 'pending')->count();
            $diproses = Complaint::where('status', 'diproses')->count();
            $selesai = Complaint::where('status', 'selesai')->count();
            $ditolak = Complaint::where('status', 'ditolak')->count();
            $complaints = Complaint::with('user')->latest()->paginate(5);
            return view('dashboard.admin', compact('total','pending','diproses','selesai','ditolak','complaints'));
        } else {
            $complaints = Complaint::where('user_id', auth()->id())->latest()->paginate(5);
            $total = Complaint::where('user_id', auth()->id())->count();
            $pending = Complaint::where('user_id', auth()->id())->where('status','pending')->count();
            $selesai = Complaint::where('user_id', auth()->id())->where('status','selesai')->count();
            return view('dashboard.user', compact('complaints','total','pending','selesai'));
        }
    }
}