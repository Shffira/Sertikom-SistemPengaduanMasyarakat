<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Pengaduan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-sm p-8 w-full max-w-md mx-4">

        {{-- Header --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="bg-blue-50 rounded-full p-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Form Register</h1>
                <p class="text-sm text-gray-400">Buat akun baru untuk menggunakan sistem.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            {{-- Name --}}
            <div>
                <label class="block text-sm text-gray-500 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    placeholder="Masukkan nama lengkap"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm text-gray-500 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="Masukkan email"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm text-gray-500 mb-1">Password</label>
                <input type="password" name="password"
                    placeholder="Masukkan password"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-400 @enderror">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="block text-sm text-gray-500 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                    placeholder="Ulangi password"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Role --}}
            {{-- Role --}}
            <div>
                <label class="block text-sm text-gray-500 mb-1">Role</label>
                <select name="role"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="masyarakat">Masyarakat</option>
                    {{-- Admin hanya dibuat lewat tinker/seeder, tidak bisa register --}}
                </select>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-gray-600">
                    Sudah punya akun? Login disini
                </a>
                <button type="submit"
                    class="bg-blue-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-blue-700 transition">
                    Daftar
                </button>
            </div>

        </form>
    </div>

</body>
</html>