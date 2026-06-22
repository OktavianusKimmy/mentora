@extends('layouts.app')

@section('content')

<div class="max-w-6xl">

    <!-- BACK -->
    <div class="mb-6">
        <a href="{{ route('tutor') }}"
           class="text-primary font-medium hover:underline">
            ← Kembali ke daftar tutor
        </a>
    </div>

    <!-- PROFILE CARD -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- COVER -->
        <div class="h-40 bg-gradient-to-r from-primary to-blue-500"></div>

        <div class="px-8 pb-8">

            <!-- AVATAR -->
            <div class="-mt-14 mb-4">
                <div class="w-28 h-28 rounded-full bg-white p-2 shadow-lg">
                    <div class="w-full h-full rounded-full bg-primary text-white flex items-center justify-center text-4xl font-bold">
                        {{ strtoupper(substr($tutor->user->name, 0, 1)) }}
                    </div>
                </div>
            </div>

            <!-- NAME -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6">

                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        {{ $tutor->user->name }}
                    </h1>

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
                        
                    <span class="text-yellow-500 font-semibold">
                        ⭐ {{ $tutor->rating }}
                    </span>

                </div>

             </div>

                <div class="flex gap-3">

                    <button class="bg-primary text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition">
                        Booking Session
                    </button>

                    <button class="border border-primary text-primary px-6 py-3 rounded-xl font-semibold hover:bg-primary hover:text-white transition">
                        Chat Tutor
                    </button>

                </div>

            </div>

            <!-- STATS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">

                <div class="bg-gray-50 rounded-2xl p-5">
                    <p class="text-sm text-gray-500">
                        Rating
                    </p>

                    <h2 class="text-2xl font-bold text-yellow-500 mt-1">
                        ⭐ {{ $tutor->rating }}
                    </h2>
                </div>

                <div class="bg-gray-50 rounded-2xl p-5">
                    <p class="text-sm text-gray-500">
                        Students
                    </p>

                    <h2 class="text-2xl font-bold mt-1">
                        120+
                    </h2>
                </div>

                <div class="bg-gray-50 rounded-2xl p-5">
                    <p class="text-sm text-gray-500">
                        Sessions
                    </p>

                    <h2 class="text-2xl font-bold mt-1">
                        350+
                    </h2>
                </div>

            </div>

            <!-- ABOUT -->
            <div class="mt-8">

                <h2 class="text-xl font-bold mb-3">
                    Tentang Tutor
                </h2>

                <div class="bg-gray-50 rounded-2xl p-5 text-gray-700 leading-relaxed">
                    {{ $tutor['bio'] }}
                </div>

            </div>

        </div>

    </div>

</div>

@endsection