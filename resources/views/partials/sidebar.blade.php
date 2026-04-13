<aside class="w-64 bg-white shadow-lg hidden md:flex flex-col p-6">
    <h1 class="text-2xl font-bold text-primary mb-10">Mentora</h1>

    <nav class="flex flex-col gap-4">
        <a href="{{ url('/') }}"
        class="px-4 py-3 rounded-lg {{ request()->is('/') ? 'bg-primary/10 text-primary font-semibold' : 'hover:bg-gray-100' }}">
            Dashboard
        </a>

        <a href="{{ route('study-room') }}"
        class="px-4 py-3 rounded-lg {{ request()->is('study-room') ? 'bg-primary/10 text-primary font-semibold' : 'hover:bg-gray-100' }}">
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