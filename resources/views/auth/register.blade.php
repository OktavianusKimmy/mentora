<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Mentora</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen font-sans">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-sm border border-gray-100 my-10">

        <h1 class="text-2xl font-bold mb-2 text-center text-gray-900">
            Daftar Mentora 🚀
        </h1>
        <p class="text-sm text-gray-500 text-center mb-6">
            Buat akun untuk mulai belajar SNBT
        </p>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                    value="{{ old('name') }}" required autofocus>

                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                    value="{{ old('email') }}" required>

                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                    required>

                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                    required>
            </div>

            <button type="submit"
                class="w-full bg-primary text-white py-2.5 rounded-lg font-bold hover:opacity-90 transition shadow-md shadow-primary/20 active:scale-[0.98]">
                Daftar Sekarang
            </button>
        </form>

        <div class="my-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-400">atau daftar cepat dengan</span>
                </div>
            </div>
        </div>

        <a href="{{ route('google.login') }}" class="mb-4 w-full flex items-center justify-center gap-3 px-4 py-2.5 border border-gray-300 rounded-lg font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm active:scale-[0.98]">
            <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" class="w-5 h-5">
            Google Account
        </a>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-500 mb-3">Sudah punya akun?</p>
            <a href="{{ route('login') }}"
               class="block w-full text-center border border-primary text-primary py-2.5 rounded-lg font-bold hover:bg-primary hover:text-white transition active:scale-[0.98]">
                Login di sini
            </a>
        </div>

    </div>

</body>
</html>