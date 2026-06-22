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

            <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition tutor-card"
                data-name="{{ strtolower($tutor['name']) }}"
                data-subject="{{ strtolower($tutor['subject']) }}">

                <!-- HEADER -->
                <div class="flex items-center gap-3 mb-3">

                    <div class="w-12 h-12 bg-primary text-white flex items-center justify-center rounded-full font-bold">
                        {{ strtoupper(substr($tutor['name'], 0, 1)) }}
                    </div>

                    <div>
                        <h2 class="font-semibold">
                            {{ $tutor['name'] }}
                        </h2>
                        <p class="text-xs text-gray-400">
                            Tutor {{ $tutor['subject'] }}
                        </p>
                    </div>

                </div>

                <!-- RATING -->
                <p class="text-yellow-500 text-sm mb-2">
                    ⭐ {{ $tutor['rating'] }} (120 review)
                </p>

                <!-- EMAIL -->
                <p class="text-sm text-gray-500 mb-3">
                    {{ $tutor['email'] }}
                </p>

                <!-- DESC -->
                <p class="text-xs text-gray-400 mb-4">
                    Fokus membantu siswa lolos SNBT dengan strategi efektif
                </p>
                
                <div class="flex gap-2">
                    <!-- BUTTON -->
                    <a href="{{ route('tutor.show', $tutor['name']) }}"
                    class="w-full block text-center border border-primary text-primary font-semibold py-2 rounded-xl hover:opacity-90">
                    Lihat Profil
                    </a>
                
                    <a href="{{ route('tutor.show', $tutor['name']) }}"
                    class="w-full block text-center bg-primary text-white font-semibold py-2 rounded-xl hover:opacity-90">
                    Book Sekarang
                    </a>
                </div>

            </div>

        @empty

            <!-- EMPTY STATE -->
            <div class="col-span-3 text-center text-gray-500 mt-10">
                Belum ada tutor 😢
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
            const subject = card.dataset.subject;

            const matchSearch = name.includes(search);
            const matchFilter = currentFilter === 'all' || subject === currentFilter;

            if (matchSearch && matchFilter) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

});
</script>