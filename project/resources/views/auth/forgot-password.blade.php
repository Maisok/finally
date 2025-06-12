@extends('layouts.user')

@section('content')
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-[#3C3C3C] rounded-xl p-8 shadow-lg">
            <h2 class="text-3xl font-bold text-center mb-8">Восстановление пароля</h2>

            @if (session('status'))
                <div class="bg-green-500/20 border-l-4 border-green-500 text-green-300 p-4 mb-6 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-gray-300 mb-2">Email</label>
                    <input id="email" type="email"
                           class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('email') border-red-500 @enderror"
                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="mt-8">
                    <button type="submit"
                            class="w-full px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all">
                        Отправить ссылку для сброса
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection