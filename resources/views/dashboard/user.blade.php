@extends('layouts.app')
@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-500 text-sm">Selamat datang, {{ auth()->user()->name }}!</p>
</div>

<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-4">
        <div class="bg-blue-100 text-blue-600 rounded-full p-3"><i class="fa fa-file-alt text-xl"></i></div>
        <div>
            <p class="text-2xl font-bold">{{ $total }}</p>
            <p class="text-xs text-gray-400">Total Pengaduan</p>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-4">
        <div class="bg-yellow-100 text-yellow-500 rounded-full p-3"><i class="fa fa-clock text-xl"></i></div>
        <div>
            <p class="text-2xl font-bold">{{ $pending }}</p>
            <p class="text-xs text-gray-400">Pending</p>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-4">
        <div class="bg-green-100 text-green-500 rounded-full p-3"><i class="fa fa-check-circle text-xl"></i></div>
        <div>
            <p class="text-2xl font-bold">{{ $selesai }}</p>
            <p class="text-xs text-gray-400">Selesai</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold text-gray-800">Pengaduan Saya</h2>
        <a href="{{ route('complaints.create') }}" class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fa fa-plus"></i> Tambah Pengaduan
        </a>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-400 border-b">
                <th class="pb-3">No</th>
                <th class="pb-3">Judul</th>
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
                <td class="py-3 font-medium text-blue-600">{{ $c->title }}</td>
                <td class="py-3 text-gray-600">{{ $c->location }}</td>
                <td class="py-3 text-gray-500 text-xs">{{ $c->created_at->format('d M Y') }}</td>
                <td class="py-3">
                    @php $colors = ['pending'=>'bg-yellow-100 text-yellow-600','diproses'=>'bg-blue-100 text-blue-600','selesai'=>'bg-green-100 text-green-600','ditolak'=>'bg-red-100 text-red-600']; @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $colors[$c->status] }}">{{ ucfirst($c->status) }}</span>
                </td>
                <td class="py-3">
                    <div class="flex gap-2">
                        <a href="{{ route('complaints.show', $c) }}" class="bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded hover:bg-gray-200">
                            <i class="fa fa-eye"></i>
                        </a>
                        @if($c->status === 'pending')
                        <a href="{{ route('complaints.edit', $c) }}" class="bg-yellow-100 text-yellow-600 text-xs px-3 py-1 rounded hover:bg-yellow-200">
                            <i class="fa fa-edit"></i>
                        </a>
                        <form action="{{ route('complaints.destroy', $c) }}" method="POST" class="delete-form">
                            @csrf @method('DELETE')
                            <button class="bg-red-100 text-red-600 text-xs px-3 py-1 rounded hover:bg-red-200">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-8 text-gray-400">Belum ada pengaduan</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $complaints->links() }}</div>
</div>

<script>
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({ title: 'Yakin hapus?', icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#ef4444', confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal'
        }).then((result) => { if (result.isConfirmed) this.submit(); });
    });
});
</script>
@endsection