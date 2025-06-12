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
                    Автосалон лучших машин
                </span>
            </h1>
            <h2 class="text-3xl md:text-5xl font-bold mb-8 animate__animated animate__fadeIn animate__delay-1s">
                <span class="gradient-text bg-gradient-to-r from-purple-400 via-purple-200 to-purple-400 bg-clip-text text-transparent">
                    Мы ждем вас в Иркутске!
                </span>
            </h2>
            
            <div class="flex flex-col sm:flex-row gap-4 animate__animated animate__fadeInUp animate__delay-2s">
                <a href="{{route('catalog')}}" class="px-8 py-4 bg-purple-600 rounded-full text-lg font-semibold hover:bg-purple-700 transition-all transform hover:scale-105 shadow-lg shadow-purple-500/20">
                    Каталог автомобилей
                </a>
                <a href="#branches" class="px-8 py-4 bg-transparent border-2 border-purple-400 rounded-full text-lg font-semibold hover:bg-purple-400/20 transition-all transform hover:scale-105">
                    Наши филиалы
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
                    <h3 class="text-2xl font-semibold mb-6 text-center">Выберите филиал:</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($branches->take(6) as $branch)
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

                <div class="text-center mt-12">
                    <a href="{{route('branches.index')}}" class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-purple-400 rounded-full text-lg font-semibold hover:bg-purple-400/20 transition-all transform hover:scale-105">
                        Все филиалы
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

                <div id="cars-section" class="hidden animate__animated animate__fadeIn">
                    <button id="back-to-branches" class="back-button mb-6 px-6 py-3 bg-purple-600 text-white rounded-lg flex items-center hover:bg-purple-700 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Вернуться к списку филиалов
                    </button>
                    <h3 class="text-2xl font-semibold mb-6 text-center">Автомобили в выбранном филиале</h3>
                    <div id="cars-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Здесь будут отображаться автомобили -->
                    </div>
                </div>
            </div>
        </section>

        <!-- Популярные автомобили -->
        <section class="mb-16">
            <div class="text-center mb-12">
                <span class="text-purple-500 font-semibold">Популярные предложения</span>
                <h2 class="text-4xl font-bold mt-2">Лучшие автомобили</h2>
                <div class="w-20 h-1 bg-purple-600 mx-auto mt-4 rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($popularCars as $car)
                <div class="car-item bg-[#3C3C3C] rounded-xl overflow-hidden transition-all duration-300 group">
                    @if($car->images->where('is_main', true)->first())
                        <div class="overflow-hidden h-60">
                            <img src="{{ asset('storage/' . $car->images->where('is_main', true)->first()->path) }}" 
                                 alt="{{ $car->equipment->generation->carModel->brand->name }} {{ $car->equipment->generation->carModel->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                    @else
                        <div class="w-full h-60 bg-gray-800 flex items-center justify-center">
                            <img src="{{ asset('images/car-icon.jpg') }}" alt="No image" class="w-20 h-20 opacity-50">
                        </div>
                    @endif
                    <div class="p-6">
                        <h3 class="text-xl font-semibold group-hover:text-purple-400 transition-colors">
                            {{ $car->equipment->generation->carModel->brand->name }} 
                            {{ $car->equipment->generation->carModel->name }}
                        </h3>
                        <div class="mt-4 space-y-2">
                            <p class="text-gray-300 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Год: {{ $car->equipment->generation->year_from }}
                            </p>
                            <p class="text-gray-300 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Пробег: {{ number_format($car->mileage, 0, ',', ' ') }} км
                            </p>
                        </div>
                        <p class="text-2xl font-bold mt-4 text-purple-400">
                            {{ number_format($car->price, 0, ',', ' ') }} ₽
                        </p>
                        <a href="{{ route('cars.show', $car->id) }}" 
                           class="block mt-6 px-6 py-3 bg-purple-600 text-white text-center rounded-lg hover:bg-purple-700 transition-all group-hover:shadow-lg group-hover:shadow-purple-500/30">
                            Подробнее
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{route('catalog')}}" class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-purple-400 rounded-full text-lg font-semibold hover:bg-purple-400/20 transition-all transform hover:scale-105">
                    Смотреть весь каталог
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </section>

        <!-- Галерея -->
        <section class="mb-16">
            <div class="text-center mb-12">
                <span class="text-purple-500 font-semibold">Галерея</span>
                <h2 class="text-4xl font-bold mt-2">Наши автомобили</h2>
                <div class="w-20 h-1 bg-purple-600 mx-auto mt-4 rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($randomCars as $index => $car)
                    <div class="@if($index == 0) col-span-2 row-span-2 @elseif($index == 3) row-span-2 @endif bg-gray-800 rounded-xl overflow-hidden group">
                        @if($car->images->where('is_main', true)->first())
                            <div class="h-full w-full overflow-hidden">
                                <img src="{{ asset('storage/' . $car->images->where('is_main', true)->first()->path) }}" 
                                     alt="Car image"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <img src="{{ asset('images/car-icon.jpg') }}" alt="No image" class="w-16 h-16 opacity-50">
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

    </main>

   
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Инициализация карты
        ymaps.ready(initMap);
        
        let map;
        let placemarks = [];
        let selectedBranchId = null;
        
        function initMap() {
            map = new ymaps.Map('map', {
                center: [52.289588, 104.280606], // Координаты Иркутска
                zoom: 12,
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
                    balloonContent: `<b>${name}</b><br>${address}`
                }, {
                    preset: 'islands#redDotIcon',
                    iconColor: '#8B5CF6'
                });
                
                placemark.branchId = branchId;
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
                
                // Добавляем обработчик клика на метку
                placemark.events.add('click', function() {
                    // Удаляем все метки кроме текущей
                    placemarks.forEach(pm => {
                        if (pm.branchId !== branchId) {
                            map.geoObjects.remove(pm);
                        } else {
                            pm.options.set('preset', 'islands#greenDotIcon');
                        }
                    });
                    
                    // Показать автомобили этого филиала
                    loadCarsForBranch(branchId);
                    
                    // Переключить видимость секций
                    document.getElementById('branches-section').classList.add('hidden');
                    document.getElementById('cars-section').classList.remove('hidden');
                });
            });
        }
        
        // Обработка клика по филиалу
        document.querySelectorAll('.branch-item').forEach(item => {
            item.addEventListener('click', function() {
                const branchId = this.getAttribute('data-branch-id');
                selectedBranchId = branchId;
                
                // Найти метку по branchId и выделить ее
                placemarks.forEach(placemark => {
                    if (placemark.branchId === branchId) {
                        placemark.options.set('preset', 'islands#greenDotIcon');
                        map.setCenter(placemark.geometry.getCoordinates(), 15);
                        
                        // Удалить все другие метки
                        placemarks.forEach(pm => {
                            if (pm.branchId !== branchId) {
                                map.geoObjects.remove(pm);
                            }
                        });
                    }
                });
                
                // Показать автомобили этого филиала
                loadCarsForBranch(branchId);
                
                // Переключить видимость секций
                document.getElementById('branches-section').classList.add('hidden');
                document.getElementById('cars-section').classList.remove('hidden');
            });
        });
        
        // Кнопка "Назад"
        document.getElementById('back-to-branches').addEventListener('click', function() {
            // Восстановить все метки
            placemarks.forEach(placemark => {
                placemark.options.set('preset', 'islands#redDotIcon');
                map.geoObjects.add(placemark);
            });
            
            // Центрировать карту по всем меткам
            if (placemarks.length > 0) {
                map.setBounds(map.geoObjects.getBounds(), {
                    checkZoomRange: true
                });
            }
            
            // Переключить видимость секций
            document.getElementById('branches-section').classList.remove('hidden');
            document.getElementById('cars-section').classList.add('hidden');
        });
        
        // Загрузка автомобилей филиала с анимацией
        function loadCarsForBranch(branchId) {
            fetch(`/branches/${branchId}/cars`)
                .then(response => response.json())
                .then(cars => {
                    const container = document.getElementById('cars-container');
                    container.innerHTML = '';
                    
                    if (cars.length === 0) {
                        container.innerHTML = `
                            <div class="col-span-3 text-center py-12">
                                <div class="inline-block p-6 bg-[#3C3C3C] rounded-xl">
                                    <svg class="w-16 h-16 mx-auto text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-xl mt-4">В этом филиале нет автомобилей в наличии</p>
                                </div>
                            </div>
                        `;
                        return;
                    }
                    
                    cars.forEach((car, index) => {
                        const carElement = document.createElement('div');
                        carElement.className = 'car-item bg-[#3C3C3C] rounded-xl overflow-hidden transition-all duration-300 group animate__animated animate__fadeInUp';
                        carElement.style.animationDelay = `${index * 0.1}s`;
                        carElement.innerHTML = `
                            <div class="overflow-hidden h-60">
                                ${car.main_image ? 
                                    `<img src="/storage/${car.main_image.path}" alt="${car.brand} ${car.model}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">` : 
                                    `<div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                        <img src="/images/car-icon.jpg" alt="No image" class="w-20 h-20 opacity-50">
                                    </div>`}
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold group-hover:text-purple-400 transition-colors">
                                    ${car.brand} ${car.model}
                                </h3>
                                <div class="mt-4 space-y-2">
                                    <p class="text-gray-300 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Год: ${car.year}
                                    </p>
                                    <p class="text-gray-300 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Пробег: ${new Intl.NumberFormat('ru-RU').format(car.mileage)} км
                                    </p>
                                </div>
                                <p class="text-2xl font-bold mt-4 text-purple-400">
                                    ${new Intl.NumberFormat('ru-RU').format(car.price)} ₽
                                </p>
                                <a href="/cars/${car.id}" 
                                   class="block mt-6 px-6 py-3 bg-purple-600 text-white text-center rounded-lg hover:bg-purple-700 transition-all group-hover:shadow-lg group-hover:shadow-purple-500/30">
                                    Подробнее
                                </a>
                            </div>
                        `;
                        container.appendChild(carElement);
                    });
                })
                .catch(error => {
                    console.error('Error loading cars:', error);
                    document.getElementById('cars-container').innerHTML = `
                        <div class="col-span-3 text-center py-12">
                            <div class="inline-block p-6 bg-[#3C3C3C] rounded-xl">
                                <svg class="w-16 h-16 mx-auto text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <p class="text-xl mt-4 text-red-400">Произошла ошибка при загрузке автомобилей</p>
                            </div>
                        </div>
                    `;
                });
        }
        
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
        
        // Mobile menu toggle
        const menuToggle = document.getElementById('menu-toggle');
        const menu = document.getElementById('menu');
        
        menuToggle.addEventListener('click', function() {
            menu.classList.toggle('hidden');
            menu.classList.toggle('flex');
            menu.classList.toggle('absolute');
            menu.classList.toggle('top-16');
            menu.classList.toggle('right-6');
            menu.classList.toggle('bg-[#3C3C3C]');
            menu.classList.toggle('p-4');
            menu.classList.toggle('rounded-lg');
            menu.classList.toggle('flex-col');
            menu.classList.toggle('space-y-4');
            menu.classList.toggle('space-x-0');
            menu.classList.toggle('animate__animated');
            menu.classList.toggle('animate__fadeInDown');
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
                    
                    // Close mobile menu if open
                    if (!menu.classList.contains('hidden')) {
                        menuToggle.click();
                    }
                }
            });
        });
    });
</script>

@endsection