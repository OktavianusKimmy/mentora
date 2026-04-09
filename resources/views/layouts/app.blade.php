<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>Mentora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background h-full overflow-hidden">

<div class="flex h-screen">

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

        @auth
            <a href="{{ route('profile.edit') }}" 
            class="mt-auto flex items-center gap-3 bg-gray-100 p-3 rounded-xl hover:bg-gray-200 transition">

                <!-- PROFILE PICTURE -->
                <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <!-- NAME -->
                <div class="flex flex-col text-left">
                    <span class="font-semibold text-sm">
                        {{ auth()->user()->name }}
                    </span>
                    <span class="text-xs text-gray-500">
                        Lihat Profile →
                    </span>
                </div>
            </a>
        @endauth

        @guest
            <div class="mt-auto">
                <a href="{{ route('auth.redirect') }}" 
                class="block w-full text-center bg-primary text-white py-3 rounded-xl">
                    Login / Register
                </a>
            </div>
        @endguest
    </aside>

    <!-- RIGHT AREA -->
    <div class="flex-1 flex flex-col h-full">

        <main class="flex-1 overflow-y-auto p-6 md:p-10">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>