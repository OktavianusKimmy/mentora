<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>Mentora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
    const wrapper = document.getElementById('profileWrapper');
    const button = document.getElementById('profileButton');
    const dropdown = document.getElementById('dropdownMenu');

    if (wrapper && button && dropdown) {
        button.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });

        wrapper.addEventListener('mouseleave', () => {
            dropdown.classList.add('hidden');
        });
    }
</script>
@stack('scripts')
</body>
</html>