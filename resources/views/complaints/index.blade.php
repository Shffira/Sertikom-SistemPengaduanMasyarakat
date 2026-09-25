@extends('layouts.app')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

        <div>
            <div class="flex items-center gap-2">

                @if(request('status') === 'selesai')
                    <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center">
                        <i class="fa fa-check text-green-600"></i>
                    </div>

                    <h1 class="text-xl font-bold text-gray-800">
                        Laporan Selesai
                    </h1>

                @elseif(request('status') === 'pending')
                    <div class="w-9 h-9 rounded-lg bg-yellow-100 flex items-center justify-center">
                        <i class="fa fa-clock text-yellow-600"></i>
                    </div>

                    <h1 class="text-xl font-bold text-gray-800">
                        Pengaduan Masuk
                    </h1>

                @elseif(request('status') === 'diproses')
                    <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
                        <i class="fa fa-spinner text-blue-600"></i>
                    </div>

                    <h1 class="text-xl font-bold text-gray-800">
                        Pengaduan Diproses
                    </h1>

                @else
                    <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
                        <i class="fa fa-list text-blue-600"></i>
                    </div>

                    <h1 class="text-xl font-bold text-gray-800">
                        Tabel Pengaduan
                    </h1>
                @endif

            </div>

            <p class="text-sm text-gray-400 mt-1 ml-11">
                @if(request('status') === 'selesai')
                    Daftar pengaduan masyarakat yang telah diselesaikan.
                @elseif(request('status') === 'pending')
                    Daftar pengaduan yang menunggu respon Admin.
                @elseif(request('status') === 'diproses')
                    Daftar pengaduan yang sedang diproses.
                @else
                    Daftar semua pengaduan masyarakat.
                @endif
            </p>
        </div>

        @if(auth()->user()->role === 'masyarakat')

            <a href="{{ route('complaints.create') }}"
               class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm px-4 py-2.5 rounded-lg hover:bg-blue-700 transition">

                <i class="fa fa-plus"></i>

                Tambah Pengaduan

            </a>

        @endif

    </div>


    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead>

                <tr class="text-left text-gray-400 border-b border-gray-100">

                    <th class="pb-4 pr-4 font-medium w-12">
                        No
                    </th>

                    <th class="pb-4 pr-6 font-medium min-w-[320px]">
                        Pengaduan
                    </th>

                    <th class="pb-4 pr-6 font-medium min-w-[180px]">
                        Pelapor
                    </th>

                    <th class="pb-4 pr-6 font-medium min-w-[200px]">
                        Lokasi
                    </th>

                    <th class="pb-4 pr-6 font-medium w-32">
                        Tanggal
                    </th>

                    <th class="pb-4 pr-6 font-medium w-28">
                        Status
                    </th>

                    <th class="pb-4 font-medium w-40">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($complaints as $i => $c)

                <tr class="border-b border-gray-100 hover:bg-gray-50/70 transition">

                    {{-- NO --}}
                    <td class="py-4 pr-4 text-gray-400 align-top">

                        {{ $complaints->firstItem() + $i }}

                    </td>


                    {{-- PENGADUAN --}}
                    <td class="py-4 pr-6 align-top">

                        <div class="flex items-start gap-3">

                            {{-- FOTO --}}
                            @if($c->photo)

                                @if(str_starts_with($c->photo, 'http'))

                                    <img
                                        src="{{ $c->photo }}"
                                        class="w-12 h-12 object-cover rounded-lg border border-gray-100 flex-shrink-0"
                                    >

                                @else

                                    <img
                                        src="{{ asset('storage/'.$c->photo) }}"
                                        class="w-12 h-12 object-cover rounded-lg border border-gray-100 flex-shrink-0"
                                    >

                                @endif

                            @else

                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">

                                    <i class="fa fa-image text-gray-300"></i>

                                </div>

                            @endif


                            {{-- TEXT --}}
                            <div class="min-w-0">

                                <p class="font-semibold text-blue-600 hover:text-blue-700 leading-5">

                                    {{ $c->title }}

                                </p>

                                <p class="text-xs text-gray-400 mt-1 leading-4">

                                    {{ Str::limit($c->description, 55) }}

                                </p>

                            </div>

                        </div>

                    </td>


                    {{-- PELAPOR --}}
                    <td class="py-4 pr-6 align-top">

                        <p class="font-medium text-gray-700">

                            {{ $c->user->name }}

                        </p>

                        <p class="text-xs text-gray-400 mt-1">

                            {{ $c->user->email }}

                        </p>

                    </td>


                    {{-- LOKASI --}}
                    <td class="py-4 pr-6 text-gray-600 align-top leading-5">

                        {{ $c->location }}

                    </td>


                    {{-- TANGGAL --}}
                    <td class="py-4 pr-6 text-xs text-gray-500 align-top whitespace-nowrap">

                        <div>
                            {{ $c->created_at->format('d M Y') }}
                        </div>

                        <div class="text-gray-400 mt-1">
                            {{ $c->created_at->format('H:i') }}
                        </div>

                    </td>


                    {{-- STATUS --}}
                    <td class="py-4 pr-6 align-top">

                        @php

                            $colors = [

                                'pending' =>
                                    'bg-yellow-50 text-yellow-600 border border-yellow-100',

                                'diproses' =>
                                    'bg-blue-50 text-blue-600 border border-blue-100',

                                'selesai' =>
                                    'bg-green-50 text-green-600 border border-green-100',

                                'ditolak' =>
                                    'bg-red-50 text-red-600 border border-red-100',

                            ];

                            $statusColor =
                                $colors[$c->status]
                                ?? 'bg-gray-50 text-gray-600 border border-gray-100';

                        @endphp


                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $statusColor }}">

                            @if($c->status === 'pending')
                                <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>

                            @elseif($c->status === 'diproses')
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5"></span>

                            @elseif($c->status === 'selesai')
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>

                            @elseif($c->status === 'ditolak')
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>

                            @endif

                            {{ ucfirst($c->status) }}

                        </span>

                    </td>


                    {{-- AKSI --}}
                    <td class="py-4 align-top">

                        <div class="flex items-center gap-2">

                            @if(auth()->user()->role === 'admin')

                                {{-- DETAIL --}}
                                <a
                                    href="{{ route('complaints.show', $c) }}"
                                    class="inline-flex items-center justify-center gap-1.5 bg-gray-100 text-gray-600 text-xs px-3 py-2 rounded-lg hover:bg-gray-200 transition"
                                    title="Lihat detail"
                                >
                                    <i class="fa fa-eye"></i>
                                    Detail
                                </a>


                                {{-- RESPON --}}
                                @if($c->status !== 'selesai' && $c->status !== 'ditolak')

                                    <a
                                        href="{{ route('responses.create', $c) }}"
                                        class="inline-flex items-center justify-center gap-1.5 bg-blue-500 text-white text-xs px-3 py-2 rounded-lg hover:bg-blue-600 transition"
                                    >

                                        <i class="fa fa-reply"></i>
                                        Respon

                                    </a>

                                @endif


                                {{-- DELETE --}}
                                <form
                                    action="{{ route('complaints.destroy', $c) }}"
                                    method="POST"
                                    class="delete-form"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="inline-flex items-center justify-center w-9 h-9 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition"
                                        title="Hapus"
                                    >

                                        <i class="fa fa-trash"></i>

                                    </button>

                                </form>


                            @else

                                {{-- DETAIL --}}
                                <a
                                    href="{{ route('complaints.show', $c) }}"
                                    class="inline-flex items-center justify-center w-9 h-9 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition"
                                    title="Lihat detail"
                                >

                                    <i class="fa fa-eye"></i>

                                </a>


                                {{-- EDIT --}}
                                @if($c->status === 'pending')

                                    <a
                                        href="{{ route('complaints.edit', $c) }}"
                                        class="inline-flex items-center justify-center w-9 h-9 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition"
                                        title="Edit"
                                    >

                                        <i class="fa fa-edit"></i>

                                    </a>


                                    {{-- DELETE --}}
                                    <form
                                        action="{{ route('complaints.destroy', $c) }}"
                                        method="POST"
                                        class="delete-form"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="inline-flex items-center justify-center w-9 h-9 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition"
                                            title="Hapus"
                                        >

                                            <i class="fa fa-trash"></i>

                                        </button>

                                    </form>

                                @endif

                            @endif

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td
                        colspan="7"
                        class="text-center py-16 text-gray-400"
                    >

                        <div class="flex flex-col items-center">

                            <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mb-3">

                                <i class="fa fa-inbox text-gray-300 text-xl"></i>

                            </div>

                            @if(request('status') === 'selesai')

                                <p class="font-medium text-gray-500">
                                    Belum ada laporan yang selesai
                                </p>

                            @elseif(request('status') === 'pending')

                                <p class="font-medium text-gray-500">
                                    Tidak ada pengaduan yang menunggu respon
                                </p>

                            @else

                                <p class="font-medium text-gray-500">
                                    Belum ada pengaduan
                                </p>

                            @endif

                            <p class="text-xs text-gray-400 mt-1">
                                Data akan muncul setelah tersedia.
                            </p>

                        </div>

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- FOOTER --}}
    @if($complaints->count() > 0)

        <div class="mt-5 pt-4 border-t border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">

            <p class="text-sm text-gray-400">

                Menampilkan

                <span class="font-medium text-gray-600">
                    {{ $complaints->firstItem() }}
                </span>

                -

                <span class="font-medium text-gray-600">
                    {{ $complaints->lastItem() }}
                </span>

                dari

                <span class="font-medium text-gray-600">
                    {{ $complaints->total() }}
                </span>

                data

            </p>


            <div>
                {{ $complaints->links() }}
            </div>

        </div>

    @endif

</div>


{{-- SWEETALERT DELETE --}}
<script>

document.querySelectorAll('.delete-form').forEach(form => {

    form.addEventListener('submit', function(e) {

        e.preventDefault();

        Swal.fire({

            title: 'Yakin hapus?',
            text: 'Data tidak bisa dikembalikan!',
            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',

            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'

        }).then((result) => {

            if (result.isConfirmed) {

                this.submit();

            }

        });

    });

});

</script>

@endsection