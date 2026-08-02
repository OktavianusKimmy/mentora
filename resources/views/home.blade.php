@extends('layouts.app')

@section('content')

<section
    id="topBanner"
    class="
    static
    lg:fixed
    lg:top-0
    lg:left-0
    lg:right-0
    z-50
    lg:bg-gradient-to-r
    lg:from-blue-600
    lg:to-blue-700
    lg:text-white
    lg:shadow-md
    transition-all
    duration-300
    ml-0
    lg:ml-20">
    <div class="max-w-7xl mx-auto lg:px-6 lg:py-3">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-2xl shadow-md p-4 sm:p-5 lg:bg-none lg:shadow-none lg:rounded-none lg:p-0 flex flex-wrap lg:flex-nowrap items-center justify-between gap-x-4 gap-y-4 lg:gap-y-0"> 
            <button id="sidebarOpenBtn"
                type="button"
                class="order-1 lg:hidden w-10 h-10 flex items-center justify-center bg-white rounded-xl shadow text-gray-600 hover:bg-gray-100 transition shrink-0">
                <svg class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="2">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>
                </svg>
            </button>

            <div class="order-2 lg:order-3">
                @guest
                    <a href="{{ route('auth.redirect') }}" class="bg-accent text-black hover:bg-accent-hover text-sm font-semibold px-4 py-2 rounded-lg whitespace-nowrap inline-block">
                        {{ __('Mulai belajar') }}
                    </a>
                @endguest

                @auth
                    <a href="{{ route('study-room') }}" class="bg-accent text-black hover:bg-accent-hover text-sm font-semibold px-4 py-2 rounded-lg whitespace-nowrap inline-block">
                        {{ __('Mulai belajar') }}
                    </a>
                @endauth
            </div>

            <div class="order-3 lg:order-1 w-full lg:w-auto flex flex-col lg:flex-row lg:items-center gap-0.5 lg:gap-4">
                <p class="text-lg lg:text-xl font-bold tracking-wide whitespace-nowrap">
                    🎯 SNBT 2026
                </p>
                <p class="text-xs lg:text-base opacity-80 whitespace-nowrap">
                    {{ __('dimulai dalam') }}
                </p>
            </div>

            <div class="order-4 lg:order-2 w-full lg:w-auto flex items-center gap-2 lg:gap-3 text-center">

                <div class="flex-1 lg:flex-none bg-white text-primary px-2 py-1.5 lg:px-3 lg:py-2 rounded-lg lg:w-16">
                    <p id="days" class="text-lg lg:text-xl font-bold">00</p>
                    <span class="text-[10px] lg:text-xs text-gray-500">{{ __('Hari') }}</span>
                </div>

                <div class="flex-1 lg:flex-none bg-white text-primary px-2 py-1.5 lg:px-3 lg:py-2 rounded-lg lg:w-16">
                    <p id="hours" class="text-lg lg:text-xl font-bold">00</p>
                    <span class="text-[10px] lg:text-xs text-gray-500">{{ __('Jam') }}</span>
                </div>

                <div class="flex-1 lg:flex-none bg-white text-primary px-2 py-1.5 lg:px-3 lg:py-2 rounded-lg lg:w-16">
                    <p id="minutes" class="text-lg lg:text-xl font-bold">00</p>
                    <span class="text-[10px] lg:text-xs text-gray-500">{{ __('Menit') }}</span>
                </div>

                <div class="flex-1 lg:flex-none bg-white text-primary px-2 py-1.5 lg:px-3 lg:py-2 rounded-lg lg:w-16">
                    <p id="seconds" class="text-lg lg:text-xl font-bold transition-all duration-200"></p>
                    <span class="text-[10px] lg:text-xs text-gray-500">{{ __('Detik') }}</span>
                </div>

            </div>

        </div>
    </div>
</section>

