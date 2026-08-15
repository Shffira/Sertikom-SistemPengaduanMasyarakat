<?php
namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            $complaints = Complaint::with('user')->latest()->paginate(10);
        } else {
            $complaints = Complaint::where('user_id', auth()->id())->latest()->paginate(10);
        }
        return view('complaints.index', compact('complaints'));
    }

    public function create()
    {
        return view('complaints.create');
    }

    public function show(Complaint $complaint)
    {
        if (auth()->user()->role !== 'admin' && $complaint->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }
        return view('complaints.show', compact('complaint'));
    }

    public function edit(Complaint $complaint)
    {
        if (auth()->user()->role !== 'admin' && $complaint->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }
        if (auth()->user()->role !== 'admin' && $complaint->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengaduan tidak dapat diedit karena sudah diproses.');
        }
        return view('complaints.edit', compact('complaint'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
        ]);

        $photo = null;

        if ($request->photo_type === 'kamera' && $request->photo_base64) {
            $imageData = $request->photo_base64;
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = base64_decode($imageData);
            $filename = 'complaints/' . uniqid() . '.jpg';
            \Storage::disk('public')->put($filename, $imageData);
            $photo = $filename;
        } elseif ($request->photo_type === 'link' && $request->photo_url) {
            $photo = $request->photo_url;
        }

        Complaint::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'photo' => $photo,
            'status' => 'pending',
        ]);

        return redirect()->route('complaints.index')->with('success', 'Pengaduan berhasil dikirim!');
    }

    public function update(Request $request, Complaint $complaint)
    {
        if (auth()->user()->role !== 'admin' && $complaint->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
        ]);
        $complaint->update($request->only(['title', 'description', 'location']));
        return redirect()->route('complaints.index')->with('success', 'Pengaduan berhasil diupdate!');
    }

    public function destroy(Complaint $complaint)
    {
        if (auth()->user()->role !== 'admin' && $complaint->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }
        $complaint->delete();
        return redirect()->route('complaints.index')->with('success', 'Pengaduan berhasil dihapus!');
    }

    public function responMasuk()
    {
        $complaints = Complaint::with('user')
                        ->where('status', 'pending')
                        ->latest()
                        ->paginate(10);

        return view('complaints.index', compact('complaints'));
    }
}