@extends('layouts.user')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-white mb-6">Избранные автомобили</h2>

    @if($favorites->isEmpty())
        <div class="text-center py-8 bg-gray-800 rounded-lg">
            <svg class="mx-auto w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <h4 class="mt-3 text-lg font-medium text-gray-300">Нет избранных автомобилей</h4>
            <p class="mt-1 text-gray-500">Добавляйте понравившиеся автомобили в избранное</p>
            <a href="{{ route('catalog') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg transition hover:bg-purple-700">
                Перейти в каталог
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($favorites as $equipment)
                <div class="bg-[#3C3C3C] hover:bg-gray-800 rounded-xl border border-gray-700 overflow-hidden transition-all">
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-white line-clamp-1">
                                    {{ $equipment->generation->carModel->brand->name }} {{ $equipment->generation->carModel->name }}
                                </h4>
                                <p class="text-sm text-gray-400 mt-1">Комплектация: {{ $equipment->name }}</p>
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
                                        ]) }}" class="w-full inline-flex justify-center items-center px-3 py-2 text-sm font-medium text-center text-white bg-purple-600 rounded-lg hover:bg-indigo-700 transition">
                            Подробнее
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection