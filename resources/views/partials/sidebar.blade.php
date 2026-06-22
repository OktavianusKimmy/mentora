<aside id="sidebar"
    class="fixed top-0 left-0 h-full w-20 bg-white shadow-lg
           flex flex-col p-4 z-[70]
           transition-all duration-300 overflow-hidden">
    <div class="flex items-center px-2 mb-8 pt-4">
        <img src="{{ asset('images/logo.png') }}"
            alt="Mentora Logo"
            class="h-8 w-8 shrink-0">

        <span class="sidebar-text
            max-w-0 opacity-0 overflow-hidden
            whitespace-nowrap transition-all duration-300
            text-2xl font-bold text-primary ml-3 mt-3">
            Mentora
        </span>
    </div>

    <nav class="flex flex-col gap-4">

        <a href="{{ url('/') }}"
            class="flex items-center gap-3 px-3.5 py-3 rounded-lg 
            {{ request()->is('/') ? 'bg-primary/10 text-primary font-semibold' : 'hover:bg-gray-100' }}">

            <span>🏠</span>
            <span class="sidebar-text
                max-w-0
                opacity-0
                overflow-hidden
                whitespace-nowrap
                transition-all duration-300">
                Dashboard
            </span>
        </a>

        <a href="{{ route('study-room') }}"
            class="flex items-center gap-3 px-3.5 py-3 rounded-lg 
            {{ request()->is('study-room') ? 'bg-primary/10 text-primary font-semibold' : 'hover:bg-gray-100' }}">

            <span>📚</span>
            <span class="sidebar-text
                max-w-0
                opacity-0
                overflow-hidden
                whitespace-nowrap
                transition-all duration-300">
                Study Room
            </span>
        </a>

        <a href="{{route('tutor')}}" class="flex items-center gap-3 px-3.5 py-3 rounded-lg 
            {{ request()->is('tutor') ? 'bg-primary/10 text-primary font-semibold' : 'hover:bg-gray-100' }}">
            <span>👨‍🏫</span>
            <span class="sidebar-text
                max-w-0
                opacity-0
                overflow-hidden
                whitespace-nowrap
                transition-all duration-300">
                Tutor
            </span>
        </a>

        <a href="#" class="flex items-center gap-3 px-3.5 py-3 rounded-lg hover:bg-gray-100">
            <span>💬</span>
            <span class="sidebar-text
                max-w-0
                opacity-0
                overflow-hidden
                whitespace-nowrap
                transition-all duration-300">
                Forum
            </span>
        </a>

    </nav>

    @auth
    <div id="profileWrapper" class="mt-auto relative">
        <div id="profileButton"
            class="flex items-center bg-gray-10  p-2 pr-3 rounded-xl hover:bg-gray-200 transition cursor-pointer">

            <div class="w-10 h-10 rounded-full bg-primary text-white
                        flex items-center justify-center font-bold shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div class="profile-text
                        max-w-0 opacity-0 overflow-hidden
                        whitespace-nowrap transition-all duration-300
                        flex flex-col text-left ml-3">

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
            class="flex items-center px-2.5 py-3 rounded-xl bg-primary text-white">

                <span class="text-xl shrink-0">👤</span>

                <span class="sidebar-text
                            max-w-0 opacity-0 overflow-hidden
                            whitespace-nowrap transition-all duration-300
                            ml-3 font-semibold">
                    Login / Register
                </span>

            </a>
        </div>
    @endguest
</aside>