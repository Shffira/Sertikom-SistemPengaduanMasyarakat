@extends('layouts.app')
@section('content')

<div class="max-w-2xl mx-auto space-y-4">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h1 class="text-xl font-bold text-gray-800 mb-4">Detail Pengaduan</h1>
        @if($complaint->photo)
    @if(str_starts_with($complaint->photo, 'http'))
        <img src="{{ $complaint->photo }}" class="w-full h-48 object-cover rounded-lg mb-4">
    @else
        <img src="{{ asset('storage/'.$complaint->photo) }}" class="w-full h-48 object-cover rounded-lg mb-4">
    @endif
@endif
        <div class="space-y-3 text-sm">
            <div><span class="text-gray-400">Judul:</span> <span class="font-medium text-gray-800">{{ $complaint->title }}</span></div>
            <div><span class="text-gray-400">Lokasi:</span> <span class="text-gray-700">{{ $complaint->location }}</span></div>
            <div><span class="text-gray-400">Deskripsi:</span> <p class="text-gray-700 mt-1">{{ $complaint->description }}</p></div>
            <div><span class="text-gray-400">Tanggal:</span> <span class="text-gray-700">{{ $complaint->created_at->format('d M Y H:i') }}</span></div>
            <div>
                <span class="text-gray-400">Status:</span>
                @php $colors = ['pending'=>'bg-yellow-100 text-yellow-600','diproses'=>'bg-blue-100 text-blue-600','selesai'=>'bg-green-100 text-green-600','ditolak'=>'bg-red-100 text-red-600']; @endphp
                <span class="ml-2 px-2 py-1 rounded-full text-xs font-medium {{ $colors[$complaint->status] }}">{{ ucfirst($complaint->status) }}</span>
            </div>
        </div>
    </div>

    @if($complaint->responses->count() > 0)
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-3">Respon Admin</h2>
        @foreach($complaint->responses as $res)
        <div class="border-l-4 border-blue-400 pl-4 mb-3">
            <p class="text-sm text-gray-700">{{ $res->response }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $res->admin->name }} • {{ $res->created_at->format('d M Y H:i') }}</p>
        </div>
        @endforeach
    </div>
    @endif

    <a href="{{ route('complaints.index') }}" class="inline-block bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>
@endsection