@extends('layouts.app')
@section('content')

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center gap-3 mb-6">
        <div class="bg-blue-100 text-blue-600 rounded-full p-2"><i class="fa fa-file-alt"></i></div>
        <div>
            <h1 class="text-lg font-bold text-gray-800">Form Tambah Pengaduan</h1>
            <p class="text-sm text-gray-400">Sampaikan keluhan atau laporan Anda.</p>
        </div>
    </div>
    <script>
    let stream = null;

    function showTab(tab) {
        // Reset semua tab
        document.getElementById('tab-kamera-content').classList.add('hidden');
        document.getElementById('tab-link-content').classList.add('hidden');
        document.getElementById('tab-kamera').className = 'flex-1 py-2 text-sm rounded-lg border-2 border-gray-200 text-gray-500 font-medium transition';
        document.getElementById('tab-link').className = 'flex-1 py-2 text-sm rounded-lg border-2 border-gray-200 text-gray-500 font-medium transition';

        // Aktifkan tab yang dipilih
        document.getElementById('tab-' + tab + '-content').classList.remove('hidden');
        document.getElementById('tab-' + tab).className = 'flex-1 py-2 text-sm rounded-lg border-2 border-blue-500 bg-blue-50 text-blue-600 font-medium transition';

        // Update hidden input type
        document.getElementById('photo_type').value = tab;

        // Matikan kamera jika pindah tab
        if (tab !== 'kamera' && stream) {
            stream.getTracks().forEach(t => t.stop());
            stream = null;
        }
    }

    async function bukaKamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            const video = document.getElementById('video');
            video.srcObject = stream;
            video.classList.remove('hidden');
            document.getElementById('kamera-placeholder').classList.add('hidden');
            document.getElementById('btn-buka').classList.add('hidden');
            document.getElementById('btn-ambil').classList.remove('hidden');
        } catch(e) {
            alert('Kamera tidak dapat diakses. Pastikan browser memiliki izin kamera.');
        }
    }

    function ambilFoto() {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);

        const dataUrl = canvas.toDataURL('image/jpeg');
        document.getElementById('photo_base64').value = dataUrl;

        // Tampilkan preview
        const preview = document.getElementById('preview');
        preview.src = dataUrl;
        preview.classList.remove('hidden');
        video.classList.add('hidden');

        // Stop kamera
        stream.getTracks().forEach(t => t.stop());
        stream = null;

        document.getElementById('btn-ambil').classList.add('hidden');
        document.getElementById('btn-ulangi').classList.remove('hidden');
    }

    function ulangi() {
        document.getElementById('preview').classList.add('hidden');
        document.getElementById('photo_base64').value = '';
        document.getElementById('btn-ulangi').classList.add('hidden');
        document.getElementById('btn-buka').classList.remove('hidden');
        document.getElementById('kamera-placeholder').classList.remove('hidden');
    }

    function previewLink(url) {
        const img = document.getElementById('preview-link');
        if (url) {
            img.src = url;
            img.classList.remove('hidden');
            img.onerror = () => img.classList.add('hidden');
        } else {
            img.classList.add('hidden');
        }
    }
</script>

    <form action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Pengaduan</label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="Masukkan judul pengaduan"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
            @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Kejadian</label>
            <input type="text" name="location" value="{{ old('location') }}" placeholder="Masukkan lokasi kejadian"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('location') border-red-500 @enderror">
            @error('location')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Pengaduan</label>
            <textarea name="description" rows="4" placeholder="Tuliskan deskripsi pengaduan secara detail..."
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        {{-- Foto Bukti --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Bukti</label>

            {{-- Tab Pilihan --}}
            <div class="flex gap-2 mb-3">
                <button type="button" onclick="showTab('kamera')"
                    id="tab-kamera"
                    class="flex-1 py-2 text-sm rounded-lg border-2 border-blue-500 bg-blue-50 text-blue-600 font-medium transition">
                    <i class="fa fa-camera mr-1"></i> Kamera
                </button>
                <button type="button" onclick="showTab('link')"
                    id="tab-link"
                    class="flex-1 py-2 text-sm rounded-lg border-2 border-gray-200 text-gray-500 font-medium transition">
                    <i class="fa fa-link mr-1"></i> Link URL
                </button>
            </div>

            {{-- Tab Kamera --}}
            <div id="tab-kamera-content">
                <div class="border-2 border-dashed border-gray-200 rounded-lg p-4 text-center">
                    <video id="video" class="w-full rounded-lg hidden" autoplay></video>
                    <canvas id="canvas" class="w-full rounded-lg hidden"></canvas>
                    <img id="preview" class="w-full rounded-lg hidden object-cover max-h-48">

                    <div id="kamera-placeholder" class="py-6">
                        <i class="fa fa-camera text-4xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-400">Klik tombol di bawah untuk membuka kamera</p>
                    </div>

                    <div class="flex gap-2 mt-3 justify-center">
                        <button type="button" onclick="bukaKamera()"
                            id="btn-buka"
                            class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700">
                            <i class="fa fa-camera mr-1"></i> Buka Kamera
                        </button>
                        <button type="button" onclick="ambilFoto()"
                            id="btn-ambil"
                            class="bg-green-500 text-white text-sm px-4 py-2 rounded-lg hover:bg-green-600 hidden">
                            <i class="fa fa-circle mr-1"></i> Ambil Foto
                        </button>
                        <button type="button" onclick="ulangi()"
                            id="btn-ulangi"
                            class="bg-gray-400 text-white text-sm px-4 py-2 rounded-lg hover:bg-gray-500 hidden">
                            <i class="fa fa-redo mr-1"></i> Ulangi
                        </button>
                    </div>
                </div>
                {{-- Input hidden untuk hasil kamera --}}
                <input type="hidden" name="photo_base64" id="photo_base64">
                <input type="hidden" name="photo_type" id="photo_type" value="kamera">
            </div>

            {{-- Tab Link --}}
            <div id="tab-link-content" class="hidden">
                <input type="url" name="photo_url" id="photo_url"
                    placeholder="https://contoh.com/foto.jpg"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    oninput="previewLink(this.value)">
                <p class="text-xs text-gray-400 mt-1">Masukkan URL foto yang bisa diakses publik</p>
                <img id="preview-link" class="mt-3 w-full max-h-48 object-cover rounded-lg hidden">
            </div>

        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg text-sm hover:bg-green-600">
                Kirim Pengaduan
            </button>
            <a href="{{ route('complaints.index') }}" class="bg-gray-100 text-gray-600 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection