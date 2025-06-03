<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rehvi Pood</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">Rehvi Pood</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="relative group">
                            <button class="text-gray-600 hover:text-gray-900 font-semibold focus:outline-none">
                                {{ Auth::user()->name }}
                            </button>
                            <div class="absolute right-0 mt-2 w-32 bg-white border rounded shadow-lg hidden group-hover:block">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logi välja</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Logi sisse</a>
                        <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-900">Registreeru</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-white shadow-lg mt-8">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500">&copy; {{ date('Y') }} Rehvi Pood. Kõik õigused kaitstud.</p>
        </div>
    </footer>
</body>
</html> 