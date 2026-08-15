<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{--  KARTU STATISTIK --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @foreach([
                    ['label' => 'Total',     'value' => $totalPengaduan, 'color' => 'blue'],
                    ['label' => 'Pending',   'value' => $totalPending,   'color' => 'yellow'],
                    ['label' => 'Diproses',  'value' => $totalDiproses,  'color' => 'indigo'],
                    ['label' => 'Selesai',   'value' => $totalSelesai,   'color' => 'green'],
                    ['label' => 'Ditolak',   'value' => $totalDitolak,   'color' => 'red'],
                ] as $stat)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 flex items-center gap-4">
                    <div class="text-3xl font-bold text-{{ $stat['color'] }}-600">
                        {{ $stat['value'] }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>

            {{--  TABEL DENGAN SEARCH & FILTER STATUS --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Toolbar --}}
                    <form method="GET" action="{{ route('admin.dashboard') }}">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5">

                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                Pengaduan Terbaru
                            </h3>

                            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">

                                {{-- Input Search --}}
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                                        </svg>
                                    </div>
                                    <input
                                        type="text"
                                        name="search"
                                        value="{{ $search ?? '' }}"
                                        placeholder="Cari judul, pelapor, lokasi..."
                                        class="pl-9 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm
                                               bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                               focus:outline-none focus:ring-2 focus:ring-blue-500 w-72"
                                    >
                                </div>

                                {{-- Filter Status --}}
                                <select
                                    name="status"
                                    class="py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm
                                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="this.form.submit()"
                                >
                                    <option value="">Semua Status</option>
                                    @foreach(['pending', 'diproses', 'selesai', 'ditolak'] as $s)
                                        <option value="{{ $s }}" {{ ($status ?? '') == $s ? 'selected' : '' }}>
                                            {{ ucfirst($s) }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- Tombol Search --}}
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition">
                                    Cari
                                </button>

                                {{-- Tombol Reset --}}
                                @if(!empty($search) || !empty($status))
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="px-4 py-2 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200
                                              text-gray-700 dark:text-gray-200 text-sm rounded-lg transition text-center">
                                        Reset
                                    </a>
                                @endif

                            </div>
                        </div>
                    </form>

                    {{-- Info hasil pencarian --}}
                    @if(!empty($search) || !empty($status))
                        <div class="mb-3 text-sm text-gray-500 dark:text-gray-400">
                            Menampilkan hasil untuk
                            @if(!empty($search)) kata kunci "<strong>{{ $search }}</strong>" @endif
                            @if(!empty($status)) dengan status "<strong>{{ ucfirst($status) }}</strong>" @endif
                            — {{ $pengaduan->total() }} data ditemukan.
                        </div>
                    @endif

                    {{-- Tabel --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                            <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Judul Pengaduan</th>
                                    <th class="px-4 py-3">Pelapor</th>
                                    <th class="px-4 py-3">Lokasi</th>
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengaduan as $item)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-4 py-3">
                                            {{ ($pengaduan->currentPage() - 1) * $pengaduan->perPage() + $loop->iteration }}
                                        </td>
                                        <td class="px-4 py-3 max-w-xs">
                                            <div class="font-medium text-gray-900 dark:text-white truncate">
                                                {{ $item->judul }}
                                            </div>
                                            <div class="text-xs text-gray-400 truncate">{{ Str::limit($item->deskripsi, 50) }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div>{{ $item->user->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-400">{{ $item->user->email ?? '' }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-xs">{{ $item->lokasi }}</td>
                                        <td class="px-4 py-3 text-xs whitespace-nowrap">
                                            {{ $item->created_at->format('d M Y') }}<br>
                                            <span class="text-gray-400">{{ $item->created_at->format('H:i') }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                @if($item->status == 'pending')  bg-yellow-100 text-yellow-700
                                                @elseif($item->status == 'diproses') bg-blue-100 text-blue-700
                                                @elseif($item->status == 'selesai')  bg-green-100 text-green-700
                                                @elseif($item->status == 'ditolak')  bg-red-100 text-red-700
                                                @endif">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex gap-2">
                                                <a href="{{ route('complaint.show', $item->id) }}"
                                                class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transition">
                                                    Respon
                                                </a>
                                                <form method="POST" action="{{ route('complaint.destroy', $item->id) }}">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                            onclick="return confirm('Yakin ingin menghapus pengaduan ini?')"
                                                            class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg transition">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-10 text-center text-gray-400">
                                            @if(!empty($search) || !empty($status))
                                                <p>Tidak ada data yang cocok.</p>
                                                <a href="{{ route('admin.dashboard') }}" class="text-blue-500 text-xs hover:underline mt-1 inline-block">
                                                    Tampilkan semua data
                                                </a>
                                            @else
                                                Belum ada pengaduan masuk.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination + info --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-2">
                        <p class="text-xs text-gray-400">
                            Menampilkan {{ $pengaduan->firstItem() ?? 0 }}–{{ $pengaduan->lastItem() ?? 0 }}
                            dari {{ $pengaduan->total() }} data
                        </p>
                        {{ $pengaduan->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>