<div id="mainContent" class="mt-0 lg:mt-16 flex flex-col flex-1 min-h-0">

    <div class="px-6 md:px-10">

        <div class="flex flex-col lg:flex-row gap-6 flex-1 min-h-0">

                <!-- FORUM FEED -->
                <div id="forumFeed" class="order-2 lg:order-1 flex-1 lg:overflow-y-auto no-scrollbar pr-0 sm:pr-2 flex flex-col gap-4 lg:min-h-0 mt-6">

                    <div>
                        <h1 class="text-3xl sm:text-4xl font-bold mb-2">{{ __('Forum Belajar SNBT') }}</h1>
                        <p class="text-textgray">{{ __('Lihat pertanyaan siswa lain 👀') }}</p>
                    </div>

                    <!-- SEARCH -->
                    <a href="{{ route('forum') }}#searchInput"
                        class="w-full px-4 py-2 bg-white text-gray-400 cursor-text border border-gray-200 rounded-2xl shadow-sm">
                        {{ __('Cari diskusi...') }}
                    </a>

                    <!-- QUESTION CARDS -->
                    @foreach($questions as $thread)

                    <a href="{{ route('forum.show', $thread) }}"
                    class="block bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition">

                        <h2 class="font-semibold text-lg">
                            {{ $thread->title }}
                        </h2>
                        <p class="mb-3 text-gray-600 line-clamp-2">

                            {{ Str::limit(strip_tags($thread->body),150) }}

                        </p>
                        
                        @guest
                        <div class="blur-sm pointer-events-none select-none">
                        @endguest


                        @if($thread->images->isNotEmpty())
                            @php
                                $images = $thread->images;
                            @endphp

                            <div class="grid gap-2 mb-5 {{ $images->count() == 1 ? 'grid-cols-1' : 'grid-cols-2' }}">

                                @foreach($images->take(2) as $index => $image)

                                    <div class="relative h-48 sm:h-64 overflow-hidden rounded-2xl">

                                        <img src="{{ $image->url }}"
                                            class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition"
                                            @click="lightboxImage='{{ $image->url }}'">

                                        @if($images->count() > 2 && $index == 1)
                                            <div class="absolute inset-0 bg-black/45 flex items-center justify-center text-white text-3xl font-bold">
                                                +{{ $images->count() - 2 }}
                                            </div>
                                        @endif

                                    </div>

                                @endforeach

                            </div>
                        @endif

                        <div class="flex items-center gap-2 mt-2 text-sm text-gray-500">

                            <span>{{ $thread->replies_count }} {{ __('jawaban') }}</span>

                            <span>•</span>

                            <span>{{ $thread->created_at->diffForHumans() }}</span>

                        </div>

                        @guest
                        </div>
                        @endguest

                    </a>

                    @endforeach

                </div>


                <!-- RIGHT STATS -->
                <div x-data="{ statView: 'mingguan' }" id="statsSidebar" class="order-1 lg:order-2 w-full lg:w-64 flex flex-col gap-6 static lg:sticky lg:top-[80px] h-fit self-start mt-6 lg:mt-6">

                    <!-- Mobile only: pick which stat to see. Desktop shows all three, so this stays hidden there. -->
                    <div class="lg:hidden flex gap-2 text-xs font-medium">
                        <button type="button" @click="statView = 'mingguan'"
                            :class="statView === 'mingguan' ? 'bg-primary text-white' : 'bg-white text-gray-500'"
                            class="flex-1 py-2 rounded-full shadow-sm transition">{{ __('Mingguan') }}</button>
                        <button type="button" @click="statView = 'fokus'"
                            :class="statView === 'fokus' ? 'bg-primary text-white' : 'bg-white text-gray-500'"
                            class="flex-1 py-2 rounded-full shadow-sm transition">{{ __('Fokus') }}</button>
                        <button type="button" @click="statView = 'streak'"
                            :class="statView === 'streak' ? 'bg-primary text-white' : 'bg-white text-gray-500'"
                            class="flex-1 py-2 rounded-full shadow-sm transition">{{ __('Streak') }}</button>
                    </div>

                    <div class="lg:block bg-white p-3 rounded-2xl shadow-sm" :class="statView === 'mingguan' ? '' : 'hidden'">
                        <p class="font-semibold mb-3">{{ __('Waktu Belajar Mingguan') }}</p>

                        <div class="flex items-end justify-between gap-1 sm:gap-2 h-24 w-full">
                            @php
                                $maxValue = max(array_column($weekly, 'total')) ?: 1;
                            @endphp

                            @foreach($weekly as $day => $data)
                                @php
                                    $height = $data['total'] > 0 ? ($data['total'] / $maxValue) * 50 : 5;
                                @endphp

                                <div class="relative group flex flex-col items-center flex-1">

                                    <!-- BAR -->
                                    <div 
                                        class="bg-primary w-4 sm:w-6 rounded transition-all duration-500"
                                        style="height: {{ $height }}px;">
                                    </div>

                                    <!-- LABEL -->
                                    <span class="text-[10px] mt-2 text-gray-400">
                                        @php
                                            $hariMap = app()->getLocale() === 'en'
                                                ? ['Mon' => 'Mon', 'Tue' => 'Tue', 'Wed' => 'Wed', 'Thu' => 'Thu', 'Fri' => 'Fri', 'Sat' => 'Sat', 'Sun' => 'Sun']
                                                : ['Mon' => 'Sen', 'Tue' => 'Sel', 'Wed' => 'Rab', 'Thu' => 'Kam', 'Fri' => 'Jum', 'Sat' => 'Sab', 'Sun' => 'Min'];
                                        @endphp
                                        {{ $hariMap[\Carbon\Carbon::parse($day)->format('D')] }}
                                    </span>

                                    <!-- TOOLTIP -->
                                    <div class="absolute top-full mt-2 left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block bg-white shadow-lg rounded-lg p-3 text-xs w-36 z-100">

                                        <p class="font-semibold mb-1">
                                            {{ \Carbon\Carbon::parse($day)->format('d M') }}
                                        </p>

                                        <p>{{ __('Total') }}: {{ round($data['total'],1) }} {{ __('jam') }}</p>

                                        <div class="mt-1 text-gray-500">
                                            TPS: {{ round($data['TPS'],1) }}{{ __('j') }} <br>
                                            Numerasi: {{ round($data['Numerasi'],1) }}{{ __('j') }} <br>
                                            Literasi: {{ round($data['Literasi'],1) }}{{ __('j') }}
                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>
                    </div>

                    <div class="lg:block bg-white p-3 rounded-2xl shadow-sm" :class="statView === 'fokus' ? '' : 'hidden'">
                        <p class="font-semibold mb-4">{{ __('Fokus Belajar') }}</p>

                        <!-- TPS -->
                        <div class="mb-3">
                            <div class="flex justify-between text-sm mb-1">
                                <span>TPS</span>
                                <span>{{ round($tps, 2) }} {{ __('jam') }}</span>
                            </div>
                            <div class="w-full bg-gray-200 h-2 rounded-full">
                                <div class="bg-primary h-2 rounded-full"
                                    style="width: {{ ($tps / $max) * 100 }}%"></div>
                            </div>
                        </div>

                        <!-- Numerasi -->
                        <div class="mb-3">
                            <div class="flex justify-between text-sm mb-1">
                                <span>Numerasi</span>
                                <span>{{ round($numerasi, 2) }} {{ __('jam') }}</span>
                            </div>
                            <div class="w-full bg-gray-200 h-2 rounded-full">
                                <div class="bg-primary h-2 rounded-full"
                                    style="width: {{ ($numerasi / $max) * 100 }}%"></div>
                            </div>
                        </div>

                        <!-- Literasi -->
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span>Literasi</span>
                                <span>{{ round($literasi, 2) }} {{ __('jam') }}</span>
                            </div>
                            <div class="w-full bg-gray-200 h-2 rounded-full">
                                <div class="bg-primary h-2 rounded-full"
                                    style="width: {{ ($literasi / $max) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:block bg-primary text-white p-6 rounded-2xl text-center" :class="statView === 'streak' ? '' : 'hidden'">
                        <p class="text-lg">🔥 {{ __('Streak Belajar') }}</p>
                        <p class="text-4xl font-bold">
                            {{ $streak }} {{ __('Hari') }}
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script>
        function adjustForumHeight() {
            const forumFeed = document.getElementById("forumFeed");
            const sidebar = document.getElementById("statsSidebar");
            if (!forumFeed || !sidebar) return;

            if (window.matchMedia("(min-width: 1024px)").matches) {
                forumFeed.style.maxHeight = sidebar.offsetHeight + "px";
            } else {
                forumFeed.style.maxHeight = "";
            }
        }
        document.addEventListener("DOMContentLoaded", adjustForumHeight);
        window.addEventListener("load", adjustForumHeight);
        window.addEventListener("resize", adjustForumHeight);
    </script>

@endsection