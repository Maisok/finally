@extends('layouts.user')

@section('content')
<section class="py-8">
    <div class="container mx-auto px-4">
        <!-- Header with user greeting -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Добро пожаловать, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-400 mt-2">Здесь вы можете управлять своим профилем и активностями</p>
        </div>

        @if(session('status'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 p-4 mb-6 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-300 p-4 mb-6 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-300 p-4 mb-6 rounded-lg">
                <ul class="mt-2 list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main grid layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left column - User info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile card -->
                <div class="bg-[#2A2A2A]  rounded-2xl shadow-xl p-6 border border-gray-700">
                    <div class="flex items-center space-x-4 mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-white">{{ auth()->user()->name }}</h2>
                            <div class="flex items-center">
                                <span class="text-gray-400 text-sm">{{ auth()->user()->email }}</span>
                                @if(auth()->user()->hasVerifiedEmail())
                                    <svg class="w-4 h-4 ml-1 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-gray-700">
                            <span class="text-gray-400">Телефон</span>
                            <span class="text-white">{{ auth()->user()->phone ?? 'Не указан' }}</span>
                        </div>
                        @if(auth()->user()->isAdmin())
                        <div class="flex justify-between py-2 border-b border-gray-700">
                            <span class="text-gray-400">Статус</span>
                            <span class="px-2 py-1 text-xs bg-gradient-to-r from-purple-600 to-indigo-600 text-[#ffffff] rounded-full">Администратор</span>
                        </div>
                        @endif
                        @if(auth()->user()->isManager())
                        <div class="flex justify-between py-2 border-b border-gray-700">
                            <span class="text-gray-400">Статус</span>
                            <span class="px-2 py-1 text-xs bg-blue-500/30 text-blue-400 rounded-full">Менеджер</span>
                        </div>
                        @endif
                    </div>

                    <div class="mt-6 space-y-3">
                        @if(!auth()->user()->hasVerifiedEmail())
                        @if(!auth()->user()->isManager() && !auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-blue-600/10 border border-blue-600/30 text-blue-400 rounded-lg hover:bg-blue-600/20 transition-all">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Подтвердить email
                                </button>
                            </form>
                        @endif
                        @endif
                        


                        @if(!auth()->user()->isManager())
                        <button onclick="openModal()" class="w-full flex items-center justify-center px-4 py-2 bg-purple-600/10 border border-indigo-600/30 text-indigo-400 rounded-lg hover:bg-indigo-600/20 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Редактировать профиль
                        </button>
                        @endif

                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block w-full text-center px-4 py-2 bg-gray-700/50 border border-gray-600/30 text-[#ffffff] rounded-lg hover:bg-gray-700 transition-all">
                            Выйти из аккаунта
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                    </div>
                </div>
  
            </div>

            <!-- Right column - Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Favorites section -->
                <div class="bg-[#2A2A2A] rounded-2xl shadow-xl p-6 border border-gray-700">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            Избранные автомобили
                        </h3>
                        <span class="bg-gray-700 text-gray-300 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $user->favoriteEquipments->count() }}
                        </span>
                    </div>
                
                    @if($user->favoriteEquipments->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <h4 class="mt-3 text-lg font-medium text-gray-300">Нет избранных автомобилей</h4>
                            <p class="mt-1 text-gray-500">Добавляйте понравившиеся автомобили в избранное</p>
                            <a href="{{ route('catalog') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-purple-600 text-[#ffffff] rounded-lg transition hover:bg-indigo-700">
                                Перейти в каталог
                            </a>
                        </div>
                    @else
                        <!-- Блоки в сетке -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($user->favoriteEquipments->take(10) as $equipment)
                                <div class="bg-[#3C3C3C] hover:bg-gray-800 rounded-xl border border-gray-700 overflow-hidden transition-all">
                                    <div class="p-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-semibold text-white line-clamp-1">
                                                    {{ $equipment->generation->carModel->brand->name }} {{ $equipment->generation->carModel->name }}
                                                </h4>
                                                <p class="text-sm text-gray-400 mt-1">Поколение: {{ $equipment->generation->name }}</p>
                                            </div>
                                            <form method="POST" action="{{ route('favorites.remove', $equipment) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-rose-400 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="px-4 pb-4">
                                        <a href="{{ route('catalog', [
                                            'brand' => $equipment->generation->carModel->brand->id,
                                            'model' => $equipment->generation->carModel->id
                                        ]) }}"
                                           class="w-full inline-flex justify-center items-center px-3 py-2 text-sm font-medium text-center text-white bg-purple-600 rounded-lg hover:bg-indigo-700 transition">
                                            Подробнее
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                
                        @if($user->favoriteEquipments->count() > 10)
                            <div class="mt-6 text-center">
                                <a href="{{ route('favorites.all') }}" class="text-purple-600 hover:text-indigo-300 text-sm font-medium">
                                    Показать все {{ $user->favoriteEquipments->count() }} автомобилей в избранном →
                                </a>
                            </div>
                        @endif
                    @endif
                </div>



                @if (!(auth()->user()->isManager()) && !(auth()->user()->isAdmin()))
                <!-- Bookings section -->
                <div class="bg-[#2A2A2A]  rounded-2xl shadow-xl p-6 border border-gray-700">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Мои бронирования
                        </h3>
                        <span class="bg-gray-700 text-gray-300 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $bookings->count() }}</span>
                    </div>

                    @if($bookings->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h4 class="mt-3 text-lg font-medium text-gray-300">Нет активных бронирований</h4>
                            <p class="mt-1 text-gray-500">Забронируйте автомобиль для тест-драйва</p>
                            <a href="{{ route('catalog') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-purple-600 text-[#ffffff] rounded-lg transition">
                                Перейти в каталог
                            </a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($bookings->take(10) as $booking)
                                <div class="bg-[#3C3C3C] hover:bg-gray-800 rounded-xl border border-gray-700 p-4 transition-all">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                        <div class="mb-3 sm:mb-0">
                                            <h4 class="font-semibold text-white">
                                                {{ $booking->car->equipment->generation->carModel->brand->name }} {{ $booking->car->equipment->generation->carModel->name }}
                                            </h4>
                                            <div class="flex items-center mt-1">
                                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-sm text-gray-400">{{ $booking->appointment_date}}</span>
                                                <span class="mx-2 text-gray-600">•</span>
                                                <span class="text-sm 
                                                @switch($booking->status)
                                                    @case('pending') text-yellow-400 @break
                                                    @case('confirmed') text-emerald-400 @break
                                                    @case('rejected') text-rose-400 @break
                                                    @case('cancelled') text-gray-400 @break
                                                    @default text-blue-400
                                                @endswitch">
                                                @switch($booking->status)
                                                    @case('pending') Ожидание @break
                                                    @case('confirmed') Подтверждено @break
                                                    @case('rejected') Отклонено @break
                                                    @case('completed') Выполнено @break
                                                    @default Статус неизвестен
                                                @endswitch
                                            </span>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('bookings.show', $booking) }}" class="px-3 py-1.5 text-sm bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                                                Подробнее
                                            </a>
                                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                                <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Вы уверены, что хотите отменить бронирование?')" class="px-3 py-1.5 text-sm bg-rose-600/10 hover:bg-rose-600/20 border border-rose-600/30 text-rose-400 rounded-lg transition">
                                                        Отменить
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($bookings->count() > 10)
                                <div class="mt-6 text-center">
                                    <a href="{{ route('bookings.all') }}" class="text-purple-600  hover:text-indigo-300 text-sm font-medium">
                                        Показать все {{ $bookings->count() }} бронирований в избранном →
                                    </a>
                                </div>
                            @endif
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-[#2A2A2A]   w-full max-w-md rounded-2xl shadow-2xl border border-gray-700 overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-gray-700">
            <h3 class="text-xl font-bold text-white">Редактирование профиля</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Tabs -->
        <div class="border-b border-gray-700 flex">
            <button id="profileTabBtn" class="tab-btn flex-1 py-4 font-medium text-indigo-400 border-b-2 border-indigo-400">
                Основные данные
            </button>
            <button id="passwordTabBtn" class="tab-btn flex-1 py-4 font-medium text-gray-400 border-b-2 border-transparent">
                Смена пароля
            </button>
        </div>

        <!-- Profile Form -->
        <div id="profileFormContent" class="tab-content active p-6">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Имя</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-rose-500 @enderror">
                        @error('name')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') border-rose-500 @enderror">
                        @error('email')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-300 mb-1">Телефон</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('phone') border-rose-500 @enderror">
                        @error('phone')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                        Отмена
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-purple-600 text-[#ffffff] rounded-lg transition">
                        Сохранить
                    </button>
                </div>
            </form>
        </div>

        <!-- Password Form -->
        <div id="passwordFormContent" class="tab-content hidden p-6">
            <form method="POST" action="{{ route('password.edit') }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-300 mb-1">Текущий пароль</label>
                        <input id="current_password" type="password" name="current_password"
                               class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('current_password') border-rose-500 @enderror">
                        @error('current_password')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Новый пароль</label>
                        <input id="password" type="password" name="password"
                               class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('password') border-rose-500 @enderror">
                        @error('password')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Подтвердите пароль</label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                        Отмена
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-purple-600 text-[#ffffff] rounded-lg transition">
                        Обновить пароль
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const nameInput = document.getElementById("name");
    const phoneInput = document.getElementById("phone");
    const currentPasswordInput = document.getElementById("current_password");
    const newPasswordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("password_confirmation");

    // --- Автоматическая заглавная буква у имени ---
    nameInput.addEventListener("input", function () {
        this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);
    });

    // --- Маска телефона: 8 888 888 88 88 ---
    phoneInput.addEventListener("input", function () {
        let input = this.value.replace(/\D/g, '');
        let formatted = '';

        if (input.length > 0) {
            formatted = '8 ';
        }

        if (input.length > 1) {
            formatted += input.substring(1, 4);
        }

        if (input.length > 4) {
            formatted += ' ' + input.substring(4, 7);
        }

        if (input.length > 7) {
            formatted += ' ' + input.substring(7, 9);
        }

        if (input.length > 9) {
            formatted += ' ' + input.substring(9, 11);
        }

        this.value = formatted;
    });

    // --- Проверка сложности нового пароля ---
    newPasswordInput.addEventListener("input", function () {
        const pass = this.value;
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        if (!regex.test(pass)) {
            this.setCustomValidity("Пароль не соответствует требованиям");
        } else {
            this.setCustomValidity("");
        }
    });

    // --- Сравнение нового пароля и подтверждения ---
    confirmPasswordInput.addEventListener("input", function () {
        if (this.value !== newPasswordInput.value) {
            this.setCustomValidity("Пароли не совпадают");
        } else {
            this.setCustomValidity("");
        }
    });

    // --- Валидация формы при отправке ---
    document.querySelector('#profileFormContent form').addEventListener("submit", function (e) {
        const password = newPasswordInput.value;
        const confirm = confirmPasswordInput.value;

        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

        if (password && !regex.test(password)) {
            e.preventDefault();
            alert("Пароль должен содержать минимум 8 символов, одну заглавную, одну строчную букву и цифру.");
        }

        if (password !== confirm) {
            e.preventDefault();
            alert("Пароли не совпадают");
        }
    });

    // --- Для формы изменения пароля ---
    document.querySelector('#passwordFormContent form').addEventListener("submit", function (e) {
        const password = newPasswordInput.value;
        const confirm = confirmPasswordInput.value;

        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

        if (!regex.test(password)) {
            e.preventDefault();
            alert("Пароль должен содержать минимум 8 символов, одну заглавную, одну строчную букву и цифру.");
        }

        if (password !== confirm) {
            e.preventDefault();
            alert("Пароли не совпадают");
        }
    });
});
</script>

<script>
    // Modal functions
    function openModal() {
        document.getElementById('editModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            // Update tabs
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('text-indigo-400', 'border-indigo-400');
                b.classList.add('text-gray-400', 'border-transparent');
            });
            btn.classList.add('text-indigo-400', 'border-indigo-400');
            btn.classList.remove('text-gray-400', 'border-transparent');
            
            // Update content
            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            const target = btn.id === 'profileTabBtn' ? 'profileFormContent' : 'passwordFormContent';
            document.getElementById(target).classList.remove('hidden');
        });
    });

    // Close modal when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === document.getElementById('editModal')) {
            closeModal();
        }
    });
</script>
@endsection