@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Автомобили</h1>

        <!-- Уведомления -->
        <div class="space-y-4 mb-6">
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Произошли ошибки:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm flex items-start">
                    <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm text-green-700">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm flex items-start">
                    <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm text-red-700">{{ session('error') }}</span>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 mb-4 rounded">
                    {{ session('warning') }}
                </div>
            @endif
        </div>

        <!-- Форма поиска -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200 mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-sm sm:text-base font-medium text-gray-700">Фильтр автомобилей</h3>
            </div>
            <div class="p-4 sm:p-6">
                <form method="GET" action="{{ route('admin.cars.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Обычный поиск -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Поиск</label>
                            <input type="text" name="search" id="search"
                                   value="{{ request('search') }}"
                                   placeholder="VIN, марка, модель, поколение"
                                   class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                        </div>

                        <!-- Марка -->
                        <div>
                            <label for="brand-select" class="block text-sm font-medium text-gray-700 mb-1">Марка</label>
                            <select name="brand_id" id="brand-select" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2">
                                <option value="">Все марки</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Модель -->
                        <div>
                            <label for="model-select" class="block text-sm font-medium text-gray-700 mb-1">Модель</label>
                            <select name="model_id" id="model-select" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2">
                                <option value="">Все модели</option>
                                @if (!empty($models))
                                    @foreach ($models as $model)
                                        <option value="{{ $model->id }}" {{ request('model_id') == $model->id ? 'selected' : '' }}>
                                            {{ optional($model->brand)->name }} — {{ $model->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Поколение -->
                        <div>
                            <label for="generation-select" class="block text-sm font-medium text-gray-700 mb-1">Поколение</label>
                            <select name="generation_id" id="generation-select" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2">
                                <option value="">Все поколения</option>
                                @if (!empty($generations))
                                    @foreach ($generations as $gen)
                                        <option value="{{ $gen->id }}" {{ request('generation_id') == $gen->id ? 'selected' : '' }}>
                                            {{ optional($gen->carModel)->name }} — {{ $gen->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <!-- Сортировка и кнопки -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 pt-4">
                        <div class="w-full sm:w-auto">
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Сортировать</label>
                            <select name="sort" id="sort" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2">
                                <option value="">Без сортировки</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Цена ↑</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Цена ↓</option>
                                <option value="mileage_asc" {{ request('sort') === 'mileage_asc' ? 'selected' : '' }}>Пробег ↑</option>
                                <option value="mileage_desc" {{ request('sort') === 'mileage_desc' ? 'selected' : '' }}>Пробег ↓</option>
                            </select>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                                Найти
                            </button>
                            @if(request()->hasAny(['search', 'brand_id', 'model_id', 'generation_id', 'sort']))
                                <a href="{{ route('admin.cars.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Сбросить
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Кнопки добавления и экспорта -->
        <div class="flex flex-col sm:flex-row gap-3 mb-6">
            <a href="{{ route('admin.cars.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Добавить автомобиль
            </a>
        </div>

        <!-- Блок экспорта -->
<div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200 mt-8">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-sm sm:text-base font-medium text-gray-700">Экспорт отчета по продажам</h3>
    </div>
    <div class="p-4 sm:p-6">
        <form method="GET" action="{{ route('admin.cars.export.sales-report') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Марка -->
                <div>
                    <label for="export-brand" class="block text-sm font-medium text-gray-700 mb-1">Марка</label>
                    <select name="brand_id" id="export-brand" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2">
                        <option value="">Все марки</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Модель -->
                <div>
                    <label for="export-model" class="block text-sm font-medium text-gray-700 mb-1">Модель</label>
                    <select name="model_id" id="export-model" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2">
                        <option value="">Все модели</option>
                    </select>
                </div>

                <!-- Поколение -->
                <div>
                    <label for="export-generation" class="block text-sm font-medium text-gray-700 mb-1">Поколение</label>
                    <select name="generation_id" id="export-generation" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2">
                        <option value="">Все поколения</option>
                    </select>
                </div>

                <!-- Комплектация -->
                <div>
                    <label for="export-equipment" class="block text-sm font-medium text-gray-700 mb-1">Комплектация</label>
                    <select name="equipment_id" id="export-equipment" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2">
                        <option value="">Все комплектации</option>
                    </select>
                </div>

                <!-- Дата начала -->
                <div>
                    <label for="export-start-date" class="block text-sm font-medium text-gray-700 mb-1">Дата с</label>
                    <input type="date" name="start_date" id="export-start-date" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                </div>

                <!-- Дата окончания -->
                <div>
                    <label for="export-end-date" class="block text-sm font-medium text-gray-700 mb-1">Дата по</label>
                    <input type="date" name="end_date" id="export-end-date" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 pt-4">
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Экспортировать
                </button>
            </div>
        </form>
    </div>
</div>

        <!-- Таблица автомобилей -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">VIN</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Марка / Модель</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Цвет</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Цена</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Пробег</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Филиал</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($cars as $car)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $car->vin }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="font-medium">{{ optional($car->equipment->generation->carModel->brand)->name }}</div>
                                    <div>{{ optional($car->equipment->generation->carModel)->name }}</div>
                                    <div class="text-gray-400">{{ optional($car->equipment->generation)->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if ($car->color)
                                        <div class="flex items-center">
                                            <span style="width: 16px; height: 16px; background-color: {{ $car->color->hex_code }};" class="inline-block mr-2 rounded-full border border-gray-200"></span>
                                            <span>{{ $car->color->name }}</span>
                                        </div>
                                    @elseif ($car->custom_color_name)
                                        <div class="flex items-center">
                                            <span style="width: 16px; height: 16px; background-color: {{ $car->custom_color_hex }};" class="inline-block mr-2 rounded-full border border-gray-200"></span>
                                            <span>{{ $car->custom_color_name }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">Нет данных</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($car->price, 0, ',', ' ') }} ₽</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $car->mileage ? number_format($car->mileage, 0, ',', ' ') . ' км' : '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ optional($car->branch)->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $car->is_sold ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $car->is_sold ? 'Продан' : 'На продаже' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.cars.edit', $car) }}" class="text-blue-600 hover:text-blue-900 flex items-center">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.cars.destroy', $car) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 flex items-center"
                                                    onclick="return confirm('Вы уверены, что хотите удалить этот автомобиль?')">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Пагинация -->
        <div class="mt-6">
            @include('components.custom-pagination', ['paginator' => $cars])
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {
    $('.select2').select2({
        placeholder: "Выберите значение",
        allowClear: true,
        width: '100%'
    });

    // При изменении марки → обновляем список моделей
    $('#brand-select').on('change', function () {
        const brandId = $(this).val();
        const modelSelect = $('#model-select');

        modelSelect.empty().append('<option value="">Все модели</option>');

        if (!brandId) return;

        $.get(`/api/brand/${brandId}/models`, function (data) {
            data.forEach(function(model) {
                modelSelect.append(`<option value="${model.id}">${model.brand?.name || ''} — ${model.name}</option>`);
            });
            modelSelect.trigger('change');
        });
    });

    // При изменении модели → обновляем список поколений
    $('#model-select').on('change', function () {
        const modelId = $(this).val();
        const generationSelect = $('#generation-select');

        generationSelect.empty().append('<option value="">Все поколения</option>');

        if (!modelId) return;

        $.get(`/api/model/${modelId}/generations`, function (data) {
            data.forEach(gen => {
                generationSelect.append(`<option value="${gen.id}">${gen.name}</option>`);
            });
            generationSelect.trigger('change');
        });
    });

    // Автозапуск при редактировании
    const initialBrandId = "{{ request('brand_id') }}";
    const initialModelId = "{{ request('model_id') }}";

    if (initialBrandId) {
        $('#brand-select').trigger('change');
    }
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageInput = document.getElementById('new_images');
        if (!imageInput) return;
    
        imageInput.addEventListener('change', function () {
            const files = Array.from(imageInput.files);
            const maxImages = 20;
    
            // Проверка: выбрано хотя бы одно фото
            if (files.length === 0) {
                alert('Выберите хотя бы одно изображение');
                return;
            }
    
            // Проверка: не больше 20 фото
            if (files.length > maxImages) {
                let errorDiv = document.getElementById('image-limit-error');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.id = 'image-limit-error';
                    errorDiv.className = 'text-red-600 text-sm mt-1';
                    errorDiv.innerText = `Выбрано более ${maxImages} фото. Сохранены только первые ${maxImages}`;
                    imageInput.parentNode.appendChild(errorDiv);
                } else {
                    errorDiv.style.display = 'block';
                    errorDiv.innerText = `Выбрано более ${maxImages} фото. Сохранены только первые ${maxImages}`;
                }
    
                // Ограничиваем до 20 фото
                const allowedFiles = files.slice(0, maxImages);
                const dt = new DataTransfer();
    
                allowedFiles.forEach(file => dt.items.add(file));
                imageInput.files = dt.files;
            }
        });
    });
    </script>
@endsection