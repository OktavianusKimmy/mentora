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

    <div id="mainContent"
     class="flex-1 flex flex-col h-full ml-64 transition-all duration-300">
        <div class="flex items-center gap-4 p-4 bg-white border-b">

            <button id="floatingBurger"
                class="fixed top-[35px] left-10 z-[60] p-2 transition hidden">
                
                <svg id="sidebarIcon" xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 text-accent rotate-180 transition-transform duration-300"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                        d="M15 19l-7-7 7-7" />
                </svg>
            </button>

        </div>
        <main class="flex-1 flex flex-col min-h-0 p-6 md:p-10">
            @yield('content')
        </main>
    </div>

</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleSidebar');
    const floatingBtn = document.getElementById('floatingBurger');
    const main = document.getElementById('mainContent');
    const banner = document.getElementById('topBanner');
    const icon = document.getElementById('sidebarIcon');

    let isOpen = true;

    function toggleSidebar() {
        isOpen = !isOpen;

        if (!isOpen) {
            sidebar.classList.add('-translate-x-full');
            main.classList.remove('ml-64');
            banner.classList.remove('ml-64');

            floatingBtn.classList.remove('hidden');

            banner.classList.add('pl-16'); 

            icon.classList.add('rotate-180'); // 👉 jadi kanan
        } else {
            sidebar.classList.remove('-translate-x-full');
            main.classList.add('ml-64');
            banner.classList.add('ml-64');

            floatingBtn.classList.add('hidden');

            banner.classList.remove('pl-16'); 

            icon.classList.remove('rotate-180'); // 👉 balik kiri
        }
    }

    // klik dari dalam sidebar
    toggleBtn.addEventListener('click', toggleSidebar);

    // klik dari floating
    floatingBtn.addEventListener('click', toggleSidebar);
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