@extends('layouts.user')

@section('content')
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
                    }
                }
            }
        }
    </script>
    <style>
        #map {
            width: 100%;
            height: 500px;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .branch-item {
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .branch-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
        }
        
        .car-item {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .car-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.2);
        }
        
        .back-button {
            transition: all 0.3s;
        }
        
        .back-button:hover {
            transform: translateX(-5px);
        }
        
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
        .light body {
            background-color: #f5f5f7;
            color: #333;
        }
        
        .light .bg-\[\#1C1B21\] {
            background-color: #ffffff;
        }
        
        .light .bg-\[\#3C3C3C\] {
            background-color: #f8fafc;
            color: #333;
            border: 1px solid #e2e8f0;
        }
        
        .light .bg-\[\#2D2D2D\] {
            background-color: #f1f5f9;
            color: #333;
        }
        
        .light .branch-item:hover,
        .light .car-item:hover {
            background-color: #e2e8f0;
        }
        
        .light .text-gray-300 {
            color: #64748b;
        }
        
        .light .text-white {
            color: #333;
        }
        
        .light .text-black {
            color: #333;
        }
        
        /* Smooth theme transition */
        body {
            transition: background-color 0.5s ease, color 0.5s ease;
        }
        
        /* Gradient text animation */
        .gradient-text {
            background-size: 200% auto;
            animation: gradientShift 5s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% center; }
            50% { background-position: 100% center; }
            100% { background-position: 0% center; }
        }
        
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
            background: #f1f5f9;
        }
        
        .light ::-webkit-scrollbar-thumb {
            background: #7C3AED;
        }
        
        /* Header styles */
        .header-bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.8;
            transition: opacity 0.5s ease;
        }
        
        .dark .header-bg {
            content: url("{{asset('images/bgmain.png')}}");
        }
        
        .light .header-bg {
            content: url("{{asset('images/bgmain2.png')}}");
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
    </style>

    <!-- Hero Section -->
    <section class="relative overflow-hidden pt-20">
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-transparent z-0"></div>
        <img alt="Background image" class="header-bg"/>
        
        <div class="relative z-10 flex flex-col items-center justify-center min-h-[calc(100vh-80px)] text-center px-4">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 animate__animated animate__fadeInDown">
                <span class="gradient-text bg-gradient-to-r from-white via-purple-300 to-white bg-clip-text text-transparent">
                    Наши филиалы
                </span>
            </h1>
            <h2 class="text-3xl md:text-5xl font-bold mb-8 animate__animated animate__fadeIn animate__delay-1s">
                <span class="gradient-text bg-gradient-to-r from-purple-400 via-purple-200 to-purple-400 bg-clip-text text-transparent">
                    Автосалоны по всей России
                </span>
            </h2>
            
            <div class="flex flex-col sm:flex-row gap-4 animate__animated animate__fadeInUp animate__delay-2s">
                <a href="{{route('catalog')}}" class="px-8 py-4 bg-purple-600 rounded-full text-lg font-semibold hover:bg-purple-700 transition-all transform hover:scale-105 shadow-lg shadow-purple-500/20">
                    Каталог автомобилей
                </a>
                <a href="#branches" class="px-8 py-4 bg-transparent border-2 border-purple-400 rounded-full text-lg font-semibold hover:bg-purple-400/20 transition-all transform hover:scale-105">
                    Посмотреть на карте
                </a>
            </div>
            
            <div class="absolute bottom-10 animate-bounce">
                <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </div>
        </div>
    </section>

    <!-- Основной контент -->
    <main class="container mx-auto px-4 py-8">
        <!-- Яндекс Карта и филиалы -->
        <section id="branches" class="mb-16 pt-16 scroll-mt-16">
            <div class="text-center mb-12">
                <span class="text-purple-500 font-semibold">Наши филиалы</span>
                <h2 class="text-4xl font-bold mt-2">Где нас найти</h2>
                <div class="w-20 h-1 bg-purple-600 mx-auto mt-4 rounded-full"></div>
            </div>
            
            <div class="mb-8">
                <div id="map" class="mb-6"></div>
                
                <div id="branches-section">
                    <h3 class="text-2xl font-semibold mb-6 text-center">Все наши филиалы:</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($branches as $branch)
                        <div class="branch-item bg-[#3C3C3C] p-6 rounded-xl transition-all duration-300"
                             data-address="{{ $branch->address }}"
                             data-branch-id="{{ $branch->id }}">
                            <div class="flex items-start space-x-4">
                                <div class="p-3 bg-purple-600/20 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xl font-semibold">{{ $branch->name }}</h4>
                                    <p class="text-gray-300 mt-2">{{ $branch->address }}</p>
                                    <div class="mt-4 flex items-center text-purple-400">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>Автомобилей в наличии: {{ $branch->cars->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- Контакты -->
        <section class="mb-16">
            <div class="text-center mb-12">
                <span class="text-purple-500 font-semibold">Контакты</span>
                <h2 class="text-4xl font-bold mt-2">Свяжитесь с нами</h2>
                <div class="w-20 h-1 bg-purple-600 mx-auto mt-4 rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-[#3C3C3C] p-8 rounded-xl text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-600/20 rounded-full mb-4">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Телефон</h3>
                    <p class="text-gray-300">+7 (3952) 12-34-56</p>
                    <p class="text-gray-300 mt-2">Единый call-центр</p>
                </div>
                
                <div class="bg-[#3C3C3C] p-8 rounded-xl text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-600/20 rounded-full mb-4">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Email</h3>
                    <p class="text-gray-300">info@forward-auto.ru</p>
                    <p class="text-gray-300 mt-2">Для общих вопросов</p>
                </div>
                
                <div class="bg-[#3C3C3C] p-8 rounded-xl text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-600/20 rounded-full mb-4">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Режим работы</h3>
                    <p class="text-gray-300">Пн-Пт: 9:00 - 20:00</p>
                    <p class="text-gray-300 mt-2">Сб-Вс: 10:00 - 18:00</p>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Инициализация карты
            ymaps.ready(initMap);
            
            let map;
            let placemarks = [];
            
            function initMap() {
                map = new ymaps.Map('map', {
                    center: [55.751574, 37.573856], // Координаты Москвы
                    zoom: 5,
                    controls: ['zoomControl']
                });
                
                // Добавление меток для всех филиалов
                @foreach ($branches as $branch)
                addPlacemark('{{ $branch->address }}', '{{ $branch->name }}', '{{ $branch->id }}');
                @endforeach
                
                // Добавляем кастомный элемент управления
                map.controls.add(new ymaps.control.SearchControl({
                    options: {
                        float: 'right',
                        noPlacemark: true
                    }
                }));
            }
            
            function addPlacemark(address, name, branchId) {
                ymaps.geocode(address, {
                    results: 1
                }).then(function(res) {
                    let firstGeoObject = res.geoObjects.get(0);
                    let coordinates = firstGeoObject.geometry.getCoordinates();
                    
                    let placemark = new ymaps.Placemark(coordinates, {
                        hintContent: name,
                        balloonContent: `
                            <b>${name}</b><br>
                            ${address}<br>
                        `
                    }, {
                        preset: 'islands#redDotIcon',
                        iconColor: '#8B5CF6'
                    });
                    
                    map.geoObjects.add(placemark);
                    placemarks.push(placemark);
                    
                    // Центрируем карту по всем меткам
                    if (placemarks.length > 1) {
                        map.setBounds(map.geoObjects.getBounds(), {
                            checkZoomRange: true
                        });
                    } else {
                        map.setCenter(coordinates, 15);
                    }
                });
            }
            
            // Обработка клика по филиалу
            document.querySelectorAll('.branch-item').forEach(item => {
                item.addEventListener('click', function() {
                    const address = this.getAttribute('data-address');
                    
                    // Найти метку по адресу и выделить ее
                    ymaps.geocode(address, {
                        results: 1
                    }).then(function(res) {
                        let firstGeoObject = res.geoObjects.get(0);
                        let coordinates = firstGeoObject.geometry.getCoordinates();
                        
                        map.setCenter(coordinates, 15);
                    });
                });
            });
            
            // Theme switcher
            const themeToggle = document.getElementById('theme-toggle');
            const html = document.documentElement;
            
            // Check for saved user preference, if any, on load of the website
            if (localStorage.getItem('theme') === 'light') {
                html.classList.remove('dark');
                html.classList.add('light');
                themeToggle.checked = true;
            }
            
            // Toggle theme on button click
            themeToggle.addEventListener('change', function() {
                if (this.checked) {
                    html.classList.remove('dark');
                    html.classList.add('light');
                    localStorage.setItem('theme', 'light');
                } else {
                    html.classList.remove('light');
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            });
            
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
                    }
                });
            });
        });
    </script>
@endsection