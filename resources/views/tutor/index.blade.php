@extends('layouts.app')

@section('content')

<div class="max-w-6xl">

    <!-- STATUS APPLY -->
    @auth
        @if(isset($application) && $application && $application->status === 'pending')
            <div class="bg-yellow-100 text-yellow-700 p-4 rounded-xl mb-6">
                ⏳ Pengajuan kamu sedang direview
            </div>
        @endif
    @endauth

    <!-- HERO -->
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-6 mb-8">

        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">
                Mentora • Tutor
            </p>

            <h1 class="text-5xl font-extrabold tracking-tight text-gray-900">
                Tutor
            </h1>

            <p class="text-gray-500 mt-2">
                Belajar langsung dengan tutor berpengalaman untuk mempersiapkan SNBT.
            </p>
        </div>

        @auth
            @if(auth()->user()->role === 'user')
                <a href="{{ route('tutor.apply.page') }}"
                    class="bg-primary text-white font-semibold px-5 py-3 rounded-xl hover:opacity-90 transition">
                    Apply as Tutor
                </a>

            @elseif(auth()->user()->role === 'tutor')

                <span class="bg-green-100 text-green-700 px-5 py-3 rounded-xl font-semibold">
                    🎓 Kamu sudah tutor
                </span>

            @elseif(auth()->user()->role === 'admin')

                <a href="{{ route('admin.tutor') }}"
                    class="bg-red-500 text-white font-semibold px-5 py-3 rounded-xl hover:bg-red-600 transition">
                    Tutor Applications
                </a>

            @endif
        @else

            <a href="{{ route('login') }}"
                class="bg-primary text-white font-semibold px-5 py-3 rounded-xl">
                Login
            </a>

        @endauth

    </div>

    
    <!-- SEARCH -->
    <div class="mb-5">
        <input
            type="text"
            id="searchInput"
            placeholder="Cari tutor..."
            class="w-full px-4 py-2 bg-white border border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary">
    </div>

    <!-- FILTER -->
    <div class="flex flex-wrap gap-3 mb-8">

        <button class="filter-btn px-4 py-2 bg-primary text-white font-semibold rounded-full"
            data-filter="all">
            Semua
        </button>

        <button class="filter-btn px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-full"
            data-filter="tps">
            TPS
        </button>

        <button class="filter-btn px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-full"
            data-filter="numerasi">
            Numerasi
        </button>

        <button class="filter-btn px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-full"
            data-filter="literasi">
            Literasi
        </button>

    </div>

    <!-- LIST TUTOR -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        @forelse($tutors as $tutor)

            <div
                class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition tutor-card"
                data-name="{{ strtolower($tutor->user->name) }}"
                data-tps="{{ $tutor->tps ? 'true' : 'false' }}"
                data-literasi="{{ $tutor->literasi ? 'true' : 'false' }}"
                data-numerasi="{{ $tutor->numerasi ? 'true' : 'false' }}"
            >

                <!-- HEADER -->
                <div class="flex items-center gap-3 mb-4">

                    <div class="w-12 h-12 bg-primary text-white flex items-center justify-center rounded-full font-bold">
                        {{ strtoupper(substr($tutor->user->name, 0, 1)) }}
                    </div>

                    <div>
                        <h2 class="font-semibold text-lg">
                            {{ $tutor->user->name }}
                        </h2>

                        <p class="text-xs text-gray-400">
                            Tutor Mentora
                        </p>
                    </div>

                </div>

                <!-- SKILLS -->
                <div class="flex flex-wrap gap-2 mb-4">

                    @if($tutor->tps)
                        <span class="px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded-full">
                            TPS
                        </span>
                    @endif

                    @if($tutor->literasi)
                        <span class="px-2 py-1 bg-green-100 text-green-600 text-xs rounded-full">
                            Literasi
                        </span>
                    @endif

                    @if($tutor->numerasi)
                        <span class="px-2 py-1 bg-purple-100 text-purple-600 text-xs rounded-full">
                            Numerasi
                        </span>
                    @endif

                </div>

                <!-- RATING -->
                <p class="text-yellow-500 text-sm mb-2">
                    ⭐ {{ $tutor->rating }} ({{ $tutor->total_reviews }} review)
                </p>

                <!-- EMAIL -->
                <p class="text-sm text-gray-500 mb-3">
                    {{ $tutor->user->email }}
                </p>

                <!-- BIO -->
                <p class="text-xs text-gray-400 mb-4 line-clamp-3">
                    {{ $tutor->bio }}
                </p>

                <!-- BUTTONS -->
                <div class="flex gap-2">

                    <a
                        href="{{ route('tutor.show', $tutor->id) }}"
                        class="w-full block text-center border border-primary text-primary font-semibold py-2 rounded-xl hover:bg-primary hover:text-white transition"
                    >
                    Lihat Profil
                    </a>

                    <a
                        href="{{ route('tutor.show', $tutor->id) }}"
                        class="w-full block text-center bg-primary text-white font-semibold py-2 rounded-xl hover:opacity-90 transition"
                    >
                        Book Sekarang
                    </a>

                </div>

            </div>

        @empty

            <div class="col-span-3">

                <div class="bg-white rounded-2xl shadow p-10 text-center">

                    <div class="text-5xl mb-4">
                        👨‍🏫
                    </div>

                    <h3 class="font-bold text-xl mb-2">
                        Belum Ada Tutor
                    </h3>

                    <p class="text-gray-500">
                        Tutor yang telah disetujui akan muncul di sini.
                    </p>

                </div>

            </div>

        @endforelse

    </div>

</div>

@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchInput');
    const tutorCards = document.querySelectorAll('.tutor-card');
    const filterButtons = document.querySelectorAll('.filter-btn');

    let currentFilter = 'all';

    // SEARCH
    searchInput.addEventListener('input', applyFilter);

    // FILTER BUTTON
    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            currentFilter = btn.dataset.filter;

            filterButtons.forEach(b => {
                b.classList.remove('bg-primary', 'text-white')
                b.classList.add('bg-gray-200', 'text-gray-700');});
            
            btn.classList.remove('bg-gray-200');
            btn.classList.add('bg-primary', 'text-white');

            applyFilter();
        });
    });

    function applyFilter() {

        const search = searchInput.value.toLowerCase();

        tutorCards.forEach(card => {

            const name = card.dataset.name;

            let matchFilter = true;

            if(currentFilter === 'tps') {
                matchFilter = card.dataset.tps === 'true';
            }

            if(currentFilter === 'literasi') {
                matchFilter = card.dataset.literasi === 'true';
            }

            if(currentFilter === 'numerasi') {
                matchFilter = card.dataset.numerasi === 'true';
            }

            const matchSearch = name.includes(search);

            card.style.display =
                (matchSearch && matchFilter)
                ? 'block'
                : 'none';
        });
    }

});
</script>