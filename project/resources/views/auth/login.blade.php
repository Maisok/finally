@extends('layouts.user')

@section('content')
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-[#3C3C3C] rounded-xl p-8 shadow-lg">
            <h2 class="text-3xl font-bold text-center mb-8">Вход в аккаунт</h2>


            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="mb-6">
                    <label for="email" class="block text-gray-300 mb-2">Email</label>
                    <input id="email" type="email"
                           class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('email') border-red-500 @enderror"
                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                           maxlength="191">
                    @error('email')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-gray-300 mb-2">Пароль</label>
                    <input id="password" type="password"
                           class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('password') border-red-500 @enderror"
                           name="password" required autocomplete="current-password"
                           minlength="8">
                           <p class="text-xs text-gray-400 mt-1">Минимум 8 символов, одна заглавная, одна строчная буква и цифра</p>
                    @error('password')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="mt-8">
                    <button type="submit"
                            class="w-full px-6 py-3 bg-purple-600 text-[#ffffff] rounded-lg hover:bg-purple-700 transition-all">
                        Войти
                    </button>
                </div>
            </form>

            <!-- Forgot Password -->
            <div class="mt-6 text-center">
                <a href="{{ route('password.request') }}" class="text-sm text-purple-400 hover:underline">
                    Забыли пароль?
                </a>
            </div>

            <!-- Register Link -->
            <div class="mt-6 text-center">
                <p class="text-gray-400">Нет аккаунта?
                    <a href="{{ route('register') }}" class="text-purple-400 hover:underline">Зарегистрируйтесь</a>
                </p>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const form = document.getElementById("loginForm");

    // Очистка email
    emailInput.addEventListener("input", function () {
        this.value = this.value.trim();
    });

    // Проверка длины пароля
    passwordInput.addEventListener("input", function () {
            const pass = this.value;
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
            if (!regex.test(pass)) {
                this.setCustomValidity("Некорректный пароль");
            } else {
                this.setCustomValidity("");
            }
        });
    // Перед отправкой формы
    form.addEventListener("submit", function (e) {
        const email = emailInput.value.trim();
        const password = passwordInput.value;

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert("Введите корректный email адрес.");
        }

        if (password.length > 0 && password.length < 8) {
            e.preventDefault();
            alert("Пароль должен содержать минимум 8 символов.");
        }
    });
});
</script>
@endsection