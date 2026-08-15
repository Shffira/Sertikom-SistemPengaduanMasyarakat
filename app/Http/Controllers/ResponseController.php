<?php
namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Response;
use Illuminate\Http\Request;

class ResponseController extends Controller {
    public function create(Complaint $complaint) {
        return view('responses.create', compact('complaint'));
    }

    public function store(Request $request, Complaint $complaint) {
        $request->validate([
            'response' => 'required|string|max:1000',
            'status' => 'required|in:pending,diproses,selesai,ditolak',
        ]);

        Response::create([
            'complaint_id' => $complaint->id,
            'admin_id' => auth()->id(),
            'response' => $request->response,
        ]);

        $complaint->update(['status' => $request->status]);

        return redirect()->route('complaints.index')->with('success', 'Respon berhasil dikirim!');
    }
}