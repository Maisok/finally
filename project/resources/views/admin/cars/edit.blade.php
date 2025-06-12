@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Редактировать автомобиль</h1>

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

        <form action="{{ route('admin.cars.update', $car) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Основные поля -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200 mb-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-sm sm:text-base font-medium text-gray-700">Основная информация</h3>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <!-- Марка -->
                        <div>
                            <label for="brand-select" class="block text-sm font-medium text-gray-700 mb-1">Марка</label>
                            <select id="brand-select" class="select2">
                                <option value="">Выберите марку</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ optional(optional($car->equipment->generation)->carModel->brand)->id == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    
                        <!-- Модель -->
                        <div>
                            <label for="model-select" class="block text-sm font-medium text-gray-700 mb-1">Модель</label>
                            <select id="model-select" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2" required>
                                <option value="">Выберите модель</option>
                            </select>
                        </div>
                    
                        <!-- Поколение -->
                        <div>
                            <label for="generation-select" class="block text-sm font-medium text-gray-700 mb-1">Поколение</label>
                            <select id="generation-select" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2" required>
                                <option value="">Выберите поколение</option>
                            </select>
                        </div>
                    
                        <!-- Комплектация -->
                        <div>
                            <label for="equipment-select" class="block text-sm font-medium text-gray-700 mb-1">Комплектация</label>
                            <select id="equipment-select" name="equipment_id" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2" required>
                                <option value="">Выберите комплектацию</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <!-- VIN -->
                        <div>
                            <label for="vin" class="block text-sm font-medium text-gray-700 mb-1">VIN</label>
                            <input type="text" name="vin" id="vin"
                                   value="{{ old('vin', $car->vin) }}"
                                   maxlength="17" required
                                   class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>

                        <!-- Цена -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Цена</label>
                            <input type="number" name="price" id="price"
                                   value="{{ old('price', $car->price) }}"
                                   min="0" required
                                   class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>

                        <!-- Пробег -->
                        <div>
                            <label for="mileage" class="block text-sm font-medium text-gray-700 mb-1">Пробег (км)</label>
                            <input type="number" name="mileage" id="mileage"
                                   value="{{ old('mileage', $car->mileage) }}"
                                   min="0" max="9999999" required
                                   class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                    </div>

                    <!-- Цвет -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="color-select" class="block text-sm font-medium text-gray-700 mb-1">Существующий цвет</label>
                            <select id="color-select" name="color_id" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2">
                                <option value="">Выберите цвет</option>
                                @foreach ($colors as $color)
                                    <option value="{{ $color->id }}" {{ old('color_id', $car->color_id) == $color->id ? 'selected' : '' }}>
                                        {{ $color->name }} ({{ $color->hex_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Или укажите свой</label>
                            <div class="flex flex-col space-y-2">
                                <input type="text" name="custom_color_name" id="custom_color_name"
                                       value="{{ old('custom_color_name', $car->custom_color_name) }}"
                                       placeholder="Название цвета" maxlength="50"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500">
                                <input type="text" name="custom_color_hex" id="custom_color_hex"
                                       value="{{ old('custom_color_hex', $car->custom_color_hex) }}"
                                       placeholder="#FF5733" maxlength="7"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Филиал -->
                    <div class="mb-4">
                        <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-1">Филиал</label>
                        <select name="branch_id" id="branch_id" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm select2" required>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id', $car->branch_id) == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Описание -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Описание</label>
                        <textarea name="description" id="description" rows="4" maxlength="1000"
                                  class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('description', $car->description) }}</textarea>
                    </div>

                    <!-- Статус -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                        
                        <!-- Скрытое поле для случая "не продан" -->
                        <input type="hidden" name="is_sold" value="0">
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="is_sold" id="is_sold" value="1"
                                   {{ old('is_sold', $car->is_sold) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            
                            <label for="is_sold" class="ml-2 block text-sm text-gray-700">Автомобиль продан</label>
                        </div>
                    
                        @error('is_sold')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Изображения -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200 mb-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-sm sm:text-base font-medium text-gray-700">Изображения автомобиля</h3>
                </div>
                <div class="p-4 sm:p-6">
                    <!-- Загрузка новых фото -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Загрузить новые фото</label>
                        <div class="flex items-center">
                            <input type="file" name="new_images[]" multiple accept="image/*" id="new_images"
                                   class="block w-full text-sm text-gray-500
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded-md file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-blue-50 file:text-blue-700
                                   hover:file:bg-blue-100">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Можно выбрать несколько файлов</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Текущие изображения</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @foreach ($car->images as $image)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $image->path) }}" 
                                     alt="Фото авто"
                                     class="w-full h-32 object-cover rounded-lg border {{ $image->is_main ? 'border-blue-500 ring-2 ring-blue-500' : 'border-gray-200' }}">
                        
                                <!-- Радиокнопка для главного фото -->
                                <div class="absolute top-2 left-2 z-10">
                                    <input type="radio" name="main_image_id" value="{{ $image->id }}"
                                           {{ $image->is_main ? 'checked' : '' }}
                                           id="main_{{ $image->id }}" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 cursor-pointer">
                                   
                                </div>
                        
                                <!-- Чекбокс для удаления -->
                                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity bg-black bg-opacity-70 px-2 py-1 rounded z-10">
                                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" id="delete_image_{{ $image->id }}"
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded cursor-pointer">
                                    <label for="delete_image_{{ $image->id }}" class="cursor-pointer ml-1 text-sm text-white">Удалить</label>
                                </div>
                        
                                <!-- Отключаем label от перекрытия радиокнопки -->
                                <label for="delete_image_{{ $image->id }}" class="absolute inset-0 pointer-events-none"></label>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Кнопки -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.cars.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Назад
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Сохранить изменения
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
        // Инициализируем Select2
        $('.select2').select2({
            placeholder: "Выберите значение",
            allowClear: true,
            width: '100%'
        });
    
        const initialBrandId = "{{ $brandId }}";
        const initialModelId = "{{ $modelId }}";
        const initialGenerationId = "{{ $generationId }}";
        const initialEquipmentId = "{{ $equipmentId }}";
    
        // === Функции загрузки через AJAX ===
        function loadModels(brandId, selectedModelId = null) {
            const modelSelect = $('#model-select');
            modelSelect.empty().append('<option value="">Выберите модель</option>').prop('disabled', false);
    
            if (!brandId) return;
    
            $.get(`/api/brand/${brandId}/models`, function (data) {
                modelSelect.empty().append('<option value="">Выберите модель</option>');
                data.forEach(model => {
                    modelSelect.append(`<option value="${model.id}">${model.name}</option>`);
                });
    
                if (selectedModelId) {
                    modelSelect.val(selectedModelId).trigger('change.select2');
                }
            });
        }
    
        function loadGenerations(modelId, selectedGenId = null) {
            const genSelect = $('#generation-select');
            genSelect.empty().append('<option value="">Выберите поколение</option>').prop('disabled', false);
    
            if (!modelId) return;
    
            $.get(`/api/model/${modelId}/generations`, function (data) {
                genSelect.empty().append('<option value="">Выберите поколение</option>');
                data.forEach(gen => {
                    genSelect.append(`<option value="${gen.id}">${gen.name}</option>`);
                });
    
                if (selectedGenId) {
                    genSelect.val(selectedGenId).trigger('change.select2');
                }
            });
        }
    
        function loadEquipments(generationId, selectedEqId = null) {
            const eqSelect = $('#equipment-select');
            eqSelect.empty().append('<option value="">Выберите комплектацию</option>').prop('disabled', false);
    
            if (!generationId) return;
    
            $.get(`/api/generation/${generationId}/equipments`, function (data) {
                eqSelect.empty().append('<option value="">Выберите комплектацию</option>');
                data.forEach(eq => {
                    eqSelect.append(`<option value="${eq.id}">${eq.name}</option>`);
                });
    
                if (selectedEqId) {
                    eqSelect.val(selectedEqId).trigger('change.select2');
                }
            });
        }
    
        // === Предзагрузка данных при открытии формы ===
        if (initialBrandId) {
            $('#brand-select').val(initialBrandId).trigger('change.select2');
            loadModels(initialBrandId, initialModelId);
        }
    
        if (initialModelId && initialBrandId) {
            setTimeout(() => {
                $('#model-select').val(initialModelId).trigger('change.select2');
                loadGenerations(initialModelId, initialGenerationId);
            }, 500);
        }
    
        if (initialGenerationId && initialModelId) {
            setTimeout(() => {
                $('#generation-select').val(initialGenerationId).trigger('change.select2');
                loadEquipments(initialGenerationId, initialEquipmentId);
            }, 1000);
        }
    
        if (initialEquipmentId && initialGenerationId) {
            setTimeout(() => {
                $('#equipment-select').val(initialEquipmentId).trigger('change.select2');
            }, 1500);
        }
    
        // === Обработчики событий ===
        $('#brand-select').on('change', function () {
            const brandId = $(this).val();
            loadModels(brandId);
        });
    
        $('#model-select').on('change', function () {
            const modelId = $(this).val();
            loadGenerations(modelId);
        });
    
        $('#generation-select').on('change', function () {
            const generationId = $(this).val();
            loadEquipments(generationId);
        });
    });
    </script>

<script>
$(document).ready(function () {
 
    // Логика для цветов
    const colorSelect = $('#color-select');
    const customNameInput = $('#custom_color_name');
    const customHexInput = $('#custom_color_hex');

    function disableCustomFields(disabled) {
        customNameInput.prop('disabled', disabled);
        customHexInput.prop('disabled', disabled);
    }

    function disableColorSelect(disabled) {
        colorSelect.prop('disabled', disabled);
    }

    // При выборе цвета из списка
    colorSelect.on('change', function () {
        if ($(this).val()) {
            disableCustomFields(true);
            customNameInput.val('');
            customHexInput.val('');
        } else {
            disableCustomFields(false);
        }
    });

    // При вводе своего названия или HEX кода
    customNameInput.on('input', function () {
        if ($(this).val() || customHexInput.val()) {
            disableColorSelect(true);
            colorSelect.val('').trigger('change');
        } else {
            disableColorSelect(false);
        }
    });

    customHexInput.on('input', function () {
        if ($(this).val() || customNameInput.val()) {
            disableColorSelect(true);
            colorSelect.val('').trigger('change');
        } else {
            disableColorSelect(false);
        }
    });

    // Проверка при загрузке
    if (colorSelect.val()) {
        disableCustomFields(true);
    } else if (customNameInput.val() || customHexInput.val()) {
        disableColorSelect(true);
    }
});
</script>
<script>
    window.addEventListener('DOMContentLoaded', function () {
        const imageInput = document.getElementById('new_images');
        if (!imageInput) {
            console.warn('Поле new_images не найдено');
            return;
        }
    
        imageInput.addEventListener('change', function (event) {
            const files = Array.from(event.target.files);
            const existingCount = parseInt("{{ $car->images->count() }}", 10);
            const maxImages = 20;
            const remainingSlots = maxImages - existingCount;
    
            // Если попытка загрузить больше, чем осталось слотов
            if (files.length > remainingSlots) {
                alert(`Можно загрузить максимум ${remainingSlots} фото`);
    
                // Оставляем только нужное количество
                const allowedFiles = files.slice(0, remainingSlots);
    
                // Создаем новый DataTransfer для замены files
                const dt = new DataTransfer();
                allowedFiles.forEach(file => dt.items.add(file));
    
                // Заменяем текущий список файлов
                imageInput.files = dt.files;
    
                // Показываем сообщение пользователю
                let errorDiv = document.getElementById('image-limit-warning');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.id = 'image-limit-warning';
                    errorDiv.className = 'text-red-600 text-sm mt-1';
                    errorDiv.innerText = `Загружено более ${remainingSlots} фото. Сохранены только первые ${remainingSlots}.`;
                    imageInput.parentNode.appendChild(errorDiv);
                } else {
                    errorDiv.innerText = `Загружено более ${remainingSlots} фото. Сохранены только первые ${remainingSlots}.`;
                    errorDiv.style.display = 'block';
                }
            } else {
                // Если всё нормально — убираем ошибку, если она была
                let errorDiv = document.getElementById('image-limit-warning');
                if (errorDiv) {
                    errorDiv.style.display = 'none';
                }
            }
        });
    });
    </script>
@endsection