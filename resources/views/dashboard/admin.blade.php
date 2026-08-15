@extends('layouts.app')
@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
    <p class="text-gray-500 text-sm">Berikut adalah ringkasan pengaduan masyarakat.</p>
</div>

{{-- STAT CARDS --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-4">
        <div class="bg-blue-100 text-blue-600 rounded-full p-3"><i class="fa fa-file-alt text-xl"></i></div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $total }}</p>
            <p class="text-xs text-gray-400">Total Pengaduan</p>
            <p class="text-xs text-gray-300">Semua pengaduan</p>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-4">
        <div class="bg-yellow-100 text-yellow-500 rounded-full p-3"><i class="fa fa-clock text-xl"></i></div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $pending }}</p>
            <p class="text-xs text-gray-400">Pending</p>
            <p class="text-xs text-gray-300">Menunggu diproses</p>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-4">
        <div class="bg-blue-100 text-blue-500 rounded-full p-3"><i class="fa fa-sync text-xl"></i></div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $diproses }}</p>
            <p class="text-xs text-gray-400">Diproses</p>
            <p class="text-xs text-gray-300">Sedang diproses</p>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-4">
        <div class="bg-green-100 text-green-500 rounded-full p-3"><i class="fa fa-check-circle text-xl"></i></div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $selesai }}</p>
            <p class="text-xs text-gray-400">Selesai</p>
            <p class="text-xs text-gray-300">Pengaduan selesai</p>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-4">
        <div class="bg-red-100 text-red-500 rounded-full p-3"><i class="fa fa-times-circle text-xl"></i></div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $ditolak }}</p>
            <p class="text-xs text-gray-400">Ditolak</p>
            <p class="text-xs text-gray-300">Pengaduan ditolak</p>
        </div>
    </div>
</div>

{{-- TABEL PENGADUAN TERBARU --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Pengaduan Terbaru</h2>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-400 border-b">
                <th class="pb-3">No</th>
                <th class="pb-3">Judul Pengaduan</th>
                <th class="pb-3">Pelapor</th>
                <th class="pb-3">Lokasi</th>
                <th class="pb-3">Tanggal</th>
                <th class="pb-3">Status</th>
                <th class="pb-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($complaints as $i => $c)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 text-gray-500">{{ $i + 1 }}</td>
                <td class="py-3">
                    <div class="flex items-center gap-3">
                     @if($c->photo)
    @if(str_starts_with($c->photo, 'http'))
        <img src="{{ $c->photo }}" class="w-12 h-10 object-cover rounded">
    @else
        <img src="{{ asset('storage/'.$c->photo) }}" class="w-12 h-10 object-cover rounded">
    @endif
@else
<div class="w-12 h-10 bg-gray-200 rounded flex items-center justify-center">
    <i class="fa fa-image text-gray-400"></i>
</div>
@endif
                        <div>
                            <p class="font-medium text-blue-600">{{ $c->title }}</p>
                            <p class="text-xs text-gray-400">{{ Str::limit($c->description, 40) }}</p>
                        </div>
                    </div>
                </td>
                <td class="py-3">
                    <p class="font-medium text-gray-700">{{ $c->user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $c->user->email }}</p>
                </td>
                <td class="py-3 text-gray-600">{{ $c->location }}</td>
                <td class="py-3 text-gray-500 text-xs">{{ $c->created_at->format('d M Y H:i') }}</td>
                <td class="py-3">
                    @php
                        $colors = ['pending'=>'bg-yellow-100 text-yellow-600','diproses'=>'bg-blue-100 text-blue-600','selesai'=>'bg-green-100 text-green-600','ditolak'=>'bg-red-100 text-red-600'];
                    @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $colors[$c->status] }}">
                        {{ ucfirst($c->status) }}
                    </span>
                </td>
                <td class="py-3">
                    <div class="flex gap-2">
                        <a href="{{ route('responses.create', $c) }}" class="bg-blue-500 text-white text-xs px-3 py-1 rounded hover:bg-blue-600">
                            <i class="fa fa-reply"></i> Respon
                        </a>
                        <form action="{{ route('complaints.destroy', $c) }}" method="POST" class="delete-form">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white text-xs px-3 py-1 rounded hover:bg-red-600">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-8 text-gray-400">Belum ada pengaduan</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4 flex items-center justify-between text-sm text-gray-400">
        <p>Menampilkan {{ $complaints->firstItem() }} - {{ $complaints->lastItem() }} dari {{ $complaints->total() }} data</p>
        {{ $complaints->links() }}
    </div>
</div>

<script>
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({ title: 'Yakin hapus?', text: 'Data tidak bisa dikembalikan!', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal'
        }).then((result) => { if (result.isConfirmed) this.submit(); });
    });
});
</script>
@endsection