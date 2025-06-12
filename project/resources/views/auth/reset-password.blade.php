@extends('layouts.user')

@section('content')
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-[#3C3C3C] rounded-xl p-8 shadow-lg">
            <h2 class="text-3xl font-bold text-center mb-8">Сброс пароля</h2>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-gray-300 mb-2">Email</label>
                    <input id="email" type="email"
                           class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('email') border-red-500 @enderror"
                           name="email" value="{{ old('email', $request->email) }}" required autocomplete="email" readonly>
                    @error('email')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-300 mb-2">Новый пароль</label>
                    <input id="password" type="password"
                           class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('password') border-red-500 @enderror"
                           name="password" required autocomplete="new-password">
                    @error('password')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password-confirm" class="block text-gray-300 mb-2">Подтвердите пароль</label>
                    <input id="password-confirm" type="password"
                           class="w-full px-4 py-3 bg-[#2D2D2D] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                           name="password_confirmation" required autocomplete="new-password">
                </div>

                <!-- Submit -->
                <div class="mt-8">
                    <button type="submit"
                            class="w-full px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all">
                        Сбросить пароль
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection