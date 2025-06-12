@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Добавить комплектацию</h2>

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
        </div>

        <form action="{{ route('admin.equipments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Блок выбора марка → модель → поколение -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Марка -->
                <div>
                    <label for="brand-select" class="block text-sm font-medium text-gray-700 mb-1">Марка</label>
                    <select id="brand-select" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2 brand-select" required>
                        <option value="">Выберите марку</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Модель -->
                <div>
                    <label for="model-select" class="block text-sm font-medium text-gray-700 mb-1">Модель</label>
                    <select id="model-select" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2 model-select" disabled required>
                        <option value="">Выберите модель</option>
                    </select>
                </div>

                <!-- Поколение -->
                <div>
                    <label for="generation-select" class="block text-sm font-medium text-gray-700 mb-1">Поколение</label>
                    <select name="generation_id" id="generation-select" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2" disabled required>
                        <option value="">Выберите поколение</option>
                    </select>
                </div>
            </div>

            <!-- Основные данные -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Название комплектации</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2"
                           maxlength="50" required>
                </div>

                <div>
                    <label for="body_type_id" class="block text-sm font-medium text-gray-700 mb-1">Тип кузова</label>
                    <select name="body_type_id" id="body_type_id" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2" required>
                        <option value="">Выберите тип кузова</option>
                        @foreach ($bodyTypes as $type)
                            <option value="{{ $type->id }}" {{ old('body_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="engine_type_id" class="block text-sm font-medium text-gray-700 mb-1">Тип двигателя</label>
                    <select name="engine_type_id" id="engine_type_id" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2" required>
                        <option value="">Выберите тип двигателя</option>
                        @foreach ($engineTypes as $type)
                            <option value="{{ $type->id }}" {{ old('engine_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="transmission_type_id" class="block text-sm font-medium text-gray-700 mb-1">Тип КПП</label>
                    <select name="transmission_type_id" id="transmission_type_id" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2" required>
                        <option value="">Выберите тип КПП</option>
                        @foreach ($transmissionTypes as $type)
                            <option value="{{ $type->id }}" {{ old('transmission_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="drive_type_id" class="block text-sm font-medium text-gray-700 mb-1">Привод</label>
                    <select name="drive_type_id" id="drive_type_id" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2" required>
                        <option value="">Выберите привод</option>
                        @foreach ($driveTypes as $type)
                            <option value="{{ $type->id }}" {{ old('drive_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="country_id" class="block text-sm font-medium text-gray-700 mb-1">Страна сборки</label>
                    <select name="country_id" id="country_id" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2" required>
                        <option value="">Выберите страну</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="engine_volume" class="block text-sm font-medium text-gray-700 mb-1">Объем двигателя (л)</label>
                    <input type="number" step="0.1" min="0.8" max="8.0" name="engine_volume" id="engine_volume"
                           value="{{ old('engine_volume') }}" 
                           class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2" required>
                </div>

                <div>
                    <label for="engine_power" class="block text-sm font-medium text-gray-700 mb-1">Мощность двигателя (л.с.)</label>
                    <input type="number" min="40" max="2000" name="engine_power" id="engine_power"
                           value="{{ old('engine_power') }}" 
                           class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2" required>
                </div>

                <div>
                    <label for="range" class="block text-sm font-medium text-gray-700 mb-1">Запас хода (км)</label>
                    <input type="number" min="50" max="1000" name="range" id="range"
                           value="{{ old('range') }}" 
                           class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2" required>
                </div>

                <div>
                    <label for="max_speed" class="block text-sm font-medium text-gray-700 mb-1">Максимальная скорость (км/ч)</label>
                    <input type="number" min="50" max="450" name="max_speed" id="max_speed"
                           value="{{ old('max_speed') }}" 
                           class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2" required>
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Описание</label>
                    <textarea name="description" id="description" rows="4" maxlength="1000"
                              class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2" required>{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="model_folder" class="block text-sm font-medium text-gray-700 mb-1">3D модель (ZIP)</label>
                    <div class="mt-1 flex items-center">
                        <label for="model_folder" class="cursor-pointer inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-4 w-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Загрузить файл
                        </label>
                        <span class="ml-3 text-sm text-gray-500" id="file-name">Файл не выбран</span>
                        <input type="file" name="model_folder" id="model_folder" class="sr-only" accept=".zip">
                    </div>
                </div>
            </div>


            <div class="mb-6 p-4 border border-gray-200 rounded-lg">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Цвета</h3>
                
                <!-- Существующие цвета -->
                <div class="mb-6">
                    <label for="colors" class="block text-sm font-medium text-gray-700 mb-2">Существующие цвета</label>
                    <select name="colors[]" id="colors" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2" multiple>
                        @foreach ($colors as $color)
                            <option value="{{ $color->id }}" {{ is_array(old('colors')) && in_array($color->id, old('colors', [])) ? 'selected' : '' }}>
                                {{ $color->name }} ({{ $color->hex_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Новые цвета -->
                <div class="space-y-3" id="new-colors-container">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Добавить новые цвета</h4>
                    <div id="new-colors-fields">
                        <!-- Поле по умолчанию -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Название</label>
                                <input type="text" name="new_colors[0][name]" placeholder="Название" maxlength="50"
                                    class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">HEX код</label>
                                <input type="text" name="new_colors[0][hex]" placeholder="#FF5733" maxlength="7" minlength="7"
                                    class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2">
                            </div>
                            <div class="flex items-end">
                                <button type="button" onclick="addNewColorField()"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Добавить цвет
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Добавить комплектацию
                </button>
            </div>
        </form>
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
    }).on('change', function () {
        toggleRequiredFields(); // Вызываем при каждом изменении
    });

    $('#colors').trigger('change'); // Активируем проверку при загруз

    // Отображение имени выбранного файла
    $('#model_folder').change(function() {
        const fileName = $(this).val().split('\\').pop();
        $('#file-name').text(fileName || 'Файл не выбран');
    });

    // Загрузка моделей по марке
    $('#brand-select').on('change', function () {
        const brandId = $(this).val();
        const modelSelect = $('#model-select');
        const generationSelect = $('#generation-select');

        modelSelect.empty().append('<option value="">Выберите модель</option>').prop('disabled', false);
        generationSelect.empty().append('<option value="">Выберите поколение</option>').prop('disabled', true);

        $.get(`/api/brand/${brandId}/models`, function (models) {
            models.forEach(model => {
                modelSelect.append(`<option value="${model.id}">${model.name}</option>`);
            });
            modelSelect.trigger('change');
        });
    });

    // Загрузка поколений по модели
    $('#model-select').on('change', function () {
        const modelId = $(this).val();
        const generationSelect = $('#generation-select');

        generationSelect.empty().append('<option value="">Выберите поколение</option>').prop('disabled', false);

        $.get(`/api/model/${modelId}/generations`, function (generations) {
            generations.forEach(gen => {
                generationSelect.append(`<option value="${gen.id}">${gen.name}</option>`);
            });
        });
    });
});
</script>

<script>
    let colorIndex = 0;

    function addNewColorField() {
        colorIndex++;
        const container = document.getElementById('new-colors-fields');
        const div = document.createElement('div');
        div.className = 'grid grid-cols-1 md:grid-cols-3 gap-3 mt-3';
        div.setAttribute('data-color-field', '');
        div.innerHTML = `
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Название</label>
                <input type="text" name="new_colors[${colorIndex}][name]" placeholder="Название" maxlength="50"
                       class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">HEX код</label>
                <input type="text" name="new_colors[${colorIndex}][hex]" placeholder="#FF5733" maxlength="7" minlength="7"
                       class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-3 py-2">
            </div>
            <div class="flex items-end">
                <button type="button" onclick="removeColorField(this)"
                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                    Удалить
                </button>
            </div>
        `;
        container.appendChild(div);
        toggleRequiredFields(); // Обновляем состояние required
    }

    function removeColorField(button) {
        button.closest('[data-color-field]').remove();
        toggleRequiredFields(); // Обновляем состояние required
    }

    function toggleRequiredFields() {
        const select = document.getElementById('colors');
        if (!select) return;

        const selectedOptions = Array.from(select.selectedOptions);
        const isNewColorsRequired = selectedOptions.length === 0;

        // Обрабатываем все инпуты новых цветов
        document.querySelectorAll('input[name*="new_colors"]').forEach(input => {
            if (isNewColorsRequired) {
                input.setAttribute('required', 'required');
            } else {
                input.removeAttribute('required');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('colors');
        if (select) {
            toggleRequiredFields(); // Проверка при загрузке
        }
    });
</script>
@endsection