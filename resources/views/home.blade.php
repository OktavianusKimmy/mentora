@extends('layouts.app')

@section('content')

<div class="flex gap-6 h-full">

    <!-- FORUM FEED -->
    <div class="flex-1 overflow-y-auto no-scrollbar pr-2 flex flex-col gap-4">

        <div>
            <h1 class="text-4xl font-bold mb-2">Forum Belajar SNBT</h1>
            <p class="text-textgray">Lihat pertanyaan siswa lain 👀</p>
        </div>

        <!-- SEARCH -->
        <div class="h-12 bg-white rounded-xl shadow-sm flex items-center px-4 text-gray-400">
            Cari soal...
        </div>

        <!-- QUESTION CARDS -->
        <div class="bg-white p-3 rounded-2xl shadow-sm">
            <p class="font-semibold mb-2">
                Soal TPS logika ini gimana cara ngerjainnya?
            </p>
            <p class="text-sm text-textgray">12 jawaban • 10 menit lalu</p>
            <button class="mt-3 text-primary font-semibold">
                Login untuk lihat jawaban →
            </button>
        </div>

        <div class="bg-white p-3 rounded-2xl shadow-sm">
            <p class="font-semibold mb-2">
                Cara cepat ngerjain numerasi perbandingan gimana ya?
            </p>
            <p class="text-sm text-textgray">8 jawaban • 1 jam lalu</p>
            <button class="mt-3 text-primary font-semibold">
                Login untuk lihat jawaban →
            </button>
        </div>

        <div class="bg-white p-3 rounded-2xl shadow-sm">
            <p class="font-semibold mb-2">
                Ada tips biar ga kehabisan waktu pas SNBT?
            </p>
            <p class="text-sm text-textgray">20 jawaban • 3 jam lalu</p>
            <button class="mt-3 text-primary font-semibold">
                Login untuk lihat jawaban →
            </button>
        </div>

    </div>


    <!-- RIGHT STATS -->
    <div class="w-64 flex flex-col gap-6 sticky top-24 h-fit self-start">

        <div class="bg-white p-3 rounded-2xl shadow-sm">
            <p class="font-semibold mb-3">Waktu Belajar Mingguan</p>

            <div class="flex items-end justify-between gap-2 h-24 w-full">
                @php
                    $maxValue = max(array_column($weekly, 'total')) ?: 1;
                @endphp

                <div class="flex items-end gap-2">

                @foreach($weekly as $day => $data)
                    @php
                        $height = $data['total'] > 0 ? ($data['total'] / $maxValue) * 50 : 5;
                    @endphp

                    <div class="relative group flex flex-col items-center w-6">

                        <!-- BAR -->
                        <div 
                            class="bg-primary w-full rounded transition-all duration-500"
                            style="height: {{ $height }}px;">
                        </div>

                        <!-- LABEL -->
                        <span class="text-[10px] mt-2 text-gray-400">
                            {{ \Carbon\Carbon::parse($day)->format('D') }}
                        </span>

                        <!-- TOOLTIP -->
                        <div class="absolute bottom-full mb-2 hidden group-hover:block bg-white shadow-lg rounded-lg p-3 text-xs w-36 z-10">

                            <p class="font-semibold mb-1">
                                {{ \Carbon\Carbon::parse($day)->format('d M') }}
                            </p>

                            <p>Total: {{ round($data['total'],1) }} jam</p>

                            <div class="mt-1 text-gray-500">
                                TPS: {{ round($data['TPS'],1) }}j <br>
                                Numerasi: {{ round($data['Numerasi'],1) }}j <br>
                                Literasi: {{ round($data['Literasi'],1) }}j
                            </div>

                        </div>

                    </div>

                @endforeach

                </div>

            </div>
        </div>

        <div class="bg-white p-3 rounded-2xl shadow-sm">
            <p class="font-semibold mb-4">Fokus Belajar</p>

            <!-- TPS -->
            <div class="mb-3">
                <div class="flex justify-between text-sm mb-1">
                    <span>TPS</span>
                    <span>{{ $tps }} jam</span>
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
                    <span>{{ $numerasi }} jam</span>
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
                    <span>{{ $literasi }} jam</span>
                </div>
                <div class="w-full bg-gray-200 h-2 rounded-full">
                    <div class="bg-primary h-2 rounded-full"
                        style="width: {{ ($literasi / $max) * 100 }}%"></div>
                </div>
            </div>
        </div>

        <div class="bg-primary text-white p-6 rounded-2xl text-center">
            <p class="text-lg">🔥 Study Streak</p>
            <p class="text-4xl font-bold">6 Hari</p>
        </div>

    </div>

</div>

@endsection

<script>
    const targetDate = new Date("May 5, 2026 07:00:00").getTime();

    const timer = setInterval(() => {
        const now = new Date().getTime();
        const distance = targetDate - now;

        const days = Math.floor(distance / (1000*60*60*24));
        const hours = Math.floor((distance % (1000*60*60*24))/(1000*60*60));
        const minutes = Math.floor((distance % (1000*60*60))/(1000*60));
        const seconds = Math.floor((distance % (1000*60))/1000);

        document.getElementById("countdown").innerHTML =
            days + "h " + hours + "j " + minutes + "m " + seconds + "d";
    }, 1000);
</script>