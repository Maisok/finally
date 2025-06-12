@extends('layouts.admin')
@section('title', 'Структура автомобилей')

@section('content')
<div class="min-h-screen bg-gray-50">
  <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
      <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Управление автомобильной структурой</h1>
      <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-600">Управление марками, моделями и поколениями автомобилей</p>
    </div>

    <!-- Error Messages -->
    <div class="space-y-3 mb-6">
      @if ($errors->any())
        <div class="p-3 sm:p-4 bg-red-50 border-l-4 border-red-500 rounded shadow-sm">
          <ul class="space-y-1 text-sm text-red-700">
            @foreach ($errors->all() as $error)
              <li class="flex items-start">
                <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd"/>
                </svg>
                <span>{{ $error }}</span>
              </li>
            @endforeach
          </ul>
        </div>
      @endif

      @if(session('error'))
        <div class="p-3 sm:p-4 bg-red-50 border-l-4 border-red-500 rounded shadow-sm flex items-start">
          <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                  clip-rule="evenodd"/>
          </svg>
          <span class="text-sm text-red-700">{{ session('error') }}</span>
        </div>
      @endif

      @if(session('success'))
        <div class="p-3 sm:p-4 bg-green-50 border-l-4 border-green-500 rounded shadow-sm flex items-start">
          <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                  clip-rule="evenodd"/>
          </svg>
          <span class="text-sm text-green-700">{{ session('success') }}</span>
        </div>
      @endif
    </div>

    <!-- Accordion for Forms -->
    <div class="mb-6 space-y-4">
      <!-- Add Brand Accordion -->
      <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
        <button class="accordion-toggle w-full px-4 py-3 sm:px-6 sm:py-4 text-left font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out flex justify-between items-center">
          <span>Добавить марку</span>
          <svg class="h-5 w-5 text-gray-500 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div class="accordion-content hidden px-4 sm:px-6 pb-4 sm:pb-6 pt-2">
          <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="space-y-3">
              <div>
                <label for="brand-name" class="block text-sm font-medium text-gray-700 mb-1">Название марки</label>
                <input type="text"
                       name="name"
                       id="brand-name"
                       value="{{ old('name') }}"
                       class="block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md"
                       required maxlength="50">
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label for="brand-logo" class="block text-sm font-medium text-gray-700 mb-1">Логотип</label>
                <div class="mt-1 flex items-center">
                  <label for="brand-logo" class="cursor-pointer inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-4 w-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                            clip-rule="evenodd"/>
                    </svg>
                    Загрузить
                  </label>
                  <span class="ml-3 text-sm text-gray-500" id="file-name">Файл не выбран</span>
                  <input type="file" name="logo" id="brand-logo" class="sr-only" accept="image/*">
                </div>
                @error('logo')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
            </div>
            <div class="pt-2">
              <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Сохранить
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Add Model Accordion -->
      <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
        <button class="accordion-toggle w-full px-4 py-3 sm:px-6 sm:py-4 text-left font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out flex justify-between items-center">
          <span>Добавить модель</span>
          <svg class="h-5 w-5 text-gray-500 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div class="accordion-content hidden px-4 sm:px-6 pb-4 sm:pb-6 pt-2">
          <form action="{{ route('models.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-3">
              <div>
                <label for="model-brand_id" class="block text-sm font-medium text-gray-700 mb-1">Марка</label>
                <select name="brand_id" id="model-brand_id"
                        class="block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                  <option value="">Выберите марку</option>
                  @foreach (\App\Models\Brand::all() as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                      {{ $brand->name }}
                    </option>
                  @endforeach
                </select>
                @error('brand_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label for="model-name" class="block text-sm font-medium text-gray-700 mb-1">Название модели</label>
                <input type="text"
                       name="name"
                       id="model-name"
                       value="{{ old('name') }}"
                       class="block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md"
                       required maxlength="50">
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
            </div>
            <div class="pt-2">
              <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Сохранить
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Add Generation Accordion -->
      <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
        <button class="accordion-toggle w-full px-4 py-3 sm:px-6 sm:py-4 text-left font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out flex justify-between items-center">
          <span>Добавить поколение</span>
          <svg class="h-5 w-5 text-gray-500 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div class="accordion-content hidden px-4 sm:px-6 pb-4 sm:pb-6 pt-2">
          <form action="{{ route('generations.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-3">
              <div>
                <label for="generation-model_id" class="block text-sm font-medium text-gray-700 mb-1">Модель</label>
                <select name="car_model_id" id="generation-model_id"
                class="block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md" required>
                  <option value="">Выберите модель</option>
                  @foreach ($allModels as $model)
                    <option value="{{ $model->id }}" {{ old('car_model_id') == $model->id ? 'selected' : '' }}>
                      {{ $model->name }} ({{ optional($model->brand)->name ?? 'Без марки' }})
                    </option>
                  @endforeach
                </select>
                @error('car_model_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label for="generation-name" class="block text-sm font-medium text-gray-700 mb-1">Название поколения</label>
                <input type="text"
                       name="name"
                       id="generation-name"
                       value="{{ old('name') }}"
                       class="block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md"
                       required maxlength="50">
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label for="year_from" class="block text-sm font-medium text-gray-700 mb-1">Год начала выпуска</label>
                  <input type="number"
                         name="year_from"
                         id="year_from"
                         value="{{ old('year_from') }}"
                        class="block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md"
                         required min="1900" max="{{ date('Y') + 10 }}">
                  @error('year_from')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                  @enderror
                </div>
                <div>
                  <label for="year_to" class="block text-sm font-medium text-gray-700 mb-1">Год окончания выпуска</label>
                  <input type="number"
                         name="year_to"
                         id="year_to"
                         value="{{ old('year_to') }}"
                        class="block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md"
                         min="1900" max="{{ date('Y') + 10 }}">
                  @error('year_to')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                  @enderror
                </div>
              </div>
            </div>
            <div class="pt-2">
              <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Сохранить
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Search Form -->
    <div class="mb-6 bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
      <div class="px-4 py-3 sm:px-6 border-b border-gray-200">
        <h3 class="text-sm sm:text-base font-medium text-gray-700">Поиск и фильтрация</h3>
      </div>
      <div class="p-4 sm:p-6">
        <form id="search-form" method="GET" action="#" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Brand -->
            <div>
              <label for="brand-select" class="block text-sm font-medium text-gray-700 mb-1">Марка</label>
              <select id="brand-select" name="brand_id"
                      class="block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                <option value="">Выберите марку</option>
                @foreach (\App\Models\Brand::all() as $brand)
                  <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
              </select>
            </div>
            <!-- Model -->
            <div>
              <label for="model-select" class="block text-sm font-medium text-gray-700 mb-1">Модель</label>
              <select id="model-select" name="model_id"
                      class="block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                      disabled>
                <option value="">Выберите модель</option>
              </select>
            </div>
            <!-- Generation -->
            <div>
              <label for="generation-select" class="block text-sm font-medium text-gray-700 mb-1">Поколение</label>
              <select id="generation-select" name="generation_id"
                      class="block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                      disabled>
                <option value="">Выберите поколение</option>
              </select>
            </div>
          </div>
          <div class="flex justify-end pt-2">
            <a href="{{ route('admin.car-structure.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              Сбросить
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- Brands List -->
    <div id="brands-container" class="space-y-3">
      @foreach ($brands as $brand)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200" data-brand-id="{{ $brand->id }}">
          <div class="px-4 py-3 sm:px-6 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-3 sm:space-x-4">
              <div class="flex-shrink-0 h-10 w-10 sm:h-12 sm:w-12">
                <img class="h-full w-full object-contain" src="{{ asset('storage/' . $brand->logo) }}" alt="{{$brand->name}}">
              </div>
              <h3 class="text-sm sm:text-base font-medium text-gray-900">{{ $brand->name }}</h3>
            </div>
            <div class="flex items-center space-x-2">
              <button onclick="toggleBrand({{ $brand->id }})" class="text-gray-500 hover:text-gray-700 p-1 rounded-full hover:bg-gray-100">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              <button onclick="editBrand({{ $brand->id }}, '{{ $brand->name }}')" class="text-yellow-500 hover:text-yellow-700 p-1 rounded-full hover:bg-yellow-50">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
              </button>
              <form action="{{ route('brands.destroy', $brand) }}" method="POST" onsubmit="return confirm('Удалить марку?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50">
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </form>
            </div>
          </div>
          <div id="models-{{ $brand->id }}" class="hidden p-4 sm:p-6 bg-gray-50">
            <div id="models-container-{{ $brand->id }}"></div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Load More Button -->
    <div id="load-more-container" class="mt-6 text-center">
      @if ($brands->hasMorePages())
        <button id="load-more-btn"
                data-page="{{ $brands->currentPage() + 1 }}"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
          Загрузить ещё марки
        </button>
      @endif
    </div>
  </div>
</div>
  <!-- Modal для редактирования -->
  <div id="edit-modal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <form id="edit-form" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modal-title">Редактирование</h3>
            
            <!-- Блок для ошибок -->
            <div id="form-errors" class="hidden bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded">
              <ul id="error-list" class="list-disc pl-5 space-y-1 text-sm text-red-700"></ul>
            </div>
  
            <!-- Brand Form -->
            <div id="form-brand" class="hidden space-y-4">
              <input type="hidden" name="brand_id" id="edit-brand-id">
              <div>
                <label for="edit-brand-name" class="block text-sm font-medium text-gray-700 mb-1">Название марки</label>
                <input type="text" name="brand_name" id="edit-brand-name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required maxlength="50">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Логотип</label>
                <div class="mt-1 flex items-center">
                  <label for="edit-brand-logo" class="cursor-pointer inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-4 w-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                    </svg>
                    Загрузить новый
                  </label>
                  <span class="ml-3 text-sm text-gray-500" id="edit-file-name">Файл не выбран</span>
                  <input type="file" name="logo" id="edit-brand-logo" class="sr-only" accept="image/*">
                </div>
              </div>
            </div>
  
            <!-- Model Form -->
            <div id="form-model" class="hidden space-y-4">
              <input type="hidden" name="model_id" id="edit-model-id">
              <div>
                <label for="edit-model-brand" class="block text-sm font-medium text-gray-700 mb-1">Марка</label>
                <select name="brand_id" id="edit-model-brand" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                  <option value="">Выберите марку</option>
                  @foreach (\App\Models\Brand::all() as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                  @endforeach
                </select>
              </div>
              <div>
                <label for="edit-model-name" class="block text-sm font-medium text-gray-700 mb-1">Название модели</label>
                <input type="text" name="model_name" id="edit-model-name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required maxlength="50">
              </div>
            </div>
  
            <!-- Generation Form -->
            <div id="form-generation" class="hidden space-y-4">
              <input type="hidden" name="generation_id" id="edit-gen-id">
              <div>
                <label for="edit-gen-model" class="block text-sm font-medium text-gray-700 mb-1">Модель</label>
                <select name="car_model_id" id="edit-gen-model" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                  <option value="">Выберите модель</option>
                  @foreach ($allModels as $model)
                    <option value="{{ $model->id }}">{{ $model->name }}</option>
                  @endforeach
                </select>
              </div>
              <div>
                <label for="edit-gen-name" class="block text-sm font-medium text-gray-700 mb-1">Название поколения</label>
                <input type="text" name="generation_name" id="edit-gen-name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required maxlength="50">
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label for="edit-gen-year-from" class="block text-sm font-medium text-gray-700 mb-1">Год начала выпуска</label>
                  <input type="number" name="year_from" id="edit-gen-year-from" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required min="1900" max="{{ date('Y') + 10 }}">
                </div>
                <div>
                  <label for="edit-gen-year-to" class="block text-sm font-medium text-gray-700 mb-1">Год окончания выпуска</label>
                  <input type="number" name="year_to" id="edit-gen-year-to" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" min="1900" max="{{ date('Y') + 10 }}" gte="year_from">
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
              Сохранить
            </button>
            <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
              Отмена
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <style>
    @media (max-width: 640px) {
      .accordion-content {
        padding-left: 1rem;
        padding-right: 1rem;
      }
    }
    
    .highlighted {
      background-color: rgba(239, 246, 255, 0.5);
      border-left-color: #3B82F6 !important;
    }
    
    .highlighted-gen {
      background-color: rgba(239, 246, 255, 0.3);
    }

  </style>
  
  <script>
    // Accordion functionality
    document.querySelectorAll('.accordion-toggle').forEach(button => {
      button.addEventListener('click', () => {
        const content = button.nextElementSibling;
        const icon = button.querySelector('svg');
        
        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
      });
    });
    
    // File input display
    document.getElementById('brand-logo').addEventListener('change', function(e) {
      const fileName = e.target.files[0] ? e.target.files[0].name : 'Файл не выбран';
      document.getElementById('file-name').textContent = fileName;
    });
    
    document.getElementById('edit-brand-logo').addEventListener('change', function(e) {
      const fileName = e.target.files[0] ? e.target.files[0].name : 'Файл не выбран';
      document.getElementById('edit-file-name').textContent = fileName;
    });
  </script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js "></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

function editBrand(id, name) {
    // Сбрасываем все required
    $('#form-brand input, #form-brand select').removeAttr('required');
    $('#form-model input, #form-model select').removeAttr('required');
    $('#form-generation input, #form-generation select').removeAttr('required');

    // Устанавливаем required только для полей марки
    $('#edit-brand-name').attr('required', 'required');

    // Отображаем форму марки
    $('#modal-title').text('Редактировать марку');
    $('#edit-brand-id').val(id);
    $('#edit-brand-name').val(name);
    $('#form-brand').removeClass('hidden');
    $('#form-model, #form-generation').addClass('hidden');
    $('#edit-form').attr('action', '/brands/' + id);
    $('#edit-modal').removeClass('hidden');
}

function editModel(id, name, brandId) {
    // Сбрасываем все required
    $('#form-brand input, #form-brand select').removeAttr('required');
    $('#form-model input, #form-model select').removeAttr('required');
    $('#form-generation input, #form-generation select').removeAttr('required');

    // Устанавливаем required только для полей модели
    $('#edit-model-brand').attr('required', 'required');
    $('#edit-model-name').attr('required', 'required');

    // Отображаем форму модели
    $('#modal-title').text('Редактировать модель');
    $('#edit-model-id').val(id);
    $('#edit-model-name').val(name);
    $('#edit-model-brand').val(brandId);
    $('#form-model').removeClass('hidden');
    $('#form-brand, #form-generation').addClass('hidden');
    $('#edit-form').attr('action', '/models/' + id);
    $('#edit-modal').removeClass('hidden');
}

function editGeneration(id, name, modelId, yearFrom, yearTo) {
    // Сбрасываем все required
    $('#form-brand input, #form-brand select').removeAttr('required');
    $('#form-model input, #form-model select').removeAttr('required');
    $('#form-generation input, #form-generation select').removeAttr('required');

    // Устанавливаем required только для полей поколения
    $('#edit-gen-model').attr('required', 'required');
    $('#edit-gen-name').attr('required', 'required');
    $('#edit-gen-year-from').attr('required', 'required');

    // Отображаем форму поколения
    $('#modal-title').text('Редактировать поколение');
    $('#edit-gen-id').val(id);
    $('#edit-gen-name').val(name);
    $('#edit-gen-model').val(modelId);
    $('#edit-gen-year-from').val(yearFrom);
    $('#edit-gen-year-to').val(yearTo || '');

    $('#form-generation').removeClass('hidden');
    $('#form-brand, #form-model').addClass('hidden');
    $('#edit-form').attr('action', '/generations/' + id);
    $('#edit-modal').removeClass('hidden');
}

function closeModal() {
    $('#edit-modal').addClass('hidden');
}











    // Инициализация Select2
    function initSelect2($element) {
        $element.select2({
            placeholder: $element.attr('placeholder'),
            allowClear: true,
            width: '100%'
        });
    }

    $(document).ready(function () {
        // Инициализируем селекты\
        initSelect2($('#model-brand_id'));
        initSelect2($('#generation-model_id'));
        initSelect2($('#brand-select'));
        initSelect2($('#model-select'));
        initSelect2($('#generation-select'));

        // При выборе марки — загружаем все модели этой марки в селект
        $('#brand-select').on('change', function () {
    const brandId = $(this).val();
    const modelSelect = $('#model-select');
    const genSelect = $('#generation-select');

    if (!brandId) return;

    // Удаляем все марки, кроме выбранной (если она уже есть)
    const existingBrandEl = document.querySelector(`[data-brand-id="${brandId}"]`);
   

    // Если марки нет в DOM - загружаем её
    if (!existingBrandEl) {
        selectBrandInSelect(brandId);
    } else {
        // Если есть - перемещаем в начало
        existingBrandEl.remove();
        document.getElementById('brands-container').prepend(existingBrandEl);
        
        // Раскрываем модели
        document.getElementById(`models-${brandId}`).classList.remove('hidden');
        existingBrandEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    modelSelect.empty().append('<option value="">Выберите модель</option>').prop('disabled', false);
    genSelect.empty().append('<option value="">Выберите поколение</option>').prop('disabled', true);

    // Загрузить все модели для селекта
    $.ajax({
        url: '/api/all/models?brand_id=' + brandId,
        method: 'GET',
        success: function (data) {
            modelSelect.prop('disabled', false);
            modelSelect.empty().append('<option value="">Все модели</option>');
            data.forEach(model => {
                modelSelect.append(`<option value="${model.id}">${model.name}</option>`);
            });

            // Загрузить модели в дерево (по 10 штук)
            loadModels(brandId);
            document.getElementById(`models-${brandId}`).classList.remove('hidden');
            modelSelect.trigger('change');
        }
    });
});
        // При выборе модели — загружаем все поколения этой модели в селект
        $('#model-select').on('change', function () {
            const selectedModelId = $(this).val();
            const brandId = $('#brand-select').val();

            if (!selectedModelId || !brandId) return;

            const container = document.getElementById(`models-container-${brandId}`);
            const genSelect = $('#generation-select');

            // Очищаем только те элементы, которые не выделены (оставляем выделенную модель)
            Array.from(container.children).forEach(child => {
                if (!child.classList.contains('highlighted')) {
                    child.remove();
                }
            });

            let existingModelDiv = container.querySelector(`[data-model-id='${selectedModelId}']`);

            if (!existingModelDiv) {
                // Если нет — загружаем модель по ID напрямую
                fetch(`/api/model?id=${selectedModelId}`)
                    .then(res => res.json())
                    .then(model => {
                        if (!model || model.brand_id != brandId) return;

                        // Создаем div для модели и добавляем его в начало списка
                        const div = document.createElement('div');
                            div.className = 'border-l-4 border-green-500 pl-4 mb-4 bg-green-50 rounded-r-md highlighted';
                            div.setAttribute('data-model-id', model.id);
                            div.innerHTML = `
                            <div class="flex justify-between items-center py-2">
                                <strong class="text-gray-800">${model.name}</strong>
                                <div class="flex space-x-1">
                                <button onclick="editModel(${model.id}, '${model.name}', ${model.brand_id})" class="p-1 text-yellow-600 hover:text-yellow-800 rounded hover:bg-yellow-100">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <form action="/models/${model.id}" method="POST" onsubmit="return confirm('Удалить модель?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-red-600 hover:text-red-800 rounded hover:bg-red-100">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    </button>
                                </form>
                                <button onclick="toggleModel(${model.id})" class="p-1 text-blue-600 hover:text-blue-800 rounded hover:bg-blue-100">
                                    <svg class="h-4 w-4 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                </div>
                            </div>
                            <div id="gens-container-${model.id}" class="ml-4 hidden mt-2 space-y-2"></div>
                            `;
                        container.prepend(div); // ← добавляем в начало

                        // Теперь подгружаем поколения для этой модели
                        fetch(`/api/all/generations?model_id=${model.id}`)
                            .then(res => res.json())
                            .then(data => {
                                genSelect.prop('disabled', false);
                                genSelect.empty().append('<option value="">Все поколения</option>');
                                data.forEach(gen => {
                                    genSelect.append(`<option value="${gen.id}">${gen.name} (${gen.year_from}–${gen.year_to})</option>`);
                                });
                                genSelect.trigger('change');
                            })
                            .catch(err => {
                                console.error("Ошибка загрузки поколений:", err);
                                genSelect.prop('disabled', true);
                            });

                        // Открываем блок модели, если он был скрыт
                        const gensContainer = document.getElementById(`gens-container-${model.id}`);
                        if (gensContainer) {
                            gensContainer.classList.remove('hidden');
                        }

                    })
                    .catch(err => {
                        console.error("Ошибка загрузки модели по ID:", err);
                        container.innerHTML = '<div class="text-center text-red-500">Не удалось загрузить модель</div>';
                    });
            } else {
                // Если модель уже есть — просто открываем её
                fetch(`/api/all/generations?model_id=${selectedModelId}`)
                    .then(res => res.json())
                    .then(data => {
                        genSelect.prop('disabled', false);
                        genSelect.empty().append('<option value="">Все поколения</option>');
                        data.forEach(gen => {
                            genSelect.append(`<option value="${gen.id}">${gen.name} (${gen.year_from}–${gen.year_to})</option>`);
                        });
                        genSelect.trigger('change');
                    });
            }

            // Подгружаем остальные модели с пагинацией, исключая выбранную
            loadModels(brandId, 1, selectedModelId);

            // Раскрываем марку
            document.getElementById(`models-${brandId}`).classList.remove('hidden');
        });

        $('#generation-select').on('change', function () {
            const selectedGenId = $(this).val();
            const modelId = $('#model-select').val();

            if (!selectedGenId || !modelId) return;

            const container = document.getElementById(`gens-container-${modelId}`);
            if (!container) return;

            // Удаляем всё, кроме выделенного поколения
            Array.from(container.children).forEach(child => {
                if (!child.classList.contains('highlighted-gen')) {
                    child.remove();
                }
            });

            let existingGenDiv = container.querySelector(`[data-gen-id='${selectedGenId}']`);
            if (!existingGenDiv) {
                // Получаем поколение по ID
                fetch(`/api/generation?id=${selectedGenId}`)
                    .then(res => res.json())
                    .then(gen => {
                        if (!gen || gen.car_model_id != modelId) return;

                        const div = document.createElement('div');
                        div.className = 'text-sm text-gray-700 flex justify-between items-center py-2 px-3 bg-gray-50 rounded highlighted-gen';
                        div.setAttribute('data-gen-id', gen.id);
                        div.innerHTML = `
                        <span class="font-medium">${gen.name} (${gen.year_from}–${gen.year_to ?? ''})</span>
                        <div class="flex space-x-1">
                            <button onclick="editGeneration(${gen.id}, '${gen.name}', ${gen.car_model_id}, ${gen.year_from}, ${gen.year_to ?? 'null'})" class="p-1 text-yellow-600 hover:text-yellow-800 rounded hover:bg-yellow-100">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            </button>
                            <form action="/generations/${gen.id}" method="POST" onsubmit="return confirm('Удалить поколение?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1 text-red-600 hover:text-red-800 rounded hover:bg-red-100">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                            </form>
                        </div>
                        `;


                        container.appendChild(div);
                    });
            }

            loadGenerations(modelId, 1, selectedGenId);
        });

        // Поиск через селекты
        $('#search-form').on('submit', function (e) {
            e.preventDefault();

            const brandId = $('#brand-select').val();
            const modelId = $('#model-select').val();
            const generationId = $('#generation-select').val();

            document.querySelectorAll('[id^="models-"]').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('[id^="gens-container-"]').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('[id^="models-container"]').forEach(container => container.innerHTML = '');

            if (!brandId && !modelId && !generationId) {
                document.querySelectorAll('[id^="models-"]').forEach(el => el.classList.remove('hidden'));
                return;
            }

            let url = "/api/paginated/models";
            let params = {};
            if (brandId) params.brand_id = brandId;
            if (modelId) params.model_id = modelId;
            if (generationId) params.generation_id = generationId;

            fetch(url + '?' + new URLSearchParams(params))
                .then(res => res.json())
                .then(data => {
                    const modelsByBrand = {};

                    data.data.forEach(model => {
                        if (!modelsByBrand[model.brand_id]) {
                            modelsByBrand[model.brand_id] = [];
                        }
                        modelsByBrand[model.brand_id].push(model);
                    });

                    Object.keys(modelsByBrand).forEach(bId => {
                        const brandBlock = document.getElementById(`models-${bId}`);
                        const container = document.getElementById(`models-container-${bId}`);
                        if (!brandBlock || !container) return;

                        brandBlock.classList.remove('hidden');

                        const existingModels = new Set();
                        container.querySelectorAll('[data-model-id]').forEach(el => {
                            existingModels.add(parseInt(el.getAttribute('data-model-id')));
                        });

                        modelsByBrand[bId].forEach(model => {
                            if (!existingModels.has(model.id)) {
                                const div = document.createElement('div');
                                div.className = 'border-l-4 border-blue-500 pl-4 mb-4 bg-blue-50 rounded-r-md';
                                div.setAttribute('data-model-id', model.id);
                                div.innerHTML = `
                                <div class="flex justify-between items-center py-2">
                                    <strong class="text-gray-800">${model.name}</strong>
                                    <div class="flex space-x-1">
                                    <button onclick="editModel(${model.id}, '${model.name}', ${model.brand_id})" class="p-1 text-yellow-600 hover:text-yellow-800 rounded hover:bg-yellow-100">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <form action="/models/${model.id}" method="POST" onsubmit="return confirm('Удалить модель?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-red-600 hover:text-red-800 rounded hover:bg-red-100">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        </button>
                                    </form>
                                    <button onclick="toggleModel(${model.id})" class="p-1 text-blue-600 hover:text-blue-800 rounded hover:bg-blue-100">
                                        <svg class="h-4 w-4 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    </div>
                                </div>
                                <div id="gens-container-${model.id}" class="ml-4 hidden mt-2 space-y-2"></div>
                                `;
                                container.appendChild(div);
                            }
                        });
                    });
                });
        });
    });
    window.toggleBrand = function (brandId) {
    const modelsDiv = document.getElementById(`models-${brandId}`);
    if (!modelsDiv) return;

    modelsDiv.classList.toggle('hidden');

    if (!modelsDiv.dataset.loaded && !modelsDiv.classList.contains('hidden')) {
        loadModels(brandId).then(() => {
            modelsDiv.dataset.loaded = 'true';
        });
    }
};
    function initSelect2WithImage($element) {
        $element.select2({
            placeholder: $element.attr('placeholder'),
            allowClear: true,
            width: '100%',
            templateResult: function (data) {
                if (!data.id) return data.text;
                const brandName = $(data.element).closest('optgroup')?.label || $(data.element).data('brand');
                return $('<span>').text(`${data.text} (${brandName})`);
            },
            templateSelection: function (data) {
                if (!data.id) return data.text;
                return $('<span>').text(data.text);
            }
        });
    }

    window.toggleModel = function (modelId) {
        const gensDiv = document.getElementById(`gens-container-${modelId}`);
        if (gensDiv) {
            gensDiv.classList.toggle('hidden');
            if (!gensDiv.dataset.loaded) {
                loadGenerations(modelId);
            }
        }
    };

    function getSearchParams() {
        return {
            brand_id: $('#brand-select').val(),
            model_id: $('#model-select').val(),
            generation_id: $('#generation-select').val()
        };
    }

    // === Загрузка моделей с пагинацией ===
    window.loadModels = function (brandId, page = 1, highlight_id = null) {
    const container = document.getElementById(`models-container-${brandId}`);
    if (!container) return Promise.resolve(); // Возвращаем Promise для корректной работы .then()

    const params = getSearchParams();
    params.brand_id = brandId;
    params.page = page;
    if (highlight_id) params.highlight_id = highlight_id;

    const url = `/api/models?${new URLSearchParams(params)}`;

    // Возвращаем fetch, чтобы использовать .then()
    return fetch(url)
        .then(res => res.json())
        .then(data => {
            if (page === 1 && highlight_id) {
                Array.from(container.children).forEach(child => {
                    if (!child.classList.contains('highlighted')) {
                        child.remove();
                    }
                });
            }

            const existingModels = new Set();
            container.querySelectorAll('[data-model-id]').forEach(el => {
                existingModels.add(parseInt(el.getAttribute('data-model-id')));
            });

            data.data.forEach(model => {
                if (!existingModels.has(model.id)) {
                    const modelDiv = document.createElement('div');
                    modelDiv.className = 'border-l-4 border-blue-500 pl-4 mb-4 bg-blue-50 rounded-r-md';
                    modelDiv.setAttribute('data-model-id', model.id);
                    modelDiv.innerHTML = `
                        <div class="flex justify-between items-center py-2">
                            <strong class="text-gray-800">${model.name}</strong>
                            <div class="flex space-x-1">
                                <button onclick="editModel(${model.id}, '${model.name}', ${model.brand_id})" class="p-1 text-yellow-600 hover:text-yellow-800 rounded hover:bg-yellow-100">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <form action="/models/${model.id}" method="POST" onsubmit="return confirm('Удалить модель?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-red-600 hover:text-red-800 rounded hover:bg-red-100">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                <button onclick="toggleModel(${model.id})" class="p-1 text-blue-600 hover:text-blue-800 rounded hover:bg-blue-100">
                                    <svg class="h-4 w-4 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div id="gens-container-${model.id}" class="ml-4 hidden mt-2 space-y-2"></div>
                    `;
                    container.appendChild(modelDiv);
                }
            });

            if (data.next_page_url) {
                const oldBtn = container.querySelector('.load-more-btn');
                if (oldBtn) oldBtn.remove();

                const loadBtn = document.createElement('button');
                loadBtn.className = 'mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 load-more-btn';
                loadBtn.textContent = 'Загрузить ещё модели';
                loadBtn.onclick = () => loadMoreModels(brandId, page + 1, highlight_id);
                container.appendChild(loadBtn);
            }

            document.getElementById(`models-${brandId}`).dataset.loaded = true;
        })
        .catch(err => {
            console.error("Ошибка загрузки моделей:", err);
            return Promise.reject(err);
        });
};

    window.loadMoreModels = function (brandId, page, highlight_id = null) {
        const container = document.getElementById(`models-container-${brandId}`);
        if (!container) return;

        const oldBtn = container.querySelector('.load-more-btn');
        if (oldBtn) oldBtn.remove();

        const params = getSearchParams();
        params.brand_id = brandId;
        params.page = page;

        if (highlight_id) params.highlight_id = highlight_id;

        fetch(`/api/models?${new URLSearchParams(params)}`)
            .then(res => res.json())
            .then(data => {
                const existingModels = new Set();
                container.querySelectorAll('[data-model-id]').forEach(el => {
                    existingModels.add(parseInt(el.getAttribute('data-model-id')));
                });

                data.data.forEach(model => {
                    if (!existingModels.has(model.id)) {
                        const modelDiv = document.createElement('div');
                        modelDiv.className = 'border-l-4 border-blue-500 pl-4 mb-4 bg-blue-50 rounded-r-md';
                        modelDiv.setAttribute('data-model-id', model.id);
                        modelDiv.innerHTML = `
                        <div class="flex justify-between items-center py-2">
                            <strong class="text-gray-800">${model.name}</strong>
                            <div class="flex space-x-1">
                            <button onclick="editModel(${model.id}, '${model.name}', ${model.brand_id})" class="p-1 text-yellow-600 hover:text-yellow-800 rounded hover:bg-yellow-100">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <form action="/models/${model.id}" method="POST" onsubmit="return confirm('Удалить модель?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-red-600 hover:text-red-800 rounded hover:bg-red-100">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                </button>
                            </form>
                            <button onclick="toggleModel(${model.id})" class="p-1 text-blue-600 hover:text-blue-800 rounded hover:bg-blue-100">
                                <svg class="h-4 w-4 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            </div>
                        </div>
                        <div id="gens-container-${model.id}" class="ml-4 hidden mt-2 space-y-2"></div>
                        `;
                        container.appendChild(modelDiv);
                    }
                });

                if (data.next_page_url) {
                    const loadBtn = document.createElement('button');
                    loadBtn.className = 'mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 load-more-btn';
                    loadBtn.textContent = 'Загрузить ещё модели';
                    loadBtn.onclick = () => loadMoreModels(brandId, page + 1, highlight_id);
                    container.appendChild(loadBtn);
                }
            });
    };

    // === Загрузка поколений с пагинацией ===
    window.loadGenerations = function (modelId, page = 1, highlight_id = null) {
        const container = document.getElementById(`gens-container-${modelId}`);
        if (!container) return;

        const params = getSearchParams();
        params.model_id = modelId;
        params.page = page;
        if (highlight_id) params.highlight_id = highlight_id;

        fetch(`/api/generations?${new URLSearchParams(params)}`)
            .then(res => res.json())
            .then(data => {
                if (page === 1 && highlight_id) {
                    Array.from(container.children).forEach(child => {
                        if (!child.classList.contains('highlighted-gen')) {
                            child.remove();
                        }
                    });
                }

                data.data.forEach(gen => {
                    if (!container.querySelector(`[data-gen-id='${gen.id}']`)) {
                        const div = document.createElement('div');
                        div.className = 'text-sm text-gray-700 flex justify-between items-center py-2 px-3 bg-gray-50 rounded';
                        div.setAttribute('data-gen-id', gen.id);
                        div.innerHTML = `
                        <span>${gen.name} (${gen.year_from}–${gen.year_to ?? ''})</span>
                        <div class="flex space-x-1">
                            <button onclick="editGeneration(${gen.id}, '${gen.name}', ${gen.car_model_id}, ${gen.year_from}, ${gen.year_to ?? 'null'})" class="p-1 text-yellow-600 hover:text-yellow-800 rounded hover:bg-yellow-100">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            </button>
                            <form action="/generations/${gen.id}" method="POST" onsubmit="return confirm('Удалить поколение?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1 text-red-600 hover:text-red-800 rounded hover:bg-red-100">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                            </form>
                        </div>
                        `;

                        container.appendChild(div);
                    }
                });

                if (data.next_page_url) {
                    const oldBtn = container.querySelector('.load-more-btn');
                    if (oldBtn) oldBtn.remove();

                    const loadBtn = document.createElement('button');
                    loadBtn.className = 'mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 load-more-btn';
                    loadBtn.textContent = 'Загрузить ещё поколения';
                    loadBtn.onclick = () => loadMoreGenerations(modelId, page + 1, highlight_id);
                    container.appendChild(loadBtn);
                }

                container.dataset.loaded = true;
            });
    };

    window.loadMoreGenerations = function (modelId, page) {
        const container = document.getElementById(`gens-container-${modelId}`);
        if (!container) return;

        const oldBtn = container.querySelector('.load-more-btn');
        if (oldBtn) oldBtn.remove();

        const params = getSearchParams();
        params.model_id = modelId;
        params.page = page;

        fetch(`/api/paginated/generations?${new URLSearchParams(params)}`)
            .then(res => res.json())
            .then(data => {
                const existingGens = new Set();
                container.querySelectorAll('[data-gen-id]').forEach(el => {
                    existingGens.add(parseInt(el.getAttribute('data-gen-id')));
                });

                data.data.forEach(gen => {
                    if (!existingGens.has(gen.id)) {
                        const div = document.createElement('div');
                        div.className = 'text-sm text-gray-700 flex justify-between items-center py-2 px-3 bg-gray-50 rounded';
                        div.setAttribute('data-gen-id', gen.id);
                        div.innerHTML = `
                        <span>${gen.name} (${gen.year_from}–${gen.year_to ?? ''})</span>
                        <div class="flex space-x-1">
                            <button onclick="editGeneration(${gen.id}, '${gen.name}', ${gen.car_model_id}, ${gen.year_from}, ${gen.year_to ?? 'null'})" class="p-1 text-yellow-600 hover:text-yellow-800 rounded hover:bg-yellow-100">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            </button>
                            <form action="/generations/${gen.id}" method="POST" onsubmit="return confirm('Удалить поколение?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1 text-red-600 hover:text-red-800 rounded hover:bg-red-100">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                            </form>
                        </div>
                        `;
                        container.appendChild(div);
                    }
                });

                if (data.next_page_url) {
                    const loadBtn = document.createElement('button');
                    loadBtn.className = 'mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 load-more-btn';
                    loadBtn.textContent = 'Загрузить ещё поколения';
                    loadBtn.onclick = () => loadMoreGenerations(modelId, page + 1);
                    container.appendChild(loadBtn);
                }
            })
            .catch(err => console.error("Ошибка подгрузки поколений:", err));
    };
</script>
<script>
$(document).on('click', '#load-more-btn', function () {
    const btn = $(this);
    const page = parseInt(btn.data('page'));

    $.ajax({
        url: '/api/admin/brands',
        method: 'GET',
        data: { page },
        success: function (response) {
            response.data.forEach(brand => {
                // Проверяем, есть ли такая марка уже в DOM
                const existing = document.querySelector(`[data-brand-id="${brand.id}"]`);
                if (!existing) {
                    const brandHTML =  `
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
                              <!-- Здесь HTML марки из _brand.blade.php -->
                              <div class="px-4 py-3 sm:px-6 border-b border-gray-200 flex items-center justify-between">
                                  <div class="flex items-center space-x-3 sm:space-x-4">
                                      <div class="flex-shrink-0 h-10 w-10 sm:h-12 sm:w-12">
                                          <img class="h-full w-full object-contain" src="/storage/${brand.logo}" alt="${brand.name}">
                                      </div>
                                      <h3 class="text-sm sm:text-base font-medium text-gray-900">${brand.name}</h3>
                                  </div>
                                 <div class="flex items-center space-x-2">
                                  <button onclick="toggleBrand(${brand.id})" class="text-gray-500 hover:text-gray-700 p-1 rounded-full hover:bg-gray-100">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                  </button>
                                  <button onclick="editBrand(${brand.id}, '${brand.name}')" class="text-yellow-500 hover:text-yellow-700 p-1 rounded-full hover:bg-yellow-50">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                  </button>
                                  <form action="brands/${brand.id}" method="POST" onsubmit="return confirm('Удалить марку?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50">
                                      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                      </svg>
                                    </button>
                                  </form>
                                </div>
                              </div>
                              <div id="models-${brand.id}" class="hidden p-4 sm:p-6 bg-gray-50">
                                <div id="models-container-${brand.id}" class="space-y-2"></div>
                        <button type="button"
                                id="load-more-models-${brand.id}"
                                data-page="2"
                                class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hidden"
                                onclick="loadModels(${brand.id}, parseInt(this.dataset.page))">
                            Загрузить ещё модели
                        </button></div>
                          </div>
                      `; // твоя функция генерации HTML
                    $('#brands-container').append(brandHTML);
                }
            });

            if (!response.next_page_url) {
                btn.remove();
            } else {
                btn.data('page', page + 1);
            }
        },
        error: function () {
            alert('Ошибка при загрузке марок');
        }
    });
});

$(document).on('click', '[data-brand-id]', function () {
    const brandId = $(this).data('brand-id');
    const brandSelect = $('#brand-select');

});


function selectBrandInSelect(brandId) {
    if (!brandId) return;

    const container = document.getElementById('brands-container');
    const existingBrandEl = document.querySelector(`[data-brand-id="${brandId}"]`);

    if (existingBrandEl) {
        existingBrandEl.remove();
        container.prepend(existingBrandEl);

        const modelsBlock = document.getElementById(`models-${brandId}`);
        if (modelsBlock && modelsBlock.classList.contains('hidden')) {
            modelsBlock.classList.remove('hidden');
        }

        if (!modelsBlock.dataset.loaded) {
            loadModels(brandId, 1);
            modelsBlock.dataset.loaded = 'true';
        }

        existingBrandEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        return;
    }

    // Если марки нет — загружаем с сервера
    fetch(`/api/admin/brand/${brandId}`)
        .then(res => res.json())
        .then(brand => {
            const brandHTML = `
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200" data-brand-id="${brand.id}">
                    <div class="px-4 py-3 sm:px-6 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <div class="flex-shrink-0 h-10 w-10 sm:h-12 sm:w-12">
                                <img class="h-full w-full object-contain" src="/storage/${brand.logo}" alt="${brand.name}">
                            </div>
                            <h3 class="text-sm sm:text-base font-medium text-gray-900">${brand.name}</h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="toggleBrand(${brand.id})" class="text-gray-500 hover:text-gray-700 p-1 rounded-full hover:bg-gray-100">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <button onclick="editBrand(${brand.id}, '${brand.name}')" class="text-yellow-500 hover:text-yellow-700 p-1 rounded-full hover:bg-yellow-50">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <form action="brands/${brand.id}" method="POST" onsubmit="return confirm('Удалить марку?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div id="models-${brand.id}" class="hidden p-4 sm:p-6 bg-gray-50">
                        <div id="models-container-${brand.id}" class="space-y-2"></div>
                        <button type="button"
                                id="load-more-models-${brand.id}"
                                data-page="2"
                                class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hidden"
                                onclick="loadModels(${brand.id}, parseInt(this.dataset.page))">
                            Загрузить ещё модели
                        </button>
                    </div>
                </div>`;

                const parser = new DOMParser();
            const doc = parser.parseFromString(brandHTML, 'text/html');
            const brandDiv = doc.body.firstChild;

            container.prepend(brandDiv);
            loadModels(brandId, 1);
            document.getElementById(`models-${brand.id}`).dataset.loaded = true;

            brandDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
        })
        .catch(err => {
            console.error("Ошибка загрузки марки:", err);
        });
}
 
function toggleModels(brandId) {
    const modelsContainer = document.getElementById(`models-${brandId}`);
    if (!modelsContainer) return;

    modelsContainer.classList.toggle('hidden');

    if (!modelsContainer.dataset.loaded) {
        loadModels(brandId, 1); // загрузка первых 10 моделей
        modelsContainer.dataset.loaded = true;
    }
}
 </script>
@endsection