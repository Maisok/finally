@extends('layouts.user')

@section('content')
<section class="py-10">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold text-white mb-6">Создать нового администратора</h1>

        @if($errors->any())
            <div class="bg-red-500/20 border-l-4 border-red-500 text-red-300 p-4 mb-6 rounded">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-[#3C3C3C] rounded-xl shadow-lg p-6">
            <form action="{{ route('admin.admins.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-400 mb-2">Имя</label>
                        <input type="text" name="name" id="name" 
                               class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               value="{{ old('name') }}" required>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                        <input type="email" name="email" id="email" 
                               class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               value="{{ old('email') }}" required>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-400 mb-2">Телефон</label>
                        <input type="text" name="phone" id="phone" 
                               class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               value="{{ old('phone') }}">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Пароль</label>
                        <input type="password" name="password" id="password" 
                               class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               required>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-400 mb-2">Подтвердите пароль</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                               required>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.admins.index') }}" 
                       class="px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-all">
                        Отмена
                    </a>
                    <button type="submit" 
                            class="px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-all">
                        Создать
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const phoneInput = document.getElementById('phone');

        if (phoneInput) {
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
        }
    });
</script>
@endsection