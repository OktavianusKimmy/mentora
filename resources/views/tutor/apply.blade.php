@extends('layouts.app')

@section('content')

<div class="max-w-6xl">

<!-- HEADER -->
<div class="mb-8">
    <p class="text-sm font-semibold text-blue-600 mb-2">
        Mentora • Tutor Program
    </p>

    <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">
        Jadi Tutor
    </h1>

    <p class="text-gray-500 mt-2">
        Bagikan pengalaman dan bantu siswa lain mencapai target SNBT mereka.
    </p>
</div>

@auth

    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 p-4 rounded-xl mb-6">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- INFO CARD -->
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 mb-6">

        <h2 class="font-bold text-lg mb-1">
            Persyaratan Tutor
        </h2>

        <ul class="space-y-1 text-gray-700">
            <li>• Upload hasil UTBK sebagai bukti kompetensi.</li>
            <li>• Jelaskan alasan mengapa kamu ingin menjadi tutor.</li>
            <li>• Pengajuan akan direview oleh admin Mentora.</li>
            <li>• Setelah disetujui, akun akan mendapatkan role Tutor.</li>
        </ul>

    </div>

    <!-- FORM -->
    <form method="POST"
          action="{{ url('/tutor/apply') }}"
          enctype="multipart/form-data"
          class="bg-white p-8 rounded-2xl shadow border border-gray-100">

        @csrf

        <div class="space-y-2 mb-3">

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Keahlian
            </label>

            <label class="flex items-center gap-2">
                <input type="checkbox" name="tps">
                TPS
            </label>

            <label class="flex items-center gap-2">
                <input type="checkbox" name="literasi">
                Literasi
            </label>

            <label class="flex items-center gap-2">
                <input type="checkbox" name="numerasi">
                Numerasi
            </label>

        </div>

        <!-- FILE -->
        <div class="mb-3">

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Hasil UTBK
            </label>

             <input type="file" name="utbk_file" id="file-input" class="block w-full bg-layer border border-layer-line rounded-lg text-sm text-foreground placeholder:text-muted-foreground-1 focus:z-10 focus:outline-hidden focus:border-primary-focus focus:ring-1 focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none
                file:bg-primary file:border-0
                file:me-4 file:text-white
                file:py-3 file:px-4">

            <p class="text-xs text-gray-400 mt-2">
                Format: PDF, JPG, PNG (maksimal 2 MB)
            </p>

        </div>

        <!-- REASON -->
        <div class="mb-6">

            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Alasan Ingin Menjadi Tutor
            </label>

            <textarea
                name="reason"
                rows="5"
                required
                placeholder="Ceritakan pengalaman, prestasi, dan motivasi kamu menjadi tutor..."
                class="w-full border border-gray-300 rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-primary"></textarea>

        </div>

        <!-- BUTTON -->
        <div class="flex justify-end">

            <button
                type="submit"
                class="bg-primary text-white font-semibold px-6 py-3 rounded-xl hover:opacity-90 transition">
                Kirim Pengajuan
            </button>

        </div>

    </form>

@else

    <div class="bg-white rounded-2xl shadow p-10 text-center">

        <div class="text-5xl mb-4">
            🔒
        </div>

        <h2 class="text-2xl font-bold mb-2">
            Login Diperlukan
        </h2>

        <p class="text-gray-500 mb-6">
            Silakan login terlebih dahulu untuk mengajukan diri sebagai tutor.
        </p>

        <a href="{{ route('login') }}"
           class="bg-primary text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition">
            Login Sekarang
        </a>

    </div>

@endauth

</div>

@endsection
