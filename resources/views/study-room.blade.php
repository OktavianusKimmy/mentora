@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
    @keyframes shake-subtle {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        75% { transform: translateX(4px); }
    }
    .shake-subtle { animation: shake-subtle 0.2s ease-in-out 2; }
</style>

<div id="studyRoomContainer" 
     class="max-w-7xl mx-auto px-4 sm:px-6 min-h-screen pb-10" 
     x-data="{ 
        openCreateModal: false, 
        activeTab: '{{ session('active_tab') ?? 'solo' }}' 
     }">
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-blue-600 mb-2">Mentora • Study Room</p>
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">Study Room</h1>
        </div>
        
        <div class="inline-flex bg-gray-100 p-1 rounded-2xl shadow-inner">
            <button @click="activeTab = 'solo'" 
                    :class="activeTab === 'solo' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200">
                Solo Mode
            </button>
            <button @click="activeTab = 'group'" 
                    :class="activeTab === 'group' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200">
                Group Study
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-bold flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div id="sectionSolo" x-show="activeTab === 'solo'">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 bg-white rounded-[32px] border border-gray-100 shadow-sm p-6 md:p-8">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-8">
                    <div class="flex flex-wrap gap-3">
                        <button id="modePomodoro" class="mode-btn px-5 py-3 rounded-2xl text-sm font-bold transition-all duration-200 border border-transparent">
                            Pomodoro 25/5
                        </button>
                        <button id="modeDeep50" class="mode-btn px-5 py-3 rounded-2xl text-sm font-bold transition-all duration-200 border border-transparent">
                            Deep 50/10
                        </button>
                    </div>
                    <button id="focusModeBtn" class="px-4 py-3 rounded-2xl bg-gray-100 text-gray-700 text-sm font-bold hover:bg-gray-200 transition-all duration-200">
                        Focus Mode
                    </button>
                </div>

                <div id="timerCard" 
                    class="rounded-[32px] bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-500 p-8 md:p-10 text-white shadow-lg transition-all duration-300 cursor-pointer active:scale-[0.99] relative overflow-hidden"
                    onclick="handleAreaClick(event)">
                    <div class="flex items-start justify-between gap-4 mb-6">
                        <div>
                            <p id="sessionTypeLabel" class="text-blue-100 text-sm font-semibold uppercase tracking-wide">Focus Session</p>
                            <h2 id="sessionTitle" class="mt-2 text-2xl md:text-3xl font-extrabold">Study 25 Menit</h2>
                        </div>
                        <div class="text-right">
                            <p class="text-blue-100 text-xs font-medium uppercase tracking-widest">Progress</p>
                            <p id="stepCounter" class="text-2xl font-extrabold">Session 1 / 4</p>
                        </div>
                    </div>

                    <div class="flex justify-center py-6">
                        <div class="relative w-[280px] h-[280px] md:w-[340px] md:h-[340px]">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 160 160">
                                <circle cx="80" cy="80" r="68" stroke="rgba(255,255,255,0.18)" stroke-width="10" fill="none" />
                                <circle id="progressCircle" cx="80" cy="80" r="68" stroke="#FACC15" stroke-width="10" fill="none" stroke-linecap="round" stroke-dasharray="427" stroke-dashoffset="427" />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                <div id="timerDisplay" class="text-6xl md:text-7xl font-extrabold leading-none tracking-tight">25:00</div>
                                <p id="statusText" class="mt-4 text-sm text-blue-100">Pilih mode lalu tekan Start.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                        <button id="startBtn" class="px-6 py-3 rounded-2xl bg-yellow-300 text-gray-900 font-extrabold hover:bg-yellow-200 transition shadow-sm active:scale-[0.98]">Start</button>
                        <button id="pauseBtn" class="px-6 py-3 rounded-2xl bg-white/15 text-white font-semibold hover:bg-white/20 transition active:scale-[0.98]">Pause</button>
                        <button id="resetBtn" class="px-6 py-3 rounded-2xl bg-white/15 text-white font-semibold hover:bg-white/20 transition active:scale-[0.98]">Reset</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="rounded-2xl bg-gray-50 p-5 border border-gray-100">
                        <p class="text-sm text-gray-500">Mode aktif</p>
                        <p id="activeModeText" class="mt-2 text-lg font-bold text-gray-900">Pomodoro 25/5</p>
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-5 border border-gray-100">
                        <p class="text-sm text-gray-500">Step berikutnya</p>
                        <p id="nextBreakText" class="mt-2 text-lg font-bold text-gray-900">Break 5 Menit</p>
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-5 border border-gray-100">
                        <p class="text-sm text-gray-500">State</p>
                        <p id="stateText" class="mt-2 text-lg font-bold text-gray-900">Ready</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-[28px] border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500 font-medium">Today Minutes</p>
                    <h3 class="mt-3 text-4xl font-extrabold text-gray-900"><span id="todayMinutes">{{ $todayMinutes ?? 0 }}</span></h3>
                    <p class="mt-2 text-sm text-gray-500">Total menit fokus hari ini.</p>
                </div>
                <div class="bg-white rounded-[28px] border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-gray-500 font-medium">Sessions Today</p>
                    <h3 class="mt-3 text-4xl font-extrabold text-gray-900"><span id="todaySessions">{{ $todaySessions ?? 0 }}</span></h3>
                    <p class="mt-2 text-sm text-gray-500">Sesi fokus yang diselesaikan.</p>
                </div>
            </div>
        </div>
    </div>

    <div id="sectionGroup" x-show="activeTab === 'group'">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Daftar Group Study</h3>
                <p class="text-gray-500 text-sm">Pilih ruangan belajar bareng mentee lain.</p>
            </div>
            <button @click="openCreateModal = true" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-200 active:scale-95 transition">
                + Buat Room Baru
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($studyGroups as $group)
                <div class="relative group bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[32px] p-6 shadow-lg hover:shadow-blue-200/50 transition-all duration-300 text-white overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all"></div>

                    <div class="flex justify-between items-start mb-6 relative z-10">
                        <div class="flex flex-col gap-2">
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-md text-white text-[10px] font-bold uppercase rounded-lg tracking-wider w-fit border border-white/20">
                                {{ $group->subject }}
                            </span>
                            <div class="flex items-center gap-1.5 bg-green-400/20 backdrop-blur-md px-2.5 py-1 rounded-lg w-fit border border-green-400/30">
                                <div class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></div>
                                <span class="text-[10px] font-bold text-green-400">AKTIF</span>
                            </div>
                        </div>

                        @if(Auth::user()->role === 'admin' || Auth::id() === $group->created_by)
                        <form action="{{ route('study.group.destroy', $group->id) }}" method="POST" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="p-2.5 bg-white/10 text-white/80 rounded-xl hover:bg-red-500 hover:text-white transition-all duration-200 border border-white/10 backdrop-blur-sm"
                                onclick="return confirm('Yakin ingin menghapus room ini?')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>

                    <div class="relative z-10">
                        <h4 class="text-2xl font-extrabold text-white mb-1">{{ $group->name }}</h4>
                        {{-- <p class="text-[11px] text-blue-100/70 mb-4 italic font-medium">Dibuat oleh: {{ $group->creator->name ?? 'User' }}</p> --}}
                        <p class="text-sm text-blue-50/80 mb-8 line-clamp-2 leading-relaxed">Gabung untuk sesi belajar fokus bareng melalui video call Jitsi.</p>
                        
                        <a href="https://meet.jit.si/{{ $group->slug }}#config.prejoinPageEnabled=false" 
                        target="_blank" 
                        class="block w-full py-3.5 rounded-2xl bg-white text-blue-600 text-center font-extrabold hover:bg-yellow-300 hover:text-gray-900 transition-all shadow-md active:scale-[0.98]">
                        Join Group
                        </a>
                    </div>
                </div>
                @endforeach
        </div>
    </div>

    <div x-show="openCreateModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-cloak>
        <div @click.away="openCreateModal = false" class="bg-white w-full max-w-md rounded-[32px] p-8 shadow-2xl">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Buat Study Room Baru</h3>
            <p class="text-sm text-gray-500 mb-6">Ruangan ini akan muncul di daftar publik.</p>

            <form action="{{ route('study.group.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Room</label>
                        <input type="text" name="name" placeholder="Misal: Ambis UTBK Malam" required
                            class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 py-3 px-4">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Mata Pelajaran</label>
                        <input type="text" name="subject" placeholder="Misal: Pengetahuan Kuantitatif" required
                            class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 py-3 px-4">
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" @click="openCreateModal = false" class="flex-1 py-3 font-bold text-gray-500 hover:bg-gray-50 rounded-2xl transition">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition">
                        Buat Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="focusOverlay" 
    class="fixed inset-0 z-[60] hidden bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 text-white overflow-hidden cursor-pointer"
    onclick="handleOverlayAreaClick(event)">
    <div class="w-full h-full flex flex-col">
        <div class="flex items-center justify-between px-6 md:px-10 py-6">
            <div>
                <p id="focusOverlayType" class="text-blue-100 text-sm font-semibold uppercase tracking-wide">Focus Session</p>
                <h2 id="focusOverlayTitle" class="mt-2 text-2xl md:text-3xl font-extrabold">Study 25 Menit</h2>
                <p id="focusOverlayStepCounter" class="mt-1 text-blue-200 text-sm font-medium">Session 1 / 4</p>
            </div>
            <button id="exitFocusModeBtn" class="px-4 py-3 rounded-2xl bg-white/15 text-white text-sm font-bold hover:bg-white/20 transition">
                Exit Focus Mode
            </button>
    </div>

        <div class="flex-1 flex flex-col items-center justify-center px-6">
            <div class="relative w-[280px] h-[280px] md:w-[380px] md:h-[380px]">
                <svg class="w-full h-full -rotate-90" viewBox="0 0 160 160">
                    <circle cx="80" cy="80" r="68" stroke="rgba(255,255,255,0.18)" stroke-width="10" fill="none" />
                    <circle id="focusOverlayProgressCircle" cx="80" cy="80" r="68" stroke="#FACC15" stroke-width="10" fill="none" stroke-linecap="round" stroke-dasharray="427" stroke-dashoffset="427" />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                    <div id="focusOverlayTimerDisplay" class="text-7xl md:text-8xl font-extrabold leading-none tracking-tight">25:00</div>
                    <p id="focusOverlayStatusText" class="mt-4 text-sm md:text-base text-blue-100">Ready</p>
                </div>
            </div>

            <div class="mt-10 flex flex-wrap justify-center gap-3">
                <button id="focusOverlayStartBtn" class="px-6 py-3 rounded-2xl bg-yellow-300 text-gray-900 font-extrabold hover:bg-yellow-200 transition active:scale-[0.98]">Start</button>
                <button id="focusOverlayPauseBtn" class="px-6 py-3 rounded-2xl bg-white/15 text-white font-semibold hover:bg-white/20 transition active:scale-[0.98]">Pause</button>
                <button id="focusOverlayResetBtn" class="px-6 py-3 rounded-2xl bg-white/15 text-white font-semibold hover:bg-white/20 transition active:scale-[0.98]">Reset</button>
            </div>
            <p class="mt-8 text-blue-200/60 text-xs uppercase tracking-widest font-bold">Next: <span id="focusOverlayNextBreakText" class="text-white">Break 5 Menit</span></p>
        </div>
    </div>
    <div id="jitsiFrame" class="flex-1 bg-gray-900"></div>
</div>

<audio id="alarmSound" preload="auto">
    <source src="{{ asset('sound/bell.mp3') }}" type="audio/mpeg">    
</audio>

<div id="sessionModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-[70] px-4 backdrop-blur-sm">
    <div class="w-full max-w-md bg-white rounded-[32px] shadow-2xl p-7 transform transition-all">
        <h3 id="modalTitle" class="text-2xl font-extrabold text-gray-900">Sesi selesai</h3>
        <p id="modalDescription" class="mt-3 text-gray-500 leading-relaxed text-lg">Lanjut ke step berikutnya?</p>
        <div class="mt-8 space-y-3">
            <button id="modalPrimaryBtn" class="w-full px-5 py-4 rounded-2xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-200">Lanjut</button>
            <button id="modalSecondaryBtn" class="w-full px-5 py-4 rounded-2xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        const tabSolo = document.getElementById('tabSolo');
        const tabGroup = document.getElementById('tabGroup');
        const sectionSolo = document.getElementById('sectionSolo');
        const sectionGroup = document.getElementById('sectionGroup');

        tabSolo.addEventListener('click', () => {
            sectionSolo.classList.remove('hidden');
            sectionGroup.classList.add('hidden');
            tabSolo.className = "px-6 py-2.5 rounded-xl text-sm font-bold bg-white text-blue-600 shadow-sm";
            tabGroup.className = "px-6 py-2.5 rounded-xl text-sm font-bold text-gray-500 hover:text-gray-700";
        });

        tabGroup.addEventListener('click', () => {
            sectionSolo.classList.add('hidden');
            sectionGroup.classList.remove('hidden');
            tabGroup.className = "px-6 py-2.5 rounded-xl text-sm font-bold bg-white text-blue-600 shadow-sm";
            tabSolo.className = "px-6 py-2.5 rounded-xl text-sm font-bold text-gray-500 hover:text-gray-700";
        });
    </script>
    @vite('resources/js/study-room.js')
@endpush