@extends('layouts.app')

@section('content')
<div id="studyRoomContainer" class="max-w-7xl mx-auto px-4 sm:px-6 min-h-screen pb-10">
    <div id="normalStudyRoomContent">
        <div class="mb-8">
            <p class="text-sm font-semibold text-blue-600 mb-2">Mentora • Study Room</p>
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">Study Room</h1>
            <p class="mt-3 text-lg text-gray-500">
                Fokus dengan ritme yang jelas, ambil break yang cukup, lalu lanjut lagi.
            </p>
        </div>

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

                <div id="timerCard" class="rounded-[32px] bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-500 p-8 md:p-10 text-white shadow-lg transition-all duration-300">
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
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-[28px] p-6 text-white shadow-sm">
                    <p class="text-sm text-gray-300 font-medium">Tips</p>
                    <h3 class="mt-3 text-xl font-bold leading-snug">Target kecil per sesi membantu fokus lebih terarah.</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="focusOverlay" class="fixed inset-0 z-[60] hidden bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 text-white overflow-hidden">
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
                    <p id="focusOverlayStatusText" class="mt-4 text-sm md:text-base text-blue-100">Pilih mode lalu tekan Start.</p>
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
</div>

<audio id="alarmSound" preload="auto">
    <source src="{{ asset('sound/bell.mp3') }}" type="audio/mpeg">    
</audio>

<div id="sessionModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-[70] px-4 backdrop-blur-sm">
    <div class="w-full max-w-md bg-white rounded-[32px] shadow-2xl p-7 transform transition-all">
        <h3 id="modalTitle" class="text-2xl font-extrabold text-gray-900">Sesi selesai</h3>
        <p id="modalDescription" class="mt-3 text-gray-500 leading-relaxed text-lg">Lanjut ke step berikutnya?</p>
        <div class="mt-8 space-y-3">
            <button id="modalPrimaryBtn" class="w-full px-5 py-4 rounded-2xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">Lanjut</button>
            <button id="modalSecondaryBtn" class="w-full px-5 py-4 rounded-2xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/study-room.js')
@endpush