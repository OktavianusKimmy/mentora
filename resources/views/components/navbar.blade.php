<nav class="bg-white border-b border-bordercolor">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between h-16 items-center">

            <!-- Logo -->
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-primary rounded-lg"></div>
                <span class="text-xl font-bold text-primary">Mentora</span>
            </div>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center gap-8 font-medium">
                <a href="#" class="hover:text-primary">Study Room</a>
                <a href="#" class="hover:text-primary">Forum</a>
                <a href="#" class="hover:text-primary">Tutor</a>
                <a href="#" class="hover:text-primary">Tracker</a>

                <button class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-xl">
                    Login
                </button>
            </div>

            <!-- Burger Button -->
            <button id="burger" class="md:hidden">
                ☰
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden px-6 pb-4">
        <div class="flex flex-col gap-4 font-medium">
            <a href="#" class="hover:text-primary">Study Room</a>
            <a href="#" class="hover:text-primary">Forum</a>
            <a href="#" class="hover:text-primary">Tutor</a>
            <a href="#" class="hover:text-primary">Tracker</a>

            <button class="bg-primary text-white py-2 rounded-xl">
                Login
            </button>
        </div>
    </div>
</nav>