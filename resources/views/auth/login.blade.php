<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mentora</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow">

        <!-- TITLE -->
        <h1 class="text-2xl font-bold mb-2 text-center">
            Login Mentora 🚀
        </h1>
        <p class="text-sm text-gray-500 text-center mb-6">
            Masuk untuk lanjut belajar SNBT
        </p>

        <!-- SESSION STATUS -->
        @if (session('status'))
            <div class="mb-4 text-green-600 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <!-- FORM -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- EMAIL -->
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                    value="{{ old('email') }}" required autofocus>
                
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                    required>

                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- REMEMBER -->
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>

                <a href="{{ route('password.request') }}" class="text-primary hover:underline">
                    Lupa password?
                </a>
            </div>

            <!-- LOGIN BUTTON -->
            <button type="submit"
                class="w-full bg-primary text-white py-2 rounded-lg font-semibold hover:opacity-90 transition">
                Login
            </button>
        </form>

        <!-- DIVIDER -->
        <div class="my-6 text-center text-sm text-gray-400">
            atau
        </div>

        <!-- REGISTER BUTTON -->
        <a href="{{ route('register') }}"
        class="block w-full text-center border border-primary text-primary py-2 rounded-lg font-semibold hover:bg-primary hover:text-white transition">
            Daftar akun baru
        </a>

    </div>

</body>
</html>