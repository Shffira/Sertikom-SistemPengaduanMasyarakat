@extends('layouts.app')
@section('content')

<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Tabel Pengaduan</h1>
            <p class="text-sm text-gray-400">Daftar semua pengaduan masyarakat.</p>
        </div>
        @if(auth()->user()->role === 'masyarakat')
        <a href="{{ route('complaints.create') }}" class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fa fa-plus"></i> Tambah Pengaduan
        </a>
        @endif
    </div>

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
                <td class="py-3 text-gray-500">{{ $complaints->firstItem() + $i }}</td>
                <td class="py-3">
                    <div class="flex items-center gap-3">
                      @if($c->photo)
    @if(str_starts_with($c->photo, 'http'))
        <img src="{{ $c->photo }}" class="w-12 h-10 object-cover rounded">
    @else
        <img src="{{ asset('storage/'.$c->photo) }}" class="w-12 h-10 object-cover rounded">
    @endif
@else
<div class="w-12 h-10 bg-gray-100 rounded flex items-center justify-center">
    <i class="fa fa-image text-gray-300"></i>
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
                <td class="py-3 text-xs text-gray-500">{{ $c->created_at->format('d M Y H:i') }}</td>
                <td class="py-3">
                    @php $colors = ['pending'=>'bg-yellow-100 text-yellow-600','diproses'=>'bg-blue-100 text-blue-600','selesai'=>'bg-green-100 text-green-600','ditolak'=>'bg-red-100 text-red-600']; @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $colors[$c->status] }}">{{ ucfirst($c->status) }}</span>
                </td>
                <td class="py-3">
                    <div class="flex gap-2">
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('responses.create', $c) }}" class="bg-blue-500 text-white text-xs px-3 py-1 rounded hover:bg-blue-600">
                            <i class="fa fa-reply"></i> Respon
                        </a>
                        <form action="{{ route('complaints.destroy', $c) }}" method="POST" class="delete-form">
                            @csrf @method('DELETE')
                            <button class="bg-red-500 text-white text-xs px-3 py-1 rounded hover:bg-red-600">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </form>
                        @else
                        <a href="{{ route('complaints.show', $c) }}" class="bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded">
                            <i class="fa fa-eye"></i>
                        </a>
                        @if($c->status === 'pending')
                        <a href="{{ route('complaints.edit', $c) }}" class="bg-yellow-400 text-white text-xs px-3 py-1 rounded">
                            <i class="fa fa-edit"></i>
                        </a>
                        <form action="{{ route('complaints.destroy', $c) }}" method="POST" class="delete-form">
                            @csrf @method('DELETE')
                            <button class="bg-red-500 text-white text-xs px-3 py-1 rounded">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                        @endif
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-10 text-gray-400">Belum ada pengaduan</td></tr>
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
            showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal'
        }).then((result) => { if (result.isConfirmed) this.submit(); });
    });
});
</script>
@endsection