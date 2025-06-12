@extends('layouts.user')

@section('content')
<style>
 /* === Select2 Dark Theme === */
.dark .select2-container--default .select2-selection--single {
    background-color: #3C3C3C !important;
    border: 1px solid #4B5563 !important;
    color: white !important;
    height: 42px !important;
    border-radius: 0.375rem !important;
    padding: 0.25rem 0.5rem !important;
    transition: all 0.2s !important;
}

.dark .select2-container--default .select2-selection__arrow {
    top: 8px !important;
}

.dark .select2-container .select2-results__options {
    background-color: #1f2937 !important;
    color: white !important;
}

.dark .select2-container .select2-results__option--highlighted[aria-selected] {
    background-color: #4B5563 !important;
}

.dark .select2-container .select2-search__field {
    background-color: #3C3C3C !important;
    border: 1px solid #4B5563 !important;
    color: white !important;
}

/* === Select2 Light Theme === */
.light .select2-container--default .select2-selection--single,
:not(.dark) .select2-container--default .select2-selection--single {
    background-color: transparent !important;
    border: 1px solid #cbd5e1 !important;
    color: #1e293b !important;
    height: 42px !important;
    border-radius: 0.375rem !important;
    padding: 0.25rem 0.5rem !important;
    transition: all 0.2s !important;
}

.light .select2-container .select2-results__options {
    background-color: #fff !important;
    color: #1e293b !important;
}

.light .select2-container .select2-results__option--highlighted[aria-selected],
:not(.dark) .select2-container .select2-results__option--highlighted[aria-selected] {
    background-color: #e2e8f0 !important;
}

.light .select2-container .select2-search__field {
    background-color: #fff !important;
    border: 1px solid #cbd5e1 !important;
    color: #1e293b !important;
}

/* === Отключаем hover в выпадающем списке Select2 === */

.select2-container--default .select2-results__option:hover,
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: transparent !important;
    color: inherit !important;
}

/* Если хотите полностью убрать все эффекты выделения */
.select2-container--default .select2-results__option--highlighted[aria-selected]:hover {
    background-color: transparent !important;
}
</style>
<!-- Глобальный поиск -->
<section class="p-6 bg-[#1C1B21]">
  <div class="container mx-auto">
    <form action="{{ route('catalog') }}" method="GET" class="flex flex-col md:flex-row gap-4">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Поиск по марке, модели, поколению..." 
             class="flex-1 px-6 py-3 rounded-lg border border-gray-700 bg-[#2A2A2A] text-white focus:outline-none 
             focus:ring-2 focus:ring-purple-500 transition">
      <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 rounded-lg text-[#ffffff]
       transition flex items-center justify-center">
        <i class="fas fa-search mr-2"></i> Найти
      </button>
      @if (request('search'))
        <a href="{{ route('catalog') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-lg transition 
        flex items-center justify-center">
          <i class="fas fa-times mr-2"></i> Сбросить
        </a>
      @endif
    </form>
  </div>
</section>


<section class="p-6 bg-[#1C1B21]">
  <div class="container mx-auto">
    <div class="flex flex-col lg:flex-row gap-6">
      <!-- Фильтры -->
      <div class="lg:w-1/4">
        <div class="filter-sidebar bg-[#2A2A2A] rounded-lg p-6">
          <h3 class="text-xl font-bold mb-4 flex items-center">
            <i class="fas fa-filter mr-2 text-purple-400"></i> Фильтры
          </h3>
          
          <form method="GET" action="{{ route('catalog') }}">
            <input type="hidden" name="search" value="{{ request('search') }}">
            
            <div class="space-y-6">
              <!-- Марка -->
              <div>
                <label class="block text-sm font-medium mb-2">Марка</label>
                <select name="brand" id="brand-select"
                    class="w-full px-4 py-2 rounded-md border border-gray-700 bg-gray-800 text-white dark:bg-gray-800 dark:text-white light:bg-white light:text-gray-800 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="">Все марки</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
              

            <div>
              <label class="block text-sm font-medium mb-2">Модель</label>
              <select name="model" id="model-select" class="w-full px-4 py-2 rounded-md border border-gray-700 bg-gray-800 text-white dark:bg-gray-800 dark:text-white light:bg-white light:text-gray-800 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                  <option value="">Все модели</option>
                  @if(request('model') && $selectedModel = \App\Models\CarModel::find(request('model')))
                      <option value="{{ $selectedModel->id }}" selected>{{ $selectedModel->name }}</option>
                  @endif
              </select>
            </div>

            <!-- Поколение -->
            <div>
              <label class="block text-sm font-medium mb-2">Поколение</label>
              <select name="generation" id="generation-select" class="w-full px-4 py-2 rounded-md border border-gray-700 bg-gray-800 text-white dark:bg-gray-800 dark:text-white light:bg-white light:text-gray-800 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                  <option value="">Все поколения</option>
                  @if(request('generation') && $selectedGeneration = \App\Models\Generation::find(request('generation')))
                      <option value="{{ $selectedGeneration->id }}" selected>
                          {{ $selectedGeneration->name }} ({{ $selectedGeneration->year_from }})
                      </option>
                  @endif
              </select>
            </div>

            <!-- Комплектация -->
            <div>
              <label class="block text-sm font-medium mb-2">Комплектация</label>
              <select name="equipment" id="equipment-select" class="w-full px-4 py-2 rounded-md border border-gray-700 bg-gray-800 text-white dark:bg-gray-800 dark:text-white light:bg-white light:text-gray-800 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                  <option value="">Все комплектации</option>
                  @if(request('equipment') && $selectedEquipment = \App\Models\Equipment::find(request('equipment')))
                      <option value="{{ $selectedEquipment->id }}" selected>{{ $selectedEquipment->engine_name }}</option>
                  @endif
              </select>
            </div>

              <!-- Тип кузова -->
              <div>
                <label class="block text-sm font-medium mb-2">Тип кузова</label>
                <select name="body_type" class="w-full px-4 py-2 rounded-md border border-gray-700 bg-[#3C3C3C] text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                  <option value="">Все типы</option>
                  @foreach($bodyTypes as $type)
                    <option value="{{ $type->id }}" {{ request('body_type') == $type->id ? 'selected' : '' }}>
                      {{ $type->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <!-- Тип трансмиссии -->
              <div>
                <label class="block text-sm font-medium mb-2">Коробка передач</label>
                <select name="transmission_type" class="w-full px-4 py-2 rounded-md border border-gray-700 bg-[#3C3C3C] text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                  <option value="">Все типы</option>
                  @foreach($transmissionTypes as $type)
                    <option value="{{ $type->id }}" {{ request('transmission_type') == $type->id ? 'selected' : '' }}>
                      {{ $type->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <!-- Тип двигателя -->
              <div>
                <label class="block text-sm font-medium mb-2">Тип двигателя</label>
                <select name="engine_type" class="w-full px-4 py-2 rounded-md border border-gray-700 bg-[#3C3C3C] text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                  <option value="">Все типы</option>
                  @foreach($engineTypes as $type)
                    <option value="{{ $type->id }}" {{ request('engine_type') == $type->id ? 'selected' : '' }}>
                      {{ $type->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <!-- Тип привода -->
              <div>
                <label class="block text-sm font-medium mb-2">Привод</label>
                <select name="drive_type" class="w-full px-4 py-2 rounded-md border border-gray-700 bg-[#3C3C3C] text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                  <option value="">Все типы</option>
                  @foreach($driveTypes as $type)
                    <option value="{{ $type->id }}" {{ request('drive_type') == $type->id ? 'selected' : '' }}>
                      {{ $type->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <!-- Цена -->
              <div>
                <label class="block text-sm font-medium mb-2">Цена, ₽</label>
                <div class="grid grid-cols-2 gap-2">
                  <input type="number" name="min_price" placeholder="От" value="{{ request('min_price') }}"
                         class="w-full px-3 py-2 rounded-md border border-gray-700 bg-[#3C3C3C] text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                  <input type="number" name="max_price" placeholder="До" value="{{ request('max_price') }}"
                         class="w-full px-3 py-2 rounded-md border border-gray-700 bg-[#3C3C3C] text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                </div>
              </div>

              <!-- Пробег -->
              <div>
                <label class="block text-sm font-medium mb-2">Пробег, км</label>
                <div class="grid grid-cols-2 gap-2">
                  <input type="number" name="min_mileage" placeholder="От" value="{{ request('min_mileage') }}"
                         class="w-full px-3 py-2 rounded-md border border-gray-700 bg-[#3C3C3C] text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                  <input type="number" name="max_mileage" placeholder="До" value="{{ request('max_mileage') }}"
                         class="w-full px-3 py-2 rounded-md border border-gray-700 bg-[#3C3C3C] text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                </div>
              </div>

              <!-- Год выпуска -->
              <div>
                <label class="block text-sm font-medium mb-2">Год выпуска</label>
                <div class="grid grid-cols-2 gap-2">
                  <input type="number" name="min_year" placeholder="От" value="{{ request('min_year') }}"
                         class="w-full px-3 py-2 rounded-md border border-gray-700 bg-[#3C3C3C] text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                  <input type="number" name="max_year" placeholder="До" value="{{ request('max_year') }}"
                         class="w-full px-3 py-2 rounded-md border border-gray-700 bg-[#3C3C3C] text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                </div>
              </div>

              <!-- Объем двигателя -->
              <div>
                <label class="block text-sm font-medium mb-2">Объем двигателя, л</label>
                <div class="grid grid-cols-2 gap-2">
                  <input type="number" step="0.1" name="min_engine_volume" placeholder="От" value="{{ request('min_engine_volume') }}"
                         class="w-full px-3 py-2 rounded-md border border-gray-700 bg-[#3C3C3C] text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                  <input type="number" step="0.1" name="max_engine_volume" placeholder="До" value="{{ request('max_engine_volume') }}"
                         class="w-full px-3 py-2 rounded-md border border-gray-700 bg-[#3C3C3C] text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                </div>
              </div>

              <!-- Сортировка -->
              <div>
                <label class="block text-sm font-medium mb-2">Сортировать по:</label>
                <select name="sort" class="w-full px-4 py-2 rounded-md border border-gray-700 bg-[#3C3C3C] text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                  <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Цене</option>
                  <option value="mileage" {{ request('sort') == 'mileage' ? 'selected' : '' }}>Пробегу</option>
                  <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>Году выпуска</option>
                  <option value="engine_volume" {{ request('sort') == 'engine_volume' ? 'selected' : '' }}>Объему двигателя</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium mb-2">Направление:</label>
                <select name="direction" class="w-full px-4 py-2 rounded-md border border-gray-700 bg-[#3C3C3C] text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                  <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>По возрастанию</option>
                  <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>По убыванию</option>
                </select>
              </div>

              <div class="pt-2">
                <button type="submit" class="w-full px-4 py-3 bg-purple-600 text-[#ffffff] hover:bg-purple-700 rounded-lg transition flex items-center justify-center">
                  <i class="fas fa-check-circle mr-2"></i> Применить фильтры
                </button>
              </div>
              
              <div>
                <a href="{{ route('catalog') }}" class="w-full px-4 py-3 bg-gray-700 hover:bg-gray-600 rounded-lg transition flex items-center justify-center">
                  <i class="fas fa-redo mr-2"></i> Сбросить фильтры
                </a>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Список автомобилей -->
      <div class="lg:w-3/4">
        <div class="bg-[#2A2A2A] rounded-lg p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
          <h2 class="text-2xl font-bold">
            <i class="fas fa-car mr-2 text-purple-400"></i> Найдено автомобилей: {{ $cars->total() }}
          </h2>

          <div class="flex flex-wrap items-center space-x-0 sm:space-x-2 space-y-2 sm:space-y-0">
            <span class="text-sm text-gray-400 mr-2">Сортировка:</span>
            
            <select id="sort-select" class="px-3 py-1 rounded-md border border-gray-700 bg-[#3C3C3C] text-white text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent transition w-full sm:w-auto mb-2 sm:mb-0">
              <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>По цене</option>
              <option value="mileage" {{ request('sort') == 'mileage' ? 'selected' : '' }}>По пробегу</option>
              <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>По году</option>
            </select>

            <select id="direction-select" class="px-3 py-1 rounded-md border border-gray-700 bg-[#3C3C3C] text-white text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent transition w-full sm:w-auto">
              <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>По возрастанию</option>
              <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>По убыванию</option>
            </select>
          </div>
        </div>
          
          @if($cars->isEmpty())
            <div class="text-center py-12">
              <i class="fas fa-car-crash text-4xl text-gray-500 mb-4"></i>
              <h3 class="text-xl font-semibold">Автомобили не найдены</h3>
              <p class="text-gray-400 mt-2">Попробуйте изменить параметры поиска</p>
              <a href="{{ route('catalog') }}" class="inline-block mt-4 px-6 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg transition">
                Сбросить фильтры
              </a>
            </div>
          @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              @foreach($cars as $car)
                <div class="car-item bg-[#3C3C3C] rounded-lg overflow-hidden transition-all duration-300">
                  <div class="w-full h-48 bg-gray-800 flex items-center justify-center relative overflow-hidden">
                    @php
                    $mainImage = $car->images->where('is_main', 1)->first();
                @endphp
                
                @if($mainImage)
                    <img src="{{ asset('storage/' . $mainImage->path) }}" 
                         alt="{{ $car->equipment->generation->carModel->brand->name }}" 
                         class="h-full w-full object-cover transition-transform duration-500 hover:scale-105">
                @else
                    <!-- Резервное изображение или сообщение -->
                    <img src="{{ asset('images/no-image.png') }}" 
                         alt="Нет изображения" 
                         class="h-full w-full object-cover">
                @endif
                    <div class="absolute top-2 right-2 bg-[#2A2A2A]  bg-opacity-50 text-xs px-2 py-1 rounded">
                      {{ $car->branch->name }}
                    </div>
                  </div>
                  <div class="p-4">
                    <h3 class="text-lg font-semibold">
                      {{ $car->equipment->generation->carModel->brand->name }}
                      {{ $car->equipment->generation->carModel->name }}
                    </h3>
                    <p class="text-sm text-gray-300 mt-1">
                      {{ $car->equipment->generation->name }} ({{ $car->equipment->generation->year_from }})
                    </p>
                    <p class="text-sm text-gray-400 mt-1">
                      {{ $car->equipment->engine_name }} {{ $car->equipment->engine_volume }}L
                      {{ $car->equipment->transmissionType->name }}, {{ $car->equipment->driveType->name }}
                    </p>
                    <div class="mt-3 flex justify-between items-center">
                      <div>
                        <p class="text-sm text-gray-400">
                          <i class="fas fa-tachometer-alt mr-1"></i> {{ number_format($car->mileage, 0, '', ' ') }} км
                        </p>
                      </div>
                      <p class="text-purple-400 font-bold">
                        {{ number_format($car->price, 0, '', ' ') }} ₽
                      </p>
                    </div>
                    <a href="{{ route('cars.show', $car->id) }}" 
                       class="mt-4 block w-full text-center bg-purple-600 hover:bg-purple-700 px-4 py-2 text-[#ffffff] rounded transition flex items-center justify-center">
                      <i class="fas fa-info-circle mr-2"></i> Подробнее
                    </a>
                  </div>
                </div>
              @endforeach
            </div>

            <!-- Пагинация -->
            <div class="mt-6">
              {{ $cars->appends(request()->query())->links('components.catalog-pagination') }}
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {

    
    // Быстрая сортировка
    const sortSelect = document.getElementById('sort-select');
    const directionSelect = document.getElementById('direction-select');
    
    if (sortSelect && directionSelect) {
      [sortSelect, directionSelect].forEach(select => {
        select.addEventListener('change', function() {
          const url = new URL(window.location.href);
          url.searchParams.set('sort', sortSelect.value);
          url.searchParams.set('direction', directionSelect.value);
          window.location.href = url.toString();
        });
      });
    }
  });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
 document.addEventListener('DOMContentLoaded', function () {
    // === Инициализация Select2 для всех полей ===
    $('#brand-select').select2({
        placeholder: "Выберите марку",
        allowClear: true,
        width: '100%'
    });

    $('#model-select').select2({
        placeholder: "Выберите модель",
        allowClear: true,
        ajax: {
            url: '/catalog/api/models',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    brand_id: $('#brand-select').val()
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(item => ({
                        id: item.id,
                        text: item.name
                    }))
                };
            },
            cache: true
        }
    });

    $('#generation-select').select2({
        placeholder: "Выберите поколение",
        allowClear: true,
        ajax: {
            url: '/catalog/api/generations',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    model_id: $('#model-select').val()
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $('#equipment-select').select2({
        placeholder: "Выберите комплектацию",
        allowClear: true,
        ajax: {
            url: '/catalog/api/equipments',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    generation_id: $('#generation-select').val()
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    // === Блокировка дочерних селектов при отсутствии родителя ===
    function toggleDependentSelects() {
        const brand = $('#brand-select').val();
        const model = $('#model-select').val();

        if (!brand) {
            $('#model-select, #generation-select, #equipment-select').prop('disabled', true);
        } else {
            $('#model-select').prop('disabled', false);
            if (model) {
                $('#generation-select').prop('disabled', false);
                if ($('#generation-select').val()) {
                    $('#equipment-select').prop('disabled', false);
                } else {
                    $('#equipment-select').prop('disabled', true);
                }
            } else {
                $('#generation-select, #equipment-select').prop('disabled', true);
            }
        }
    }

    toggleDependentSelects();

    $('#brand-select, #model-select, #generation-select').on('change', function () {
        toggleDependentSelects();
    });

    // === Каскадное обновление при изменении марки ===
    $('#brand-select').change(function () {
        $('#model-select').val(null).trigger('change');
        $('#generation-select').val(null).trigger('change');
        $('#equipment-select').val(null).trigger('change');
    });

    $('#model-select').change(function () {
        $('#generation-select').val(null).trigger('change');
        $('#equipment-select').val(null).trigger('change');
    });

    $('#generation-select').change(function () {
        $('#equipment-select').val(null).trigger('change');
    });

    // === Восстановление выбранных значений при загрузке ===
    @if(request('model') && isset($selectedModel))
        $('#model-select').html('<option value="{{ $selectedModel->id }}" selected>{{ $selectedModel->name }}</option>');
    @endif

    @if(request('generation') && isset($selectedGeneration))
        $('#generation-select').html('<option value="{{ $selectedGeneration->id }}" selected>{{ $selectedGeneration->name }} ({{ $selectedGeneration->year_from }})</option>');
    @endif

    @if(request('equipment') && isset($selectedEquipment))
        $('#equipment-select').html('<option value="{{ $selectedEquipment->id }}" selected>{{ $selectedEquipment->name }}</option>');
    @endif
});
</script>
@endsection



