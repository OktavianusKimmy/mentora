<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>Mentora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background h-full">

<div class="flex h-full">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-lg hidden md:flex flex-col p-6">
        <h1 class="text-2xl font-bold text-primary mb-10">Mentora</h1>

        <nav class="flex flex-col gap-4">
            <a href="#" class="bg-primary/10 text-primary px-4 py-3 rounded-lg font-semibold">
                Dashboard
            </a>
            <a href="#" class="px-4 py-3 rounded-lg hover:bg-gray-100">
                Study Room
            </a>
            <a href="#" class="px-4 py-3 rounded-lg hover:bg-gray-100">
                Tutor
            </a>
            <a href="#" class="px-4 py-3 rounded-lg hover:bg-gray-100">
                Forum
            </a>
        </nav>

        <div class="mt-auto">
            <button class="w-full bg-primary text-white py-3 rounded-xl">
                Login / Register
            </button>
        </div>
    </aside>

    <!-- RIGHT AREA -->
    <div class="flex-1 flex flex-col h-full">
        <section class="sticky top-0 z-50 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-3">
                <div class="flex flex-col md:flex-row items-center gap-2 md:gap-4">
                    <p class="text-lg md:text-xl font-bold tracking-wide">
                        🎯 SNBT 2026
                    </p>
                    <p class="text-sm md:text-base opacity-80">
                        dimulai dalam
                    </p>
                </div>

                <div class="flex items-center gap-3 text-center">

                    <div class="bg-white text-primary px-3 py-2 rounded-lg w-16">
                        <p id="days" class="text-xl font-bold">00</p>
                        <span class="text-xs text-gray-500">Hari</span>
                    </div>

                    <div class="bg-white text-primary px-3 py-2 rounded-lg w-16">
                        <p id="hours" class="text-xl font-bold">00</p>
                        <span class="text-xs text-gray-500">Jam</span>
                    </div>

                    <div class="bg-white text-primary px-3 py-2 rounded-lg w-16">
                        <p id="minutes" class="text-xl font-bold">00</p>
                        <span class="text-xs text-gray-500">Menit</span>
                    </div>

                    <div class="bg-white text-primary px-3 py-2 rounded-lg w-16">
                        <p id="seconds" class="text-xl font-bold transition-all duration-200"></p>
                        <span class="text-xs text-gray-500">Detik</span>
                    </div>

                </div>

                <button class="bg-yellow-400 text-black text-sm font-semibold px-4 py-2 rounded-lg">
                    Mulai belajar
                </button>
            </div>
        </section>

        <main class="flex-1 overflow-hidden p-6 md:p-10">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>