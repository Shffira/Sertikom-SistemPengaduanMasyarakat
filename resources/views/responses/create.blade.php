@extends('layouts.app')
@section('content')

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center gap-3 mb-6">
        <div class="bg-blue-100 text-blue-600 rounded-full p-2"><i class="fa fa-reply"></i></div>
        <div>
            <h1 class="text-lg font-bold text-gray-800">Form Respon Admin</h1>
            <p class="text-sm text-gray-400">Berikan respon dan ubah status pengaduan.</p>
        </div>
    </div>

    <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-2 text-sm">
        <div><span class="text-gray-400">Pengaduan:</span> <span class="font-medium">{{ $complaint->title }}</span></div>
        <div><span class="text-gray-400">Pelapor:</span> <span class="text-gray-700">{{ $complaint->user->name }}</span></div>
        <div><span class="text-gray-400">Tanggal Pengaduan:</span> <span class="text-gray-700">{{ $complaint->created_at->format('d M Y H:i') }}</span></div>
    </div>

    <form action="{{ route('responses.store', $complaint) }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status Pengaduan</label>
            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="pending" {{ $complaint->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="diproses" {{ $complaint->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ $complaint->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ $complaint->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Respon Admin</label>
            <textarea name="response" rows="5" maxlength="1000" placeholder="Tulis respon admin..."
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('response') border-red-500 @enderror">{{ old('response') }}</textarea>
            <p class="text-xs text-gray-400 mt-1">Maksimal 1000 karakter</p>
            @error('response')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg text-sm hover:bg-green-600">
                Simpan Respon
            </button>
            <a href="{{ route('complaints.index') }}" class="bg-gray-100 text-gray-600 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection