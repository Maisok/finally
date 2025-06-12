@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Заголовок -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Комплектации</h1>
        </div>

        <!-- Уведомления -->
        <div class="space-y-3 mb-6">
            @if ($errors->any())
                <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded shadow-sm">
                    <ul class="space-y-1 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded shadow-sm flex items-start">
                    <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm text-red-700">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="p-4 bg-green-50 border-l-4 border-green-500 rounded shadow-sm flex items-start">
                    <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm text-green-700">{{ session('success') }}</span>
                </div>
            @endif
        </div>

        <!-- Форма фильтрации комплектаций -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 mb-6">
            <div class="px-4 py-3 sm:px-6 border-b border-gray-200">
                <h3 class="text-sm sm:text-base font-medium text-gray-700">Фильтр комплектаций</h3>
            </div>
            <div class="p-4 sm:p-6">
                <form method="GET" action="{{ route('admin.equipments.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Марка -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Марка</label>
                            <select name="brand_id" id="brand-select" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2" data-model-select="#model-select" data-generation-select="#generation-select">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Модель</label>
                            <select name="model_id" id="model-select" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2" data-generation-select="#generation-select">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Поколение</label>
                            <select name="generation_id" id="generation-select" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2">
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

                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 pt-2">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                            Найти
                        </button>
                        <a href="{{ route('admin.equipments.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Сбросить
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Кнопка добавления -->
        <div class="mb-6">
            <a href="{{ route('admin.equipments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Добавить комплектацию
            </a>
        </div>

        <!-- Таблица комплектаций -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Марка</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Модель</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Поколение</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Двигатель</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Привод</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Цвета</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($equipments as $eq)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($eq->generation->carModel->brand)->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($eq->generation->carModel)->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($eq->generation)->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($eq->engineType)->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ optional($eq->driveType)->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @foreach ($eq->colors as $color)
                                        <div class="flex items-center mb-1">
                                            <span style="width: 16px; height: 16px; background-color: {{ $color->hex_code }};" class="inline-block mr-2 rounded-full"></span>
                                            <span>{{ $color->name }}</span>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.equipments.edit', $eq) }}" class="text-blue-600 hover:text-blue-900">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.equipments.destroy', $eq) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Удалить комплектацию?')">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
        <div class="mb-8">
            @include('components.custom-pagination', ['paginator' => $equipments])
        </div>

        <!-- Раздел управления цветами -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Управление цветами</h3>
            </div>

            <!-- Форма фильтрации цветов -->
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" action="{{ route('admin.equipments.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Поиск по имени или HEX коду -->
                        <div>
                            <label for="color_search" class="block text-sm font-medium text-gray-700 mb-1">Поиск цвета</label>
                            <input type="text" name="color_search" id="color_search" value="{{ request('color_search') }}"
                                placeholder="Название или HEX код"  class="block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                        </div>

                        <!-- Выбор комплектации -->
                        <div>
                            <label for="equipment-select" class="block text-sm font-medium text-gray-700 mb-1">Комплектация</label>
                            <select name="equipment_id" id="equipment-select" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2">
                                <option value="">Все комплектации</option>
                                @foreach ($allEquipments as $eqOption)
                                    <option value="{{ $eqOption['id'] }}" {{ request('equipment_id') == $eqOption['id'] ? 'selected' : '' }}>
                                        {{ $eqOption['text'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Кнопки -->
                        <div class="flex items-end space-x-3">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                                Найти
                            </button>
                            <a href="{{ route('admin.equipments.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Сбросить
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Таблица цветов -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Цвет</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HEX код</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Комплектации</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Сохранить</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Удалить</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($colors as $color)
                        <tr>
                            <!-- Форма редактирования -->
                            <form method="POST" action="{{ route('admin.colors.update') }}" class="w-full">
                                @csrf
                                <input type="hidden" name="color_id" value="{{ $color->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="text" name="name" value="{{ $color->name }}"
                                           class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span style="width: 20px; height: 20px; background-color: {{ $color->hex_code }};"
                                          class="inline-block rounded-full"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="text" name="hex_code" value="{{ $color->hex_code }}"
                                           maxlength="7" placeholder="#FF5733"
                                           class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @foreach ($color->equipments as $eq)
                                        <div class="text-xs mb-1">
                                            {{ optional($eq->generation->carModel->brand)->name }}
                                            → {{ optional($eq->generation->carModel)->name }}
                                            ({{ optional($eq->generation)->name }})
                                        </div>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button type="submit" class="text-blue-600 hover:text-blue-900">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </td>
                            </form>
                    
                            <!-- Отдельная форма удаления -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form method="POST" action="{{ route('admin.colors.delete') }}" class="inline" onsubmit="return confirm('Удалить цвет?')">
                                    @csrf
                                    <input type="hidden" name="color_id" value="{{ $color->id }}">
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Цветов пока нет</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Пагинация -->
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                @include('components.custom-pagination', ['paginator' => $colors])
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            // Инициализация Select2
            $('.select2').select2({
                placeholder: "Выберите значение",
                allowClear: true,
                width: '100%'
            });
        
            // При изменении марки — обновляем список моделей
            $('#brand-select').on('change', function () {
                const brandId = $(this).val();
                const modelSelect = $('#model-select');
                const generationSelect = $('#generation-select');
        
                if (!brandId) {
                    modelSelect.html('<option value="">Все модели</option>');
                    generationSelect.html('<option value="">Все поколения</option>');
        
                    modelSelect.trigger('change');
                    generationSelect.trigger('change');
                    return;
                }
        
                // Загружаем модели
                $.ajax({
                    url: `/api/brand/${brandId}/models`,
                    method: 'GET',
                    success: function (data) {
                        modelSelect.empty().append(`<option value="">Все модели</option>`);
                        data.forEach(model => {
                            modelSelect.append(`<option value="${model.id}">${model.name}</option>`);
                        });
                        modelSelect.trigger('change');
                    },
                    error: function () {
                        modelSelect.empty().append(`<option value="">Ошибка загрузки</option>`);
                    }
                });
            });
        
            // При изменении модели — обновляем список поколений
            $('#model-select').on('change', function () {
                const modelId = $(this).val();
                const generationSelect = $('#generation-select');
        
                if (!modelId) {
                    generationSelect.html('<option value="">Все поколения</option>');
                    return;
                }
        
                // Загружаем поколения
                $.ajax({
                    url: `/api/model/${modelId}/generations`,
                    method: 'GET',
                    success: function (data) {
                        generationSelect.empty().append(`<option value="">Все поколения</option>`);
                        data.forEach(gen => {
                            generationSelect.append(`<option value="${gen.id}">${gen.name} (${gen.year_from}–${gen.year_to || ''})</option>`);
                        });
                        generationSelect.trigger('change');
                    },
                    error: function () {
                        generationSelect.empty().append(`<option value="">Ошибка загрузки</option>`);
                    }
                });
            });
        
            // Автозапуск при загрузке страницы
            const initialBrandId = "{{ request('brand_id') }}";
            const initialModelId = "{{ request('model_id') }}";
        
            if (initialBrandId) {
                $('#brand-select').trigger('change');
            }
        });
    </script>
@endsection