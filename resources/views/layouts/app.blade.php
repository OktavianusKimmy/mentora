<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentora</title>

    <script src="https://meet.jit.si/external_api.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-background h-full overflow-hidden">
<div class="flex h-screen">

    @include('partials.sidebar')

    <div class="flex-1 flex flex-col h-full">
        <main class="flex-1 overflow-y-auto p-6 md:p-10">
            @yield('content')
        </main>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const wrapper = document.getElementById('profileWrapper');
        const button = document.getElementById('profileButton');
        const dropdown = document.getElementById('dropdownMenu');

        if (wrapper && button && dropdown) {
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!wrapper.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }
    });
</script>

@stack('scripts')
</body>
</html>