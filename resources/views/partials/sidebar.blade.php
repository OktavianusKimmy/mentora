<aside id="sidebar"
    class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg 
           flex flex-col p-6 z-[70]
           transform transition-transform duration-300">
    <div class="flex items-center gap-20 mb-6 pt-2">
        <h1 class="text-2xl font-bold text-primary">Mentora</h1>

        <button id="toggleSidebar"
            class="p-2 transition">

            <svg id="sidebarIcon" xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 text-accent transition-transform duration-300"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                    d="M15 19l-7-7 7-7" />
            </svg>

        </button>
    </div>

    <nav class="flex flex-col gap-4">

        <a href="{{ url('/') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg 
            {{ request()->is('/') ? 'bg-primary/10 text-primary font-semibold' : 'hover:bg-gray-100' }}">

            <span>🏠</span>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <a href="{{ route('study-room') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg 
            {{ request()->is('study-room') ? 'bg-primary/10 text-primary font-semibold' : 'hover:bg-gray-100' }}">

            <span>📚</span>
            <span class="sidebar-text">Study Room</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100">
            <span>👨‍🏫</span>
            <span class="sidebar-text">Tutor</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100">
            <span>💬</span>
            <span class="sidebar-text">Forum</span>
        </a>

    </nav>

    @auth
    <div id="profileWrapper" class="mt-auto relative">
        <div id="profileButton"
            class="flex items-center gap-3 bg-gray-100 p-3 rounded-xl hover:bg-gray-200 transition cursor-pointer">

            <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div class="flex flex-col text-left">
                <span class="font-semibold text-sm">
                    {{ auth()->user()->name }}
                </span>
                <span class="text-xs text-gray-500">
                    Account
                </span>
            </div>
        </div>

        <div id="dropdownMenu"
            class="hidden absolute bottom-16 left-0 w-full bg-white rounded-xl shadow-lg p-2 space-y-1">

            <a href="{{ route('profile.edit') }}"
                class="block px-4 py-2 rounded-lg hover:bg-gray-100">
                Profile
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left px-4 py-2 rounded-lg hover:bg-red-50 text-red-500">
                    Logout
                </button>
            </form>
        </div>
    </div>
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