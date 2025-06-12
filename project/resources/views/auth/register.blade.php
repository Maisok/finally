@extends('layouts.user')

@section('content')
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-[#3C3C3C] rounded-xl p-8 shadow-lg">
            <h2 class="text-3xl font-bold text-center mb-8">Регистрация</h2>

            @error('name')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
            
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-gray-300 mb-2">Имя</label>
                    <input id="name" type="text"
                           class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('name') border-red-500 @enderror"
                           name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                           maxlength="100">
                    @error('name')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-gray-300 mb-2">Email</label>
                    <input id="email" type="email"
                           class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('email') border-red-500 @enderror"
                           name="email" value="{{ old('email') }}" required autocomplete="email"
                           maxlength="191">
                    @error('email')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="mb-6">
                    <label for="phone" class="block text-gray-300 mb-2">Телефон</label>
                    <input id="phone" type="text"
                           class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('phone') border-red-500 @enderror"
                           name="phone" value="{{ old('phone') }}" required autocomplete="tel" maxlength="20">
                    @error('phone')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-300 mb-2">Пароль</label>
                    <input id="password" type="password"
                           class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('password') border-red-500 @enderror"
                           name="password" required autocomplete="new-password"
                           minlength="8">
                    <p class="text-xs text-gray-400 mt-1">Минимум 8 символов, одна заглавная, одна строчная буква и цифра</p>
                    @error('password')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password-confirm" class="block text-gray-300 mb-2">Подтвердите пароль</label>
                    <input id="password-confirm" type="password"
                           class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                           name="password_confirmation" required autocomplete="new-password"
                           minlength="8">
                </div>

                <!-- Submit -->
                <div class="mt-8">
                    <button type="submit"
                            class="w-full px-6 py-3 bg-purple-600 text-[#ffffff] rounded-lg hover:bg-purple-700 transition-all">
                        Зарегистрироваться
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-gray-400">Уже есть аккаунт?
                    <a href="{{ route('login') }}" class="text-purple-400 hover:underline">Войдите</a>
                </p>
            </div>
        </div>
    </div>
</section>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const nameInput = document.getElementById("name");
        const phoneInput = document.getElementById("phone");
        const passwordInput = document.getElementById("password");
        const confirmPasswordInput = document.getElementById("password-confirm");
        const form = document.getElementById("registerForm");

        // --- Автоматическая заглавная буква в имени ---
        nameInput.addEventListener("input", function () {
            let val = this.value;
            if (val) {
                this.value = val.charAt(0).toUpperCase() + val.slice(1);
            }
        });

        // --- Маска для телефона: 8 888 888 88 88 ---
        phoneInput.addEventListener("input", function (e) {
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

        // --- Валидация пароля ---
        passwordInput.addEventListener("input", function () {
            const pass = this.value;
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
            if (!regex.test(pass)) {
                this.setCustomValidity("Некорректный пароль");
            } else {
                this.setCustomValidity("");
            }
        });

        // --- Сравнение паролей ---
        confirmPasswordInput.addEventListener("input", function () {
            if (this.value !== passwordInput.value) {
                this.setCustomValidity("Пароли не совпадают");
            } else {
                this.setCustomValidity("");
            }
        });

        // --- Финальная проверка перед отправкой ---
        form.addEventListener("submit", function (e) {
            const password = passwordInput.value;
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
@endsection