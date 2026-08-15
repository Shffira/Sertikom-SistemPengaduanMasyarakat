@extends('layouts.app')
@section('content')

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center gap-3 mb-6">
        <div class="bg-yellow-100 text-yellow-600 rounded-full p-2"><i class="fa fa-edit"></i></div>
        <div>
            <h1 class="text-lg font-bold text-gray-800">Form Edit Pengaduan</h1>
            <p class="text-sm text-gray-400">Ubah data pengaduan Anda.</p>
        </div>
    </div>

    <form action="{{ route('complaints.update', $complaint) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Pengaduan</label>
            <input type="text" name="title" value="{{ old('title', $complaint->title) }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Kejadian</label>
            <input type="text" name="location" value="{{ old('location', $complaint->location) }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('location')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Pengaduan</label>
            <textarea name="description" rows="4"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $complaint->description) }}</textarea>
            @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Bukti Saat Ini</label>
           @if($complaint->photo)
<div class="flex items-center gap-3 mb-2">
    @if(str_starts_with($complaint->photo, 'http'))
        <img src="{{ $complaint->photo }}" class="w-24 h-20 object-cover rounded">
        <span class="text-sm text-gray-500">Foto dari URL</span>
    @else
        <img src="{{ asset('storage/'.$complaint->photo) }}" class="w-24 h-20 object-cover rounded">
        <span class="text-sm text-gray-500">{{ basename($complaint->photo) }}</span>
    @endif
</div>
@endif
            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Foto Baru (Opsional)</label>
            <input type="file" name="photo" accept="image/*"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <p class="text-xs text-gray-400 mt-1">Format: jpg, jpeg, png. Maks 2MB</p>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">
                Update Pengaduan
            </button>
            <a href="{{ route('complaints.index') }}" class="bg-gray-100 text-gray-600 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection