@extends('layouts.user')
<style>
.fullscreen {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 9999 !important;
    background-color: #111111 !important;
    margin: 0 !important;
    padding: 0 !important;
}

#modelCanvas {
    width: 100%;
    height: 100%;
    display: block;
    position: relative;
}
</style>
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumbs -->
    <div class="mb-6 text-sm text-gray-400">
        <a href="{{ route('catalog') }}" class="hover:text-purple-400">Каталог</a>
        <span class="mx-2">/</span>
        <a href="#" class="hover:text-purple-400">{{ $car->equipment->generation->carModel->brand->name }}</a>
        <span class="mx-2">/</span>
        <span class="text-white">{{ $car->equipment->generation->carModel->name }}</span>
    </div>

    <!-- Notifications -->
    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg max-w-md z-50">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-bold">{{ session('success') }}</h3>
                    @if(session('booking_info'))
                        <div class="mt-2 text-sm">
                            <p><strong>Номер брони:</strong> #{{ session('booking_info.id') }}</p>
                            <p><strong>Дата визита:</strong> {{ session('booking_info.date') }}</p>
                        </div>
                    @endif
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    &times;
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white p-4 rounded-lg shadow-lg max-w-md z-50">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-bold">Ошибка</h3>
                    <p class="mt-1">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    &times;
                </button>
            </div>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Left column - photos and 3D model -->
        <div class="lg:w-1/2">
            <!-- Toggle buttons -->
            <div class="flex space-x-2 mb-4">
                <button id="showPhotosBtn" class="px-4 py-2 bg-purple-600 text-[#ffffff] rounded-lg flex items-center">
                    <i class="fas fa-images mr-2"></i> Фотографии
                </button>
                @if($car->equipment->model_url)
                    <button id="show3dModelBtn" class="px-4 py-2 text-[#ffffff] bg-gray-700 rounded-lg flex items-center">
                        <i class="fas fa-cube mr-2"></i> 3D Модель
                    </button>
                @endif
            </div>
            
            <!-- Photo gallery -->
            <div id="photosContainer" class="rounded-xl overflow-hidden bg-[#2A2A2A]">
                <div class="relative">
                    <div id="mainImage" class="h-96 w-full flex items-center justify-center bg-gray-800 overflow-hidden">
                        <img src="{{ asset('storage/' . $car->images->first()->path) }}" 
                             alt="Фото авто" 
                             class="h-full w-full object-contain cursor-pointer"
                             id="currentImage">
                    </div>
                    @if($car->images->count() > 1)
                        <div class="flex justify-between px-4 py-2">
                            <button id="prevImage" class="text-gray-400 hover:text-white">
                                <i class="fas fa-chevron-left"></i> Назад
                            </button>
                            <div class="text-gray-400">
                                <span id="currentIndex">1</span> / {{ $car->images->count() }}
                            </div>
                            <button id="nextImage" class="text-gray-400 hover:text-white">
                                Вперед <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        <div class="flex overflow-x-auto space-x-2 px-4 pb-4">
                            @foreach($car->images as $index => $image)
                                <img src="{{ asset('storage/' . $image->path) }}" 
                                     alt="Миниатюра" 
                                     class="h-16 w-24 object-cover rounded cursor-pointer thumbnail"
                                     data-index="{{ $index }}"
                                     @if($loop->first) data-active @endif>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
            <div id="modelContainer" class="hidden rounded-xl overflow-hidden bg-[#2A2A2A] relative h-96">
                <button id="fullscreenToggle" class="absolute top-4 right-4 z-50 bg-purple-600 hover:bg-purple-700 text-white p-2 rounded-lg transition">
                    <i class="fas fa-expand"></i>
                </button>
                <canvas id="modelCanvas" class="w-full h-full"></canvas>
                <div class="absolute bottom-4 left-4 bg-[#000000] bg-opacity-50 text-[#ffffff] px-3 py-1 rounded-lg text-sm">
                    <i class="fas fa-arrows-alt mr-1"></i> Перетащите для вращения
                </div>
            </div>

            <!-- Favorite button -->
            <div class="mt-4">
                @auth
                    <button type="button" 
                            class="favorite-btn w-full px-4 py-2 rounded-lg border border-purple-600 text-purple-400 hover:bg-purple-600 hover:text-white transition flex items-center justify-center" 
                            data-car-id="{{ $car->id }}"
                            data-is-favorite="{{ auth()->user()->favoriteEquipments->contains($car->equipment_id) ? 'true' : 'false' }}">
                        @if(auth()->user()->favoriteEquipments->contains($car->equipment_id))
                            <i class="fas fa-heart mr-2"></i> Удалить из избранного
                        @else
                            <i class="far fa-heart mr-2"></i> Добавить в избранное
                        @endif
                    </button>
                @else
                    <a href="{{ route('login') }}" class="w-full px-4 py-2 rounded-lg border border-gray-600 text-gray-400 hover:bg-gray-700 transition flex items-center justify-center">
                        <i class="far fa-heart mr-2"></i> Войдите, чтобы добавить в избранное
                    </a>
                @endauth
            </div>
        </div>

        <!-- Right column - information -->
        <div class="lg:w-1/2">
            <!-- Title and price -->
            <div class="mb-6">
                <div class="flex items-center  gap-4 mb-2">
                    <!-- Логотип марки -->
                    @if($car->equipment->generation->carModel->brand->logo)
                        <img src="{{ asset('storage/' . $car->equipment->generation->carModel->brand->logo) }}" 
                             alt="{{ $car->equipment->generation->carModel->brand->name }}"
                             class="w-20 h-20 object-contain">
                    @endif
                    <span class="text-3xl font-bold">
                        {{ $car->equipment->generation->carModel->brand->name }}
                        {{ $car->equipment->generation->carModel->name }}
                        {{ $car->equipment->generation->name }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-bold text-purple-400">
                        {{ number_format($car->price, 0, '', ' ') }} ₽
                    </div>
                    <div class="text-sm bg-gray-700 px-3 py-1 rounded-full">
                        <i class="fas fa-road mr-1"></i> {{ number_format($car->mileage ?? 0, 0, '', ' ') }} км
                    </div>
                </div>
                @if($car->vin)
                    <div class="mt-2 text-sm text-gray-400">
                        <span class="font-mono">{{ $car->vin }}</span>
                    </div>
                @endif
            </div>
            <!-- Остальной код остается без изменений -->

            @if($car->is_sold)
                <div class="mb-6 p-3 bg-red-600 rounded-lg flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i> Этот автомобиль продан
                </div>
            @else
            <div class="car-card">
                
                @if(auth()->check())
                    @if(!auth()->user()->isAdmin() && !auth()->user()->isManager() && !auth()->user()->isSuperAdmin())
                        @if(!Auth::user()->hasVerifiedEmail())
                            <div class="mb-6 p-3 bg-red-500 rounded-lg flex items-center text-white">
                                <i class="fas fa-exclamation-circle mr-2"></i> Подтвердите email, чтобы забронировать автомобиль
                            </div>
                        @elseif($car->is_booked_by_me)
                            <div class="mb-6 p-3 bg-yellow-600 rounded-lg flex items-center text-white">
                                <i class="fas fa-exclamation-circle mr-2"></i> Вы уже забронировали этот автомобиль
                            </div>
                        @else
                            <form action="{{ route('bookings.store', $car) }}" method="POST" class="mb-6">
                                @csrf
                                <button type="submit" class="w-full px-6 py-3 bg-green-600 hover:bg-green-700 rounded-lg font-bold flex items-center text-white justify-center">
                                    <i class="fas fa-calendar-check mr-2"></i> Забронировать для осмотра
                                </button>
                            </form>
                        @endif
                    @endif
                @else
                    <div class="mb-6 p-3 bg-blue-500 rounded-lg flex items-center text-white">
                        <i class="fas fa-info-circle mr-2"></i> Чтобы забронировать автомобиль, войдите или зарегистрируйтесь
                    </div>
                @endauth
            </div>
            @endif

            <!-- Description -->
            @if($car->description)
                <div class="mb-6 bg-[#2A2A2A] rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-2">Описание</h3>
                    <p class="text-gray-300 whitespace-pre-line break-words overflow-wrap-break-word word-break-break-word">
                        {{ $car->description }}
                    </p>
                </div>
            @endif

            <!-- Tabs -->
            <div x-data="{ activeTab: 'specs' }" class="mb-6">
                <div class="border-b border-gray-700">
                    <nav class="-mb-px flex space-x-8">
                        <button @click="activeTab = 'specs'" :class="{'border-purple-500 text-purple-400': activeTab === 'specs', 'border-transparent text-gray-400 hover:text-gray-300': activeTab !== 'specs'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Характеристики
                        </button>
                        <button @click="activeTab = 'equipment'" :class="{'border-purple-500 text-purple-400': activeTab === 'equipment', 'border-transparent text-gray-400 hover:text-gray-300': activeTab !== 'equipment'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Описание
                        </button>
                        <button @click="activeTab = 'location'" :class="{'border-purple-500 text-purple-400': activeTab === 'location', 'border-transparent text-gray-400 hover:text-gray-300': activeTab !== 'location'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Расположение
                        </button>
                    </nav>
                </div>

                <!-- Specs tab -->
                <div x-show="activeTab === 'specs'" class="pt-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm text-gray-400">Год выпуска</h3>
                            <p class="font-medium">{{ $car->equipment->generation->year_from ?? '—' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm text-gray-400">Тип кузова</h3>
                            <p class="font-medium">{{ $car->equipment->bodyType->name ?? '—' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm text-gray-400">Комлектация</h3>
                            <p class="font-medium">
                                {{ $car->equipment->name}}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm text-gray-400">Двигатель</h3>
                            <p class="font-medium">
                                {{ $car->equipment->engineType->name ?? '—' }}
                                @if($car->equipment->engine_volume)
                                    , {{ $car->equipment->engine_volume }} л
                                @endif
                                @if($car->equipment->engine_name)
                                    ({{ $car->equipment->engine_name }})
                                @endif
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm text-gray-400">Мощность</h3>
                            <p class="font-medium">{{ $car->equipment->engine_power ?? '—' }} л.с.</p>
                        </div>
                        <div>
                            <h3 class="text-sm text-gray-400">КПП</h3>
                            <p class="font-medium">
                                {{ $car->equipment->transmissionType->name ?? '—' }}
                                @if($car->equipment->transmission_name)
                                    ({{ $car->equipment->transmission_name }})
                                @endif
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm text-gray-400">Макс. скорость</h3>
                            <p class="font-medium">{{ $car->equipment->max_speed }} км/ч</p>
                        </div>
                        <div>
                            <h3 class="text-sm text-gray-400">Привод</h3>
                            <p class="font-medium">{{ $car->equipment->driveType->name ?? '—' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm text-gray-400">Запас хода</h3>
                            <p class="font-medium">{{ $car->equipment->range}} км</p>
                        </div>
                    </div>
                    
                    <!-- Color -->
                    <div class="pt-4 mt-4 border-t border-gray-700">
                        <h3 class="text-lg font-semibold mb-2">Цвет</h3>
                        @if ($car->color)
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full mr-3 border border-gray-600" style="background-color: {{ $car->color->hex_code }};"></div>
                                <span>{{ $car->color->name }}</span>
                            </div>
                        @elseif ($car->custom_color_name || $car->custom_color_hex)
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full mr-3 border border-gray-600" style="background-color: {{ $car->custom_color_hex ?: '#000000' }};"></div>
                                <span>{{ $car->custom_color_name ?? 'Кастомный цвет' }}</span>
                            </div>
                        @else
                            <p class="text-gray-400">Цвет не указан</p>
                        @endif
                    </div>
                </div>

                <!-- Equipment tab -->
                <div x-show="activeTab === 'equipment'" class="pt-4">
                    <div class="grid grid-cols-1 gap-2">
                        @foreach([
                            ['title' => 'Страна производства', 'value' => $car->equipment->country->name ?? '—'],
                            [
                                'title' => 'Описание комплектации', 
                                'value' => $car->equipment->description ?? '—',
                                'class' => 'break-words overflow-wrap-break-word word-break-break-word'
                            ],
                            ['title' => 'Вес', 'value' => $car->equipment->weight ? $car->equipment->weight.' кг' : '—'],
                            ['title' => 'Грузоподъемность', 'value' => $car->equipment->load_capacity ? $car->equipment->load_capacity.' кг' : '—'],
                            ['title' => 'Число мест', 'value' => $car->equipment->seats ?? '—'],
                            ['title' => 'Расход топлива', 'value' => $car->equipment->fuel_consumption ? $car->equipment->fuel_consumption.' л/100км' : '—'],
                            ['title' => 'Объем бака', 'value' => $car->equipment->fuel_tank_volume ? $car->equipment->fuel_tank_volume.' л' : '—'],
                            ['title' => 'Клиренс', 'value' => $car->equipment->clearance ? $car->equipment->clearance.' мм' : '—'],
                            ['title' => 'Емкость батареи', 'value' => $car->equipment->battery_capacity ? $car->equipment->battery_capacity.' кВт·ч' : '—'],
                        ] as $item)
                               @if($item['value'] != '—')
                               <div class="flex justify-between py-2 border-b border-gray-700">
                                   <span class="text-gray-400">{{ $item['title'] }}</span>
                                   <span class="font-medium {{ $item['class'] ?? '' }} 
                                             max-w-[50%] /* Ограничивает ширину значения */
                                             text-right /* Выравнивает текст по правому краю */
                                             ">
                                       {{ $item['value'] }}
                                   </span>
                               </div>
                           @endif
                        @endforeach
                    </div>
                </div>

                <!-- Location tab -->
                <div x-show="activeTab === 'location'" class="pt-4">
                    <div class="bg-[#2A2A2A] rounded-lg p-4">
                        <h3 class="text-lg font-semibold mb-2 flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-400 mr-2"></i>
                            {{ $car->branch->name ?? 'Филиал не указан' }}
                        </h3>
                        <p class="text-gray-300 mb-4">{{ $car->branch->address ?? 'Адрес не указан' }}</p>
                        
                        @if($car->branch)
                            <div id="map" class="h-64 rounded-lg bg-gray-800"></div>
                        @else
                            <div class="h-64 rounded-lg bg-gray-800 flex items-center justify-center text-gray-400">
                                <i class="fas fa-map-marked-alt text-2xl mr-2"></i>
                                <span>Местоположение не указано</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for full-size image view -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
    <div class="relative w-full max-w-4xl max-h-screen">
        <button id="closeModal" class="absolute top-4 right-4 text-white text-3xl z-50 hover:text-purple-400 transition">
            &times;
        </button>
        <div class="flex items-center justify-center h-full">
            <img id="modalImage" src="" alt="" class="max-w-full max-h-[80vh] object-contain">
        </div>
        <button id="prevModal" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center hover:bg-purple-600 transition">
            &larr;
        </button>
        <button id="nextModal" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center hover:bg-purple-600 transition">
            &rarr;
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gallery functionality
        const images = @json($car->images->map(fn($image) => asset('storage/' . $image->path)));
        let currentImageIndex = 0;
        
        const currentImage = document.getElementById('currentImage');
        const currentIndex = document.getElementById('currentIndex');
        const prevButton = document.getElementById('prevImage');
        const nextButton = document.getElementById('nextImage');
        const thumbnails = document.querySelectorAll('.thumbnail');
        
        // Modal elements
        const imageModal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const closeModal = document.getElementById('closeModal');
        const prevModal = document.getElementById('prevModal');
        const nextModal = document.getElementById('nextModal');
        
        function updateImage(index) {
            currentImageIndex = index;
            currentImage.src = images[index];
            currentIndex.textContent = index + 1;
            
            thumbnails.forEach((thumb, i) => {
                if (i === index) {
                    thumb.classList.add('ring-2', 'ring-purple-500');
                } else {
                    thumb.classList.remove('ring-2', 'ring-purple-500');
                }
            });
        }
        
        if (prevButton && nextButton) {
            prevButton.addEventListener('click', () => {
                const newIndex = (currentImageIndex - 1 + images.length) % images.length;
                updateImage(newIndex);
            });
            
            nextButton.addEventListener('click', () => {
                const newIndex = (currentImageIndex + 1) % images.length;
                updateImage(newIndex);
            });
        }
        
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', () => {
                const index = parseInt(thumb.dataset.index);
                updateImage(index);
            });
        });
        
        currentImage.addEventListener('click', () => {
            modalImage.src = images[currentImageIndex];
            imageModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
        
        closeModal.addEventListener('click', () => {
            imageModal.classList.add('hidden');
            document.body.style.overflow = '';
        });
        
        prevModal.addEventListener('click', () => {
            const newIndex = (currentImageIndex - 1 + images.length) % images.length;
            updateImage(newIndex);
            modalImage.src = images[newIndex];
        });
        
        nextModal.addEventListener('click', () => {
            const newIndex = (currentImageIndex + 1) % images.length;
            updateImage(newIndex);
            modalImage.src = images[newIndex];
        });
        
        imageModal.addEventListener('click', (e) => {
            if (e.target === imageModal) {
                imageModal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
        
        // Photo/3D model toggle
        const photosBtn = document.getElementById('showPhotosBtn');
        const modelBtn = document.getElementById('show3dModelBtn');
        const photosContainer = document.getElementById('photosContainer');
        const modelContainer = document.getElementById('modelContainer');
        
        photosBtn.addEventListener('click', function() {
            photosContainer.classList.remove('hidden');
            modelContainer.classList.add('hidden');
            photosBtn.classList.add('bg-purple-600');
            photosBtn.classList.remove('bg-gray-700');
            if (modelBtn) {
                modelBtn.classList.add('bg-gray-700');
                modelBtn.classList.remove('bg-purple-600');
            }
        });
        
        if (modelBtn) {
            modelBtn.addEventListener('click', function() {
                photosContainer.classList.add('hidden');
                modelContainer.classList.remove('hidden');
                modelBtn.classList.add('bg-purple-600');
                modelBtn.classList.remove('bg-gray-700');
                photosBtn.classList.add('bg-gray-700');
                photosBtn.classList.remove('bg-purple-600');
                
                if (!window.modelInitialized) {
                    init3DModel();
                    window.modelInitialized = true;
                }
            });
        }
        
        // Map initialization
        if (document.getElementById('map') && typeof ymaps !== 'undefined') {
            ymaps.ready(initMap);
            
            function initMap() {
                const address = "{{ $car->branch->address ?? '' }}";
                if (!address) return;
                
                const map = new ymaps.Map('map', {
                    center: [55.76, 37.64],
                    zoom: 10,
                    controls: ['zoomControl']
                });
                
                ymaps.geocode(address, {
                    results: 1
                }).then(function(res) {
                    const firstGeoObject = res.geoObjects.get(0);
                    if (firstGeoObject) {
                        const coordinates = firstGeoObject.geometry.getCoordinates();
                        map.setCenter(coordinates, 15);
                        
                        const placemark = new ymaps.Placemark(
                            coordinates, 
                            {
                                hintContent: address,
                                balloonContent: address
                            }, 
                            {
                                preset: 'islands#redDotIcon',
                                iconColor: '#8B5CF6'
                            }
                        );
                        
                        map.geoObjects.add(placemark);
                    }
                }).catch(err => {
                    console.error('Ошибка при загрузке карты:', err);
                });
            }
        }
        
        // Favorite functionality
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const carId = this.dataset.carId;
                const isFavorite = this.dataset.isFavorite === 'true';
                const url = "{{ route('cars.favorite', ['car' => ':id']) }}".replace(':id', carId);
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.is_favorite !== undefined) {
                        this.dataset.isFavorite = data.is_favorite;
                        if (data.is_favorite) {
                            this.innerHTML = '<i class="fas fa-heart mr-2"></i> Удалить из избранного';
                        } else {
                            this.innerHTML = '<i class="far fa-heart mr-2"></i> Добавить в избранное';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let camera, scene, renderer, controls;
        let modelInitialized = false;
        const photosBtn = document.getElementById('showPhotosBtn');
        const modelBtn = document.getElementById('show3dModelBtn');
        const photosContainer = document.getElementById('photosContainer');
        const modelContainer = document.getElementById('modelContainer');
        const canvas = document.getElementById('modelCanvas');
        const fullscreenToggle = document.getElementById('fullscreenToggle');
        
        // Переключение между фотографиями и 3D моделью
        photosBtn.addEventListener('click', function() {
            photosContainer.classList.remove('hidden');
            modelContainer.classList.add('hidden');
            photosBtn.classList.add('bg-purple-600');
            photosBtn.classList.remove('bg-gray-700');
            modelBtn.classList.add('bg-gray-700');
            modelBtn.classList.remove('bg-purple-600');
        });
        
        modelBtn.addEventListener('click', function() {
            photosContainer.classList.add('hidden');
            modelContainer.classList.remove('hidden');
            modelBtn.classList.add('bg-purple-600');
            modelBtn.classList.remove('bg-gray-700');
            photosBtn.classList.add('bg-gray-700');
            photosBtn.classList.remove('bg-purple-600');
            
            if (!modelInitialized) {
                init3DModel();
                modelInitialized = true;
            }
        });
        
        function init3DModel() {
            scene = new THREE.Scene();
            camera = new THREE.PerspectiveCamera(
                75,
                canvas.clientWidth / canvas.clientHeight,
                0.1,
                1000
            );
    
            renderer = new THREE.WebGLRenderer({ 
                canvas: canvas,
                antialias: true
            });
            renderer.setSize(canvas.clientWidth, canvas.clientHeight);
            renderer.setClearColor(0x1a1a1a);
            renderer.physicallyCorrectLights = true;
            renderer.outputEncoding = THREE.sRGBEncoding;
            renderer.toneMapping = THREE.ACESFilmicToneMapping;
            renderer.toneMappingExposure = 1.0;
    
            // Освещение
            const ambientLight = new THREE.AmbientLight(0xffffff, 1);
            scene.add(ambientLight);
    
            const keyLight = new THREE.DirectionalLight(0xffffff, 2);
            keyLight.position.set(5, 5, 5);
            scene.add(keyLight);
    
            const backLight = new THREE.DirectionalLight(0xffffff, 1);
            backLight.position.set(-5, 2, -5);
            scene.add(backLight);
    
            // Камера и контроллеры
            controls = new THREE.OrbitControls(camera, renderer.domElement);
            controls.enableDamping = true;
            camera.position.z = 5;
    
            // Загрузка модели
            const loader = new THREE.GLTFLoader();
            const modelUrl = '{{ asset('storage/' . $car->equipment->model_path . '/scene.gltf') }}';
    
            loader.load(modelUrl, function(gltf) {
                const model = gltf.scene;
    
                model.traverse(child => {
                    if (child.isMesh && child.material) {
                        if (child.material.map) {
                            child.material.map.colorSpace = THREE.SRGBColorSpace;
                        }
                        if (child.material.emissiveMap) {
                            child.material.emissiveMap.colorSpace = THREE.SRGBColorSpace;
                        }
    
                        if (child.material.metalness !== undefined) {
                            child.material.metalness = Math.min(child.material.metalness, 0.9);
                        }
                        if (child.material.roughness !== undefined) {
                            child.material.roughness = Math.max(child.material.roughness, 0.1);
                        }
                    }
                });
    
                // Центрирование и масштабирование
                const box = new THREE.Box3().setFromObject(model);
                const center = box.getCenter(new THREE.Vector3());
                model.position.sub(center);
                const size = box.getSize(new THREE.Vector3());
                const maxDim = Math.max(size.x, size.y, size.z);
                model.scale.set(3 / maxDim, 3 / maxDim, 3 / maxDim);
    
                scene.add(model);
    
                // Анимация
                function animate() {
                    requestAnimationFrame(animate);
                    controls.update();
                    renderer.render(scene, camera);
                }
                animate();
    
                // Вызов resize один раз после инициализации
                handleResize();
            }, undefined, error => {
                console.error('Error loading model:', error);
                modelContainer.innerHTML = `<div class="text-center p-4 text-red-400">Ошибка загрузки модели</div>`;
            });
    
            // Обработка изменения размера окна
            window.addEventListener('resize', handleResize);
        }
        
        // Обработчик изменения размера
        function handleResize() {
            if (!camera || !renderer) return;
            
            const isFullscreen = modelContainer.classList.contains('fullscreen');
            const width = isFullscreen ? window.innerWidth : modelContainer.clientWidth;
            const height = isFullscreen ? window.innerHeight : modelContainer.clientHeight;
            
            camera.aspect = width / height;
            camera.updateProjectionMatrix();
            renderer.setSize(width, height);
        }
        
        // Переключение полноэкранного режима
        fullscreenToggle.addEventListener('click', function() {
            if (!document.fullscreenElement) {
                enterFullscreen(modelContainer);
            } else {
                exitFullscreen();
            }
        });
        
        function enterFullscreen(element) {
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullscreen) {
                element.webkitRequestFullscreen();
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            }
            
            modelContainer.classList.add('fullscreen');
            handleResize();
        }
        
        function exitFullscreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
            
            modelContainer.classList.remove('fullscreen');
            handleResize();
        }
        
        // Следим за выходом из Fullscreen (например, через Escape)
        document.addEventListener('fullscreenchange', updateFullscreenState);
        document.addEventListener('mozfullscreenchange', updateFullscreenState);
        document.addEventListener('webkitfullscreenchange', updateFullscreenState);
        document.addEventListener('msfullscreenchange', updateFullscreenState);
        
        function updateFullscreenState() {
            const isFullscreen = document.fullscreenElement || 
                               document.mozFullScreenElement || 
                               document.webkitFullscreenElement || 
                               document.msFullscreenElement;
            
            const icon = fullscreenToggle.querySelector('i');
            
            if (isFullscreen) {
                modelContainer.classList.add('fullscreen');
                icon.classList.replace('fa-expand', 'fa-compress');
            } else {
                modelContainer.classList.remove('fullscreen');
                icon.classList.replace('fa-compress', 'fa-expand');
            }
            
            handleResize();
        }
    });
</script>
<!-- Include Alpine.js for tabs functionality -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@endsection