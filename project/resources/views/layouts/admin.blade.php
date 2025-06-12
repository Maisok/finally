<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админка</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <script src="https://api-maps.yandex.ru/2.1/?apikey=fa8f0187-f7ba-4f94-aa91-c6a61473cec3&lang=ru_RU " type="text/javascript"></script>
</head>
<body class="bg-gray-100 text-gray-900">
    <nav class="max-w-7xl mx-auto p-6">
        <!-- Mobile menu button -->
        <div class="flex justify-between items-center md:hidden">
            <span class="text-xl font-bold">Меню</span>
            <button id="menu-toggle" class="text-gray-900 focus:outline-none">
                <!-- Icon: Menu -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
    
        <!-- Desktop menu -->
        <div class="hidden md:flex md:space-x-6 lg:space-x-8 text-sm font-medium">
            <a class="p-2 text-black hover:underline" href="{{ route('admin.references.index') }}">Справочники</a>
            <a class="p-2 text-black hover:underline" href="{{ route('admin.car-structure.index') }}">Структура</a>
            <a class="p-2 text-black hover:underline" href="{{ route('admin.equipments.index') }}">Комплектации</a>
            <a class="p-2 text-black hover:underline" href="{{ route('admin.cars.index') }}">Авто</a>
            <a class="p-2 text-black hover:underline" href="{{ route('admin.managers.index') }}">Управление менеджерами</a>
            <a class="p-2 text-black hover:underline" href="{{ route('admin.bookings.index') }}">Управление бронированиями</a>
            <a class="p-2 text-black hover:underline" href="{{ route('dashboard') }}">Личный кабинет</a>
        </div>
    
        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden mt-4 space-y-2 text-sm font-medium flex flex-col">
            <a class="p-2 text-black hover:bg-gray-200 rounded" href="{{ route('admin.references.index') }}">Референсы</a>
            <a class="p-2 text-black hover:bg-gray-200 rounded" href="{{ route('admin.car-structure.index') }}">Структура</a>
            <a class="p-2 text-black hover:bg-gray-200 rounded" href="{{ route('admin.equipments.index') }}">Комплектации</a>
            <a class="p-2 text-black hover:bg-gray-200 rounded" href="{{ route('admin.cars.index') }}">Авто</a>
            <a class="p-2 text-black hover:bg-gray-200 rounded" href="{{ route('admin.managers.index') }}">Управление менеджерами</a>
            <a class="p-2 text-black hover:underline" href="{{ route('admin.bookings.index') }}">Управление бронированиями</a>
            <a class="p-2 text-black hover:bg-gray-200 rounded" href="{{ route('dashboard') }}">Личный кабинет</a>
        </div>
    </nav>

<div class="max-w-7xl mx-auto p-6">
    @yield('content')
</div>


<script>
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>
</body>
</html>