<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <meta name="user-id" content="{{ auth()->id() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://api-maps.yandex.ru/2.1/?apikey=fa8f0187-f7ba-4f94-aa91-c6a61473cec3&lang=ru_RU" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.min.js"></script>
    <title>Forward Auto</title>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    },
                    colors: {
                        dark: {
                            100: '#f5f5f7',
                            200: '#e5e5e7',
                            300: '#d4d4d8',
                            400: '#a1a1aa',
                            500: '#71717a',
                            600: '#52525b',
                            700: '#3f3f46',
                            800: '#27272a',
                            900: '#18181b',
                        },
                        light: {
                            900: '#f8fafc',
                            800: '#f1f5f9',
                            700: '#e2e8f0',
                            600: '#cbd5e1',
                            500: '#94a3b8',
                            400: '#64748b',
                            300: '#475569',
                            200: '#334155',
                            100: '#1e293b',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #2D2D2D;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #8B5CF6;
            border-radius: 4px;
        }
        
        .light ::-webkit-scrollbar-track {
            background: #e2e8f0;
        }
        
        .light ::-webkit-scrollbar-thumb {
            background: #7C3AED;
        }
        
        /* Theme switcher */
        .theme-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }
        
        .theme-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #4a4a4a;
            transition: .4s;
            border-radius: 34px;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .slider {
            background-color: #8B5CF6;
        }
        
        input:checked + .slider:before {
            transform: translateX(30px);
        }
        
        /* Light theme styles */
        .light {
            color: #1e293b;
        }
        
        .light body {
            background-color: #f8fafc;
        }
        
        .light .bg-\[\#1C1B21\] {
            background-color: #ffffff;
        }
        
        .light .bg-\[\#2A2A2A\],
        .light .bg-\[\#3C3C3C\] {
            background-color: #f1f5f9;
        }
        
        .light .bg-\[\#2D2D2D\] {
            background-color: #e2e8f0;
        }
        
        .light .text-white {
            color: #1e293b;
        }
        
        .light .text-gray-300,
        .light .text-gray-400 {
            color: #64748b;
        }
        
        .light .border-gray-700 {
            border-color: #cbd5e1;
        }
        
        .light .bg-gray-800 {
            background-color: #e2e8f0;
        }
        
        .light .bg-gray-700 {
            background-color: #cbd5e1;
        }
        
        .light .bg-gray-600 {
            background-color: #94a3b8;
        }
        
        .light .text-black {
            color: #1e293b;
        }
        
        .light .notification-dropdown,
        .light .notification-item.unread {
            background-color: #ffffff;
        }
        
        .light .notification-item {
            border-color: #e2e8f0;
        }
        
        .light .notification-item:hover {
            background-color: #f1f5f9;
        }
        
        /* Smooth theme transition */
        body {
            transition: background-color 0.5s ease, color 0.5s ease;
        }
        
        /* Fixed header */
        .main-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            background-color: rgba(28, 27, 33, 0.9);
            backdrop-filter: blur(10px);
            transition: background-color 0.5s ease;
        }
        
        .light .main-header {
            background-color: rgba(255, 255, 255, 0.9);
        }
        
        /* Content offset for fixed header */
        main {
            padding-top: 80px;
        }
        
        /* Notification styles */
        .notification-dropdown {
            max-height: 70vh;
            overflow-y: auto;
            width: 350px;
            right: 0;
            left: auto;
        }
        
        .notification-item.unread {
            background-color: rgba(139, 92, 246, 0.1);
        }
        
        .notification-badge {
            font-size: 0.7rem;
            min-width: 18px;
            height: 18px;
            line-height: 1;
        }
        
        /* Filter sidebar */
        .filter-sidebar {
            position: sticky;
            top: 100px;
            height: calc(100vh - 120px);
            overflow-y: auto;
        }
        
        /* Car item animation */
        .car-item {
            transition: all 0.3s ease;
        }
        
        .car-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }


        .light .bg-white {
        background-color: var(--tw-bg-opacity);
        background-color: rgba(249, 250, 251, var(--tw-bg-opacity));
    }
    
    .light .bg-gradient-to-r.from-blue-50.to-indigo-50 {
        background-image: linear-gradient(to right, #f0f9ff, #eef2ff);
    }
    
    .light .text-gray-800 {
        color: #1f2937;
    }
    
    .light .text-gray-600 {
        color: #4b5563;
    }
    
    .light .border-gray-200 {
        border-color: #e5e7eb;
    }
    
    .light .bg-gray-100 {
        background-color: #f3f4f6;
    }
    
    .light .bg-blue-100 {
        background-color: #dbeafe;
    }
    
    .light .bg-green-100 {
        background-color: #d1fae5;
    }
    
    .light .bg-yellow-100 {
        background-color: #fef3c7;
    }
    
    .light .bg-red-100 {
        background-color: #fee2e2;
    }
    
    .light .bg-purple-100 {
        background-color: #ede9fe;
    }
    
    .light .divide-gray-200 {
        border-color: #e5e7eb;
    }
    
    .light .hover\:bg-gray-50:hover {
        background-color: #f9fafb;
    }
    </style>
</head>
<body class="bg-[#1C1B21] text-white transition-colors duration-300">

<div class="min-h-screen">

    <header class="main-header">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{route('welcome')}}" class="flex items-center space-x-2">
                <span class="text-2xl font-bold">
                    Форвард
                </span>
                <img src="{{asset("images/logo.png")}}" alt="" class="w-8 h-8 animate-pulse-slow">
                <span class="text-2xl font-bold text-purple-600">
                    Авто
                </span>
            </a>
            
            <div class="flex items-center space-x-6">
                <nav class="hidden md:flex md:space-x-6 items-center">
                    <a class="px-4 py-2 rounded-lg hover:bg-purple-600/20 transition-all" href="{{route('catalog')}}">Все авто</a>
                    <a class="px-4 py-2 rounded-lg hover:bg-purple-600/20 transition-all" href="{{route('dashboard')}}">Личный кабинет</a>
                    @auth
                    @if(auth()->user()->isManager())

                        <a class="px-4 py-2 rounded-lg hover:bg-purple-600/20 transition-all" href="{{route('manager.bookings.index')}}">Менеджер панель</a>
                    @endif

                    @if(auth()->user()->isAdmin())
                    <a class="px-4 py-2 rounded-lg hover:bg-purple-600/20 transition-all" href="{{route('admin.car-structure.index')}}">Админ панель</a>
                    @endif

                    @if(auth()->user()->isSuperAdmin())
                    <a class="px-4 py-2 rounded-lg hover:bg-purple-600/20 transition-all" href="{{route('admin.admins.index')}}">Админ панель</a>
                    @endif
                    @endauth
                </nav>
                
                @auth
                <div class="notification-icon relative">
                    <a href="#" id="notification-bell" class="text-gray-300 hover:text-white relative">
                        <i class="fas fa-bell text-xl"></i>
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                            <span class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white rounded-full flex items-center justify-center">
                                {{ auth()->user()->unreadNotifications()->count() }}
                            </span>
                        @endif
                    </a>
                    
                    <div class="notification-dropdown hidden absolute mt-2 bg-[#2A2A2A] rounded-md shadow-lg z-50 border border-gray-700">
                        <div class="p-3 bg-[#2A2A2A]  ">
                            <div class="flex justify-between items-center">
                                <h4 class="font-semibold">Уведомления</h4>
                                <a href="#" id="mark-all-read" class="text-xs text-purple-400 hover:text-purple-300">Отметить все как прочитанные</a>
                            </div>
                        </div>
                        <div class="notification-list max-h-96 overflow-y-auto">
                            @foreach(auth()->user()->notifications()->latest()->take(10)->get() as $notification)
                                <div class="notification-item px-3 py-3 border-b bg-[#2A2A2A] hover:bg-gray-700 cursor-pointer flex justify-between items-start {{ $notification->read_at ? '' : 'unread' }}" data-id="{{ $notification->id }}">
                                    <div>
                                        <p class="text-sm">
                                            {{ \Illuminate\Support\Str::limit($notification->message, 60, '...') }}
                                        </p>
                                        <small class="text-xs text-gray-400 block mt-1">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    @if($notification->url)
                                        <a href="{{ $notification->url }}" class="text-xs text-purple-400 hover:text-purple-300 ml-2">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                            
                            @if(auth()->user()->notifications()->count() > 10)
                                <div class="p-3 text-center text-sm text-gray-400">
                                    Показаны последние 10 уведомлений
                                </div>
                            @endif
                            
                            @if(auth()->user()->notifications()->count() === 0)
                                <div class="p-6 text-center text-gray-400">
                                    <i class="far fa-bell-slash text-2xl mb-2"></i>
                                    <p>Нет уведомлений</p>
                                </div>
                            @endif
                        </div>
                        <div class="p-3 border-t border-gray-700 text-center">
                            <a href="{{ route('notifications.index') }}" class="text-sm text-purple-400 hover:text-purple-300">Все уведомления</a>
                        </div>
                    </div>
                </div>
                @endauth
                
                <div class="hidden md:block">
                    <button id="theme-toggle" class="text-gray-300 hover:text-white transition-colors">
                        <!-- Здесь будет только одна иконка - динамически меняется -->
                        <i id="desktop-theme-icon" class="fas fa-moon text-xl"></i>
                    </button>
                </div>
                
                <div class="md:hidden">
                    <button id="menu-toggle" class="text-white focus:outline-none">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden fixed inset-0 z-40 bg-black bg-opacity-75 md:hidden">
        <div class="fixed inset-y-0 right-0 w-3/4 bg-[#2D2D2D] p-4 overflow-y-auto">
            <div class="flex justify-end mb-6">
                <button id="menu-close" class="text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <nav class="flex flex-col space-y-4">
                <a class="px-4 py-3 rounded-lg hover:bg-purple-600/20 transition-all text-lg" href="{{route('catalog')}}">Все авто</a>
                <a class="px-4 py-3 rounded-lg hover:bg-purple-600/20 transition-all text-lg" href="{{route('dashboard')}}">Личный кабинет</a>
                <a class="px-4 py-3 rounded-lg hover:bg-purple-600/20 transition-all text-lg" href="{{route('branches.index')}}">Филиалы</a>
                
                @auth
                <div class="mt-6 pt-6 border-t border-gray-700">
                    <a href="{{ route('notifications.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-purple-600/20 transition-all text-lg">
                        <span class="relative mr-3">
                            <i class="fas fa-bell"></i>
                            @if(auth()->user()->unreadNotifications()->count() > 0)
                                <span class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white rounded-full flex items-center justify-center">
                                    {{ auth()->user()->unreadNotifications()->count() }}
                                </span>
                            @endif
                        </span>
                        Уведомления
                    </a>
                </div>
                @endauth
            </nav>
            
            <div class="mt-8 pt-6 border-t border-gray-700">
                <button id="mobile-theme-toggle" class="flex items-center justify-between w-full px-4 py-3 text-lg">
                    <span>Тема</span>
                    <span>
                        <!-- Здесь будет только одна иконка - динамически меняется -->
                        <i id="mobile-theme-icon" class="fas fa-moon "></i>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="text-current">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#2D2D2D] py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="text-xl font-bold">Форвард</span>
                        <img src="{{asset("images/logo.png")}}" alt="" class="w-6 h-6">
                        <span class="text-xl font-bold text-purple-600">Авто</span>
                    </div>
                    <p class="text-gray-400">Лучший автосалон в Иркутске. Мы предлагаем качественные автомобили по доступным ценам.</p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Меню</h4>
                    <ul class="space-y-2">
                        <li><a href="{{route('catalog')}}" class="text-gray-400 hover:text-purple-400 transition-colors">Все авто</a></li>
                        <li><a href="{{route('dashboard')}}" class="text-gray-400 hover:text-purple-400 transition-colors">Личный кабинет</a></li>
                        <li><a href="{{route('branches.index')}}" class="text-gray-400 hover:text-purple-400 transition-colors">Филиалы</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Контакты</h4>
                    <ul class="space-y-2">
                        <li class="text-gray-400">г. Иркутск, ул. Ленина, 1</li>
                        <li class="text-gray-400">+7 (3952) 12-34-56</li>
                        <li class="text-gray-400">info@forward-auto.ru</li>
                    </ul>
                </div>
                
                <div>
                    
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>© {{ date('Y') }} Forward Auto. Все права защищены.</p>
            </div>
        </div>
    </footer>
</div>

<script>
     document.addEventListener('DOMContentLoaded', function() {
        // Theme switcher
        const themeToggle = document.getElementById('theme-toggle');
        const mobileThemeToggle = document.getElementById('mobile-theme-toggle');
        const html = document.documentElement;
        
        // Иконки темы (теперь у нас по одной иконке для каждого переключателя)
        const desktopThemeIcon = document.getElementById('desktop-theme-icon');
        const mobileThemeIcon = document.getElementById('mobile-theme-icon');
        
        // Функция для обновления иконок темы
        function updateThemeIcons() {
            const isLight = html.classList.contains('light');
            const iconClass = isLight ? 'fa-sun' : 'fa-moon';
            
            // Обновляем иконку в десктопном переключателе
            if (desktopThemeIcon) {
                desktopThemeIcon.className = `fas ${iconClass} text-xl`;
            }
            
            // Обновляем иконку в мобильном переключателе
            if (mobileThemeIcon) {
                mobileThemeIcon.className = `fas ${iconClass} text-xl`;
            }
        }
        
        // Проверяем сохраненную тему при загрузке
        if (localStorage.getItem('theme') === 'light') {
            html.classList.remove('dark');
            html.classList.add('light');
        } else {
            // Убедимся, что темная тема установлена
            html.classList.remove('light');
            html.classList.add('dark');
        }
        
        // Инициализируем иконки
        updateThemeIcons();
        
        // Функция переключения темы
        function toggleTheme() {
            const isLight = html.classList.contains('light');
            
            if (isLight) {
                // Переключаем на темную тему
                html.classList.remove('light');
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                // Переключаем на светлую тему
                html.classList.remove('dark');
                html.classList.add('light');
                localStorage.setItem('theme', 'light');
            }
            
            // Обновляем иконки после смены темы
            updateThemeIcons();
        }
        
        // Назначаем обработчики
        if (themeToggle) themeToggle.addEventListener('click', toggleTheme);
        if (mobileThemeToggle) mobileThemeToggle.addEventListener('click', toggleTheme);
        
        // Mobile menu toggle
        const menuToggle = document.getElementById('menu-toggle');
        const menuClose = document.getElementById('menu-close');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (menuToggle && menuClose && mobileMenu) {
            // Переключение меню по кнопке меню
            menuToggle.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');
                document.body.style.overflow = mobileMenu.classList.contains('hidden') ? '' : 'hidden';
            });

            // Закрытие по кнопке "крестик"
            menuClose.addEventListener('click', function () {
                mobileMenu.classList.add('hidden');
                document.body.style.overflow = '';
            });

            // Закрытие по клику вне меню
            mobileMenu.addEventListener('click', function (e) {
                if (e.target === mobileMenu) {
                    mobileMenu.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
        }
        
        // Notification system
        const bell = document.getElementById('notification-bell');
        const dropdown = document.querySelector('.notification-dropdown');
        
        if (bell && dropdown) {
            // Функция для обновления уведомлений
            async function updateNotifications() {
                try {
                    const response = await fetch('/notifications');
                    const data = await response.json();

                    // Обновляем бейдж
                    updateBadge(data.unread_count);

                    // Обновляем список уведомлений
                    renderNotifications(data.notifications);
                } catch (error) {
                    console.error('Ошибка при обновлении уведомлений:', error);
                }
            }
        
            // Функция для обновления бейджа
            function updateBadge(count) {
                let badge = document.querySelector('.notification-badge');
                
                if (count > 0) {
                    if (!badge) {
                        badge = document.createElement('span');
                        badge.className = 'notification-badge absolute -top-1 -right-1 bg-red-500 text-white rounded-full flex items-center justify-center';
                        bell.appendChild(badge);
                    }
                    badge.textContent = count;
                } else if (badge) {
                    badge.remove();
                }
            }
        
            // Функция для отметки как прочитанного
            async function markAsRead(id) {
                try {
                    const response = await fetch(`/notifications/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        updateBadge(data.unread_count);
                        document.querySelector(`.notification-item[data-id="${id}"]`).classList.remove('unread');
                    }
                } catch (error) {
                    console.error('Ошибка при отметке уведомления:', error);
                }
            }
        
            // Открытие/закрытие выпадающего списка
            bell.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropdown.classList.toggle('hidden');
            });
        
            // Закрытие при клике вне области
            document.addEventListener('click', function(e) {
                if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        
            // Обработка кликов на уведомления
            dropdown.addEventListener('click', function(e) {
                const item = e.target.closest('.notification-item');
                if (item && item.classList.contains('unread')) {
                    markAsRead(item.dataset.id);
                    item.classList.remove('unread');
                }
            });
        
            // Отметить все как прочитанные
            document.getElementById('mark-all-read')?.addEventListener('click', async function(e) {
                e.preventDefault();
                try {
                    const response = await fetch('/notifications/read-all', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        updateBadge(0);
                        document.querySelectorAll('.notification-item').forEach(item => {
                            item.classList.remove('unread');
                        });
                    }
                } catch (error) {
                    console.error('Ошибка при отметке всех уведомлений:', error);
                }
            });
        
            // Обновляем каждые 30 секунд
            setInterval(updateNotifications, 1000);
        
            // Первоначальная загрузка
            updateNotifications();
        }
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    // Close mobile menu if open
                    if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                }
            });
        });
    });

    function renderNotifications(notifications) {
    const list = document.querySelector('.notification-list');
    if (!list) return;
    list.innerHTML = '';

    if (notifications.length === 0) {
        list.innerHTML = `
            <div class="p-6 text-center text-gray-400">
                <i class="far fa-bell-slash text-2xl mb-2"></i>
                <p>Нет уведомлений</p>
            </div>
        `;
        return;
    }

    notifications.forEach(notification => {
        const limitedMessage = notification.message.substring(0, 60) + (notification.message.length > 60 ? '...' : '');

        const item = document.createElement('div');
        item.className = `notification-item px-3 py-3 border-b bg-[#2A2A2A] hover:bg-gray-700 cursor-pointer flex justify-between items-start ${notification.read_at ? '' : 'unread'}`;
        item.setAttribute('data-id', notification.id);
        item.innerHTML = `
            <div>
                <p class="text-sm">${limitedMessage}</p>
                <small class="text-xs text-gray-400 block mt-1">${timeAgo(notification.created_at)}</small>
            </div>
            ${notification.url ? `<a href="${notification.url}" class="text-xs text-purple-400 hover:text-purple-300 ml-2"><i class="fas fa-external-link-alt"></i></a>` : ''}
        `;
        list.appendChild(item);
    });

    // Показываем "показаны последние 10"
    if (notifications.length >= 10) {
        const info = document.createElement('div');
        info.className = 'p-3 text-center text-sm text-gray-400';
        info.textContent = 'Показаны последние 10 уведомлений';
        list.appendChild(info);
    }
}

// Функция форматирования даты (простой пример)
function timeAgo(dateString) {
    const date = new Date(dateString);
    const seconds = Math.floor((new Date() - date) / 1000);
    const intervals = [
        { limit: 3600, divisor: 60, suffix: 'минуту назад' },
        { limit: 86400, divisor: 3600, suffix: 'час назад' },
        { limit: 604800, divisor: 86400, suffix: 'день назад' },
        { limit: 2629743, divisor: 604800, suffix: 'неделю назад' },
    ];
    const interval = intervals.find(i => seconds < i.limit);
    if (interval) {
        const count = Math.floor(seconds / interval.divisor);
        return `${count} ${interval.suffix}`;
    }
    return 'более месяца назад';
}
</script>

@yield('scripts')
</body>
</html>