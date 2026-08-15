<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengaduan Masyarakat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-white shadow-md flex flex-col justify-between">
        <div>
            {{-- Logo --}}
            <div class="flex items-center gap-3 px-6 py-5 border-b">
                <div class="bg-blue-600 text-white rounded-lg p-2">
                    <i class="fa fa-bullhorn"></i>
                </div>
                <div>
                    <p class="font-bold text-sm text-gray-800">Sistem Pengaduan</p>
                    <p class="text-xs text-gray-400">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Masyarakat' }}</p>
                </div>
            </div>

            {{-- Menu --}}
            <nav class="mt-4 px-4 space-y-1">
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium
                   {{ request()->routeIs('admin.dashboard') || request()->routeIs('user.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fa fa-home w-4"></i> Dashboard
                </a>

                @if(auth()->user()->role === 'admin')
                <a href="{{ route('complaints.index') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium
                   {{ request()->routeIs('complaints.index') && !request()->has('status') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fa fa-list w-4"></i> Semua Pengaduan
                </a>
                <a href="{{ route('complaints.index') }}?status=pending"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium
                   {{ request()->routeIs('complaints.index') && request()->status === 'pending' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fa fa-inbox w-4"></i> Respon Masuk
                </a>
                <a href="{{ route('complaints.index') }}?status=selesai"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium
                   {{ request()->routeIs('complaints.index') && request()->status === 'selesai' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fa fa-check-circle w-4"></i> Laporan Selesai
                </a>
                @else
                <a href="{{ route('complaints.index') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium
                   {{ request()->routeIs('complaints.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fa fa-file-alt w-4"></i> Pengaduan Saya
                </a>
                <a href="{{ route('complaints.create') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium
                   {{ request()->routeIs('complaints.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fa fa-plus-circle w-4"></i> Buat Pengaduan
                </a>
                @endif
            </nav>
        </div>

        {{-- Logout --}}
        <div class="px-4 py-4 border-t">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 px-4 py-2 w-full text-sm text-gray-600 hover:bg-gray-100 rounded-lg">
                    <i class="fa fa-sign-out-alt w-4"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- TOPBAR --}}
        <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
            <button class="text-gray-500 text-xl"><i class="fa fa-bars"></i></button>
            <div class="flex items-center gap-4">
                <button class="relative text-gray-500">
                    <i class="fa fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1">3</span>
                </button>
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center">
                        <i class="fa fa-user text-gray-500"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="flex-1 overflow-y-auto p-6">

            {{-- Flash Messages --}}
            @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 2500, showConfirmButton: false });
                });
            </script>
            @endif
            @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', timer: 2500, showConfirmButton: false });
                });
            </script>
            @endif

            @yield('content')
        </main>
    </div>
</div>

</body>
</html>