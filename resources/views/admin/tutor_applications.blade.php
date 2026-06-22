@extends('layouts.app')

@section('content')

<div class="max-w-6xl ml-2">
<!-- HEADER -->
    <div class="mb-8">
        <p class="text-sm font-semibold text-blue-600 mb-2">
            Mentora • Admin Panel
        </p>

        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">
            Tutor Applications
        </h1>

        <p class="text-gray-500 mt-2">
            Review dan approve calon tutor Mentora.
        </p>
    </div>

    <!-- TABS -->
    <div class="flex gap-3 mb-6">
        <button
            class="tab-btn px-4 py-2 bg-primary text-white rounded-xl font-semibold"
            data-tab="pending">
            Pending ({{ $pendingApps->count() }})
        </button>

        <button
            class="tab-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-xl font-semibold"
            data-tab="approved">
            Approved ({{ $approvedApps->count() }})
        </button>
    </div>

    <!-- PENDING TAB -->
    <div id="pending-tab">

        @forelse($pendingApps as $app)

            <div class="bg-white rounded-2xl shadow p-6 mb-5 border border-gray-100">

                <div class="flex justify-between items-start mb-4">

                    <div class="flex items-center gap-4">

                        <div class="w-14 h-14 rounded-full bg-primary text-white flex items-center justify-center font-bold text-lg">
                            {{ strtoupper(substr($app->name, 0, 1)) }}
                        </div>

                        <div>
                            <h2 class="font-bold text-lg">
                                {{ $app->name }}
                            </h2>

                            <p class="text-gray-500 text-sm">
                                {{ $app->email }}
                            </p>

                            <span class="inline-block mt-1 px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">
                                {{ ucfirst($app->role) }}
                            </span>
                        </div>

                    </div>

                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium">
                        Pending
                    </span>

                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-1">
                        Alasan Menjadi Tutor
                    </p>

                    <div class="bg-gray-50 rounded-xl p-4 text-gray-700">
                        {{ $app->reason }}
                    </div>
                </div>

                <div class="flex items-center justify-between">

                    <a href="{{ asset('storage/'.$app->utbk_file) }}"
                        target="_blank"
                        class="text-primary font-semibold hover:underline">
                        📄 Lihat Hasil UTBK
                    </a>

                    <form action="{{ route('admin.approve', $app->id) }}" method="POST">
                        @csrf

                        <button
                            class="bg-primary text-white px-5 py-2 rounded-xl font-semibold hover:opacity-90 transition">
                            Approve Tutor
                        </button>
                    </form>

                </div>

            </div>

        @empty

            <div class="bg-white rounded-2xl p-10 text-center shadow">
                <div class="text-5xl mb-3">📭</div>

                <h3 class="font-bold text-lg mb-2">
                    Tidak Ada Pengajuan Pending
                </h3>

                <p class="text-gray-500">
                    Semua pengajuan sudah diproses.
                </p>
            </div>

        @endforelse

    </div>

    <!-- APPROVED TAB -->
    <div id="approved-tab" class="hidden">

        @forelse($approvedApps as $app)

            <div class="bg-white rounded-2xl shadow p-6 mb-5 border border-gray-100">

                <div class="flex justify-between items-start">

                    <div class="flex items-center gap-4">

                        <div class="w-14 h-14 rounded-full bg-primary text-white flex items-center justify-center font-bold text-lg">
                            {{ strtoupper(substr($app->name, 0, 1)) }}
                        </div>

                        <div>
                            <h2 class="font-bold text-lg">
                                {{ $app->name }}
                            </h2>

                            <p class="text-gray-500 text-sm">
                                {{ $app->email }}
                            </p>

                            <span class="inline-block mt-1 px-2 py-1 bg-primary text-white rounded-full text-xs">
                                Tutor
                            </span>
                        </div>

                    </div>

                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                        Approved
                    </span>

                </div>

            </div>

        @empty

            <div class="bg-white rounded-2xl p-10 text-center shadow">
                <div class="text-5xl mb-3">🎓</div>

                <h3 class="font-bold text-lg mb-2">
                    Belum Ada Tutor Approved
                </h3>

                <p class="text-gray-500">
                    Belum ada tutor yang disetujui.
                </p>
            </div>

        @endforelse

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const buttons = document.querySelectorAll('.tab-btn');

    const pendingTab = document.getElementById('pending-tab');
    const approvedTab = document.getElementById('approved-tab');

    buttons.forEach(btn => {

        btn.addEventListener('click', () => {

            buttons.forEach(b => {
                b.classList.remove('bg-primary', 'text-white');
                b.classList.add('bg-gray-200', 'text-gray-700');
            });

            btn.classList.remove('bg-gray-200', 'text-gray-700');
            btn.classList.add('bg-primary', 'text-white');

            if (btn.dataset.tab === 'pending') {
                pendingTab.classList.remove('hidden');
                approvedTab.classList.add('hidden');
            } else {
                approvedTab.classList.remove('hidden');
                pendingTab.classList.add('hidden');
            }

        });

    });

});
</script>

@endsection
