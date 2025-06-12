@extends('layouts.admin')

@section('title', 'Справочники')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Управление справочниками</h1>
            <p class="mt-2 text-sm text-gray-600">Здесь вы можете управлять всеми справочными данными системы</p>
        </div>

        <!-- Messages -->
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-700">Обнаружены ошибки:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Search -->
        <div class="mb-8 bg-white shadow-sm rounded-lg p-4">
            <form method="GET" action="{{ route('admin.references.index') }}" class="space-y-4 sm:space-y-0 sm:flex sm:space-x-3">
                <div class="flex-grow">
                    <label for="search" class="sr-only">Поиск</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2"
                               placeholder="Поиск по всем справочникам...">
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Найти
                    </button>
                    <a href="{{ route('admin.references.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Сбросить
                    </a>
                </div>
            </form>
        </div>

        <!-- Reference Sections Accordion -->
        <div class="space-y-4">
            <!-- Body Types Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <button onclick="toggleAccordion('bodyTypes')" class="w-full px-4 py-5 sm:px-6 border-b border-gray-200 text-left focus:outline-none">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Типы кузова</h3>
                        <svg id="bodyTypesIcon" class="h-5 w-5 text-gray-500 transform transition-transform {{ request('search') && $bodyTypes->isNotEmpty() ? 'rotate-180' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
                <div id="bodyTypesContent" class="{{ request('search') && $bodyTypes->isNotEmpty() ? '' : 'hidden' }} px-4 py-5 sm:p-6">
                    <!-- Add Form -->
                    <form method="POST" action="{{ route('admin.body-types.store') }}" class="mb-4" onsubmit="return confirm('Добавить новый тип кузова?')">
                        @csrf
                        <div class="flex space-x-2">
                            <input type="text" name="name" placeholder="Новый тип кузова" required maxlength="50"
                                   class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   value="{{ old('name') }}">
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Добавить
                            </button>
                        </div>
                    </form>
                    
                    <!-- List -->
                    <div class="overflow-hidden border border-gray-200 rounded-lg">
                        <ul class="divide-y divide-gray-200">
                            @foreach ($bodyTypes as $type)
                                <li class="px-4 py-3 sm:px-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $type->name }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <!-- Edit Form -->
                                            <form method="POST" action="{{ route('admin.body-types.update', $type) }}" class="flex items-center space-x-2">
                                                @csrf @method('PUT')
                                                <input type="text" name="name" value="{{ old('name', $type->name) }}" required maxlength="50"
                                                       class="w-32 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                <button type="submit" onclick="return confirm('Сохранить изменения?')"
                                                        class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <!-- Delete Form -->
                                            <form method="POST" action="{{ route('admin.body-types.destroy', $type) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Удалить этот тип кузова?')"
                                                        class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        @include('components.custom-pagination', ['paginator' => $bodyTypes])
                    </div>
                </div>
            </div>

            <!-- Countries Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <button onclick="toggleAccordion('countries')" class="w-full px-4 py-5 sm:px-6 border-b border-gray-200 text-left focus:outline-none">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Страны</h3>
                        <svg id="countriesIcon" class="h-5 w-5 text-gray-500 transform transition-transform {{ request('search') && $countries->isNotEmpty() ? 'rotate-180' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
                <div id="countriesContent" class="{{ request('search') && $countries->isNotEmpty() ? '' : 'hidden' }} px-4 py-5 sm:p-6">
                    <!-- Add Form -->
                    <form method="POST" action="{{ route('admin.countries.store') }}" class="mb-4" onsubmit="return confirm('Добавить новую страну?')">
                        @csrf
                        <div class="grid grid-cols-1 gap-3">
                            <input type="text" name="name" placeholder="Название страны" required maxlength="50"
                                   class="block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   value="{{ old('name') }}">
                            <div class="flex space-x-2">
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Добавить
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- List -->
                    <div class="overflow-hidden border border-gray-200 rounded-lg">
                        <ul class="divide-y divide-gray-200">
                            @foreach ($countries as $country)
                                <li class="px-4 py-3 sm:px-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $country->name }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <!-- Edit Form -->
                                            <form method="POST" action="{{ route('admin.countries.update', $country) }}" class="flex items-center space-x-2">
                                                @csrf @method('PUT')
                                                <div class="flex space-x-2">
                                                    <input type="text" name="name" value="{{ old('name', $country->name) }}" required maxlength="50"
                                                           class="w-24 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                </div>
                                                <button type="submit" onclick="return confirm('Сохранить изменения?')"
                                                        class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <!-- Delete Form -->
                                            <form method="POST" action="{{ route('admin.countries.destroy', $country) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Удалить эту страну?')"
                                                        class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        @include('components.custom-pagination', ['paginator' => $countries])
                    </div>
                </div>
            </div>

            <!-- Drive Types Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <button onclick="toggleAccordion('driveTypes')" class="w-full px-4 py-5 sm:px-6 border-b border-gray-200 text-left focus:outline-none">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Типы привода</h3>
                        <svg id="driveTypesIcon" class="h-5 w-5 text-gray-500 transform transition-transform {{ request('search') && $driveTypes->isNotEmpty() ? 'rotate-180' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
                <div id="driveTypesContent" class="{{ request('search') && $driveTypes->isNotEmpty() ? '' : 'hidden' }} px-4 py-5 sm:p-6">
                    <!-- Add Form -->
                    <form method="POST" action="{{ route('admin.drive-types.store') }}" class="mb-4" onsubmit="return confirm('Добавить новый тип привода?')">
                        @csrf
                        <div class="flex space-x-2">
                            <input type="text" name="name" placeholder="Новый тип привода" required maxlength="50"
                                   class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   value="{{ old('name') }}">
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Добавить
                            </button>
                        </div>
                    </form>
                    
                    <!-- List -->
                    <div class="overflow-hidden border border-gray-200 rounded-lg">
                        <ul class="divide-y divide-gray-200">
                            @foreach ($driveTypes as $type)
                                <li class="px-4 py-3 sm:px-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $type->name }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <!-- Edit Form -->
                                            <form method="POST" action="{{ route('admin.drive-types.update', $type) }}" class="flex items-center space-x-2">
                                                @csrf @method('PUT')
                                                <input type="text" name="name" value="{{ old('name', $type->name) }}" required maxlength="50"
                                                       class="w-32 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                <button type="submit" onclick="return confirm('Сохранить изменения?')"
                                                        class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <!-- Delete Form -->
                                            <form method="POST" action="{{ route('admin.drive-types.destroy', $type) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Удалить этот тип привода?')"
                                                        class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        @include('components.custom-pagination', ['paginator' => $driveTypes])
                    </div>
                </div>
            </div>

            <!-- Engine Types Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <button onclick="toggleAccordion('engineTypes')" class="w-full px-4 py-5 sm:px-6 border-b border-gray-200 text-left focus:outline-none">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Типы двигателей</h3>
                        <svg id="engineTypesIcon" class="h-5 w-5 text-gray-500 transform transition-transform {{ request('search') && $engineTypes->isNotEmpty() ? 'rotate-180' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
                <div id="engineTypesContent" class="{{ request('search') && $engineTypes->isNotEmpty() ? '' : 'hidden' }} px-4 py-5 sm:p-6">
                    <!-- Add Form -->
                    <form method="POST" action="{{ route('admin.engine-types.store') }}" class="mb-4" onsubmit="return confirm('Добавить новый тип двигателя?')">
                        @csrf
                        <div class="flex space-x-2">
                            <input type="text" name="name" placeholder="Новый тип двигателя" required maxlength="50"
                                   class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   value="{{ old('name') }}">
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Добавить
                            </button>
                        </div>
                    </form>
                    
                    <!-- List -->
                    <div class="overflow-hidden border border-gray-200 rounded-lg">
                        <ul class="divide-y divide-gray-200">
                            @foreach ($engineTypes as $type)
                                <li class="px-4 py-3 sm:px-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $type->name }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <!-- Edit Form -->
                                            <form method="POST" action="{{ route('admin.engine-types.update', $type) }}" class="flex items-center space-x-2">
                                                @csrf @method('PUT')
                                                <input type="text" name="name" value="{{ old('name', $type->name) }}" required maxlength="50"
                                                       class="w-32 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                <button type="submit" onclick="return confirm('Сохранить изменения?')"
                                                        class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <!-- Delete Form -->
                                            <form method="POST" action="{{ route('admin.engine-types.destroy', $type) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Удалить этот тип двигателя?')"
                                                        class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        @include('components.custom-pagination', ['paginator' => $engineTypes])
                    </div>
                </div>
            </div>

            <!-- Transmission Types Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <button onclick="toggleAccordion('transmissionTypes')" class="w-full px-4 py-5 sm:px-6 border-b border-gray-200 text-left focus:outline-none">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Коробки передач</h3>
                        <svg id="transmissionTypesIcon" class="h-5 w-5 text-gray-500 transform transition-transform {{ request('search') && $transmissionTypes->isNotEmpty() ? 'rotate-180' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
                <div id="transmissionTypesContent" class="{{ request('search') && $transmissionTypes->isNotEmpty() ? '' : 'hidden' }} px-4 py-5 sm:p-6">
                    <!-- Add Form -->
                    <form method="POST" action="{{ route('admin.transmission-types.store') }}" class="mb-4" onsubmit="return confirm('Добавить новый тип КПП?')">
                        @csrf
                        <div class="flex space-x-2">
                            <input type="text" name="name" placeholder="Новый тип КПП" required maxlength="50"
                                   class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   value="{{ old('name') }}">
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Добавить
                            </button>
                        </div>
                    </form>
                    
                    <!-- List -->
                    <div class="overflow-hidden border border-gray-200 rounded-lg">
                        <ul class="divide-y divide-gray-200">
                            @foreach ($transmissionTypes as $type)
                                <li class="px-4 py-3 sm:px-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $type->name }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <!-- Edit Form -->
                                            <form method="POST" action="{{ route('admin.transmission-types.update', $type) }}" class="flex items-center space-x-2">
                                                @csrf @method('PUT')
                                                <input type="text" name="name" value="{{ old('name', $type->name) }}" required maxlength="50"
                                                       class="w-32 px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                <button type="submit" onclick="return confirm('Сохранить изменения?')"
                                                        class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <!-- Delete Form -->
                                            <form method="POST" action="{{ route('admin.transmission-types.destroy', $type) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Удалить этот тип КПП?')"
                                                        class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        @include('components.custom-pagination', ['paginator' => $transmissionTypes])
                    </div>
                </div>
            </div>

            <!-- Branches Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <button onclick="toggleAccordion('branches')" class="w-full px-4 py-5 sm:px-6 border-b border-gray-200 text-left focus:outline-none">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Филиалы</h3>
                        <svg id="branchesIcon" class="h-5 w-5 text-gray-500 transform transition-transform {{ request('search') && $branches->isNotEmpty() ? 'rotate-180' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
                <div id="branchesContent" class="{{ request('search') && $branches->isNotEmpty() ? '' : 'hidden' }} px-4 py-5 sm:p-6">
                    <!-- Add Form -->
                    <form method="POST" action="{{ route('admin.branches.store') }}" enctype="multipart/form-data" class="mb-6 space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Название филиала</label>
                                <input type="text" name="name" id="name" placeholder="Название филиала" required maxlength="50"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       value="{{ old('name') }}">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Адрес филиала</label>
                                <div class="relative">
                                    <input type="text" name="address" id="address" placeholder="Адрес филиала" required maxlength="100"
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                           value="{{ old('address') }}">
                                    <button type="button" onclick="openMapModal('address', 'add')"
                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-sm text-blue-600 underline">
                                        Выбрать на карте
                                    </button>
                                </div>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Изображение</label>
                                <input type="file" name="image" id="image" {{ !$errors->has('image') ? 'required' : '' }}
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Добавить филиал
                            </button>
                        </div>
                    </form>
            
                    <!-- List -->
                    <div class="overflow-hidden border border-gray-200 rounded-lg">
                        <ul class="divide-y divide-gray-200">
                            @foreach ($branches as $branch)
                                <li class="px-4 py-5 sm:p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex flex-col md:flex-row md:justify-between md:space-x-4 space-y-4 md:space-y-0">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-3">
                                                @if ($branch->image)
                                                    <div class="flex-shrink-0">
                                                        <img class="h-16 w-16 rounded-md object-cover" src="{{ asset('storage/' . $branch->image) }}" alt="Фото филиала">
                                                    </div>
                                                @endif
                                                <div>
                                                    <h4 class="text-lg font-medium text-gray-900 truncate">{{ $branch->name }}</h4>
                                                    <p class="text-sm text-gray-500 truncate">{{ $branch->address }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2">
                                            <!-- Edit Form -->
                                            <form method="POST" action="{{ route('admin.branches.update', $branch) }}" enctype="multipart/form-data" class="flex flex-col space-y-2">
                                                @csrf @method('PUT')
                                                <div class="flex space-x-2">
                                                    <input type="text" name="name" value="{{ old('name', $branch->name) }}" required maxlength="50"
                                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                    <div class="relative w-full">
                                                        <input type="text"
                                                            name="address"
                                                            id="address_{{ $branch->id }}"
                                                            value="{{ old('address', $branch->address) }}"
                                                            required maxlength="100"
                                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">

                                                    <button type="button"
                                                            onclick="openMapModal('address_{{ $branch->id }}', 'edit')"
                                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-sm text-blue-600 underline">
                                                        Выбрать на карте
                                                    </button>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="address_{{ $branch->id }}" name="address_input_id" value="address_{{ $branch->id }}">
                                                <div class="flex items-center space-x-2">
                                                    <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                                    <button type="submit" onclick="return confirm('Сохранить изменения?')"
                                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                        Сохранить
                                                    </button>
                                                </div>
                                            </form>
                                            <!-- Delete Form -->
                                            <form method="POST" action="{{ route('admin.branches.destroy', $branch) }}" class="flex items-center">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Вы уверены, что хотите удалить этот филиал?')"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    Удалить
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
            
                    <!-- Pagination -->
                    <div class="mt-4">
                        @include('components.custom-pagination', ['paginator' => $branches])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Modal -->
    <div id="mapModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="relative w-full max-w-4xl bg-white rounded-lg shadow-lg">
                <div class="flex justify-between items-center px-6 py-3 border-b">
                    <h3 class="text-lg font-medium">Выберите место на карте</h3>
                    <button onclick="closeMapModal()" type="button" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                <div class="p-4 h-96">
                    <div id="yandexMap" class="w-full h-full"></div>
                </div>
                <div class="px-6 py-4 flex justify-end space-x-3 border-t">
                    <span id="selectedCoords" class="text-sm text-gray-600 mr-auto"></span>
                    <button onclick="confirmLocation()" type="button"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Выбрать
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Accordion functionality
        function toggleAccordion(sectionId) {
            const content = document.getElementById(`${sectionId}Content`);
            const icon = document.getElementById(`${sectionId}Icon`);
            
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        // Map functionality
        let map;
        let selectedPlacemark = null;
        let currentInputId = '';
        
        ymaps.ready(init);
        
        function init() {
            map = new ymaps.Map("yandexMap", {
                center: [52.291839, 104.280607],
                zoom: 10,
                controls: ['zoomControl', 'typeSelector', 'searchControl']
            });
        
            // При клике на карту — ставим маркер
            map.events.add('click', function(e) {
                const coords = e.get('coords');
        
                if (selectedPlacemark) {
                    selectedPlacemark.geometry.setCoordinates(coords);
                } else {
                    selectedPlacemark = new ymaps.Placemark(coords);
                    map.geoObjects.add(selectedPlacemark);
                }
        
                document.getElementById('selectedCoords').innerText = `Координаты: ${coords}`;
            });
        }
        
        // Открытие модального окна
        function openMapModal(inputId, mode = 'add') {
            currentInputId = inputId;
        
            // Очистка предыдущего маркера
            if (selectedPlacemark) {
                map.geoObjects.remove(selectedPlacemark);
                selectedPlacemark = null;
            }
        
            document.getElementById('mapModal').classList.remove('hidden');
        
            // Если это режим редактирования — ищем по текущему адресу
            if (mode === 'edit') {
                const addressInput = document.getElementById(currentInputId);
                const value = addressInput?.value.trim();
        
                if (value) {
                    ymaps.geocode(value).then(function(res) {
                        const firstGeoObject = res.geoObjects.get(0);
                        if (firstGeoObject) {
                            const coords = firstGeoObject.geometry.getCoordinates();
                            map.setCenter(coords, 15);
                            selectedPlacemark = new ymaps.Placemark(coords);
                            map.geoObjects.add(selectedPlacemark);
                            document.getElementById('selectedCoords').innerText = `Координаты: ${coords}`;
                        }
                    });
                }
            }
        }
        
        function closeMapModal() {
            document.getElementById('mapModal').classList.add('hidden');
        }
        
        function confirmLocation() {
            if (!selectedPlacemark) {
                alert('Пожалуйста, выберите место на карте.');
                return;
            }
        
            const coords = selectedPlacemark.geometry.getCoordinates();
        
            // Получаем адрес по координатам
            ymaps.geocode(coords).then(function(res) {
                const firstGeoObject = res.geoObjects.get(0);
                const address = firstGeoObject.getAddressLine();
        
                // Подставляем адрес в форму
                document.getElementById(currentInputId).value = address;
        
                // Сохраняем координаты (если нужно)
                const form = document.querySelector(`form`);
                if (form) {
                    let latInput = form.querySelector('input[name="latitude"]');
                    let lngInput = form.querySelector('input[name="longitude"]');
        
                    if (!latInput) {
                        form.insertAdjacentHTML('beforeend', `
                            <input type="hidden" name="latitude" value="${coords[0]}">
                            <input type="hidden" name="longitude" value="${coords[1]}">
                        `);
                    } else {
                        latInput.value = coords[0];
                        lngInput.value = coords[1];
                    }
                }
        
                closeMapModal();
            });
        }

        // Автоматически раскрываем секции при поиске
        document.addEventListener('DOMContentLoaded', function() {
            @if(request('search'))
                // Проверяем, есть ли результаты в каждой секции и раскрываем их
                @if($bodyTypes->isNotEmpty())
                    document.getElementById('bodyTypesContent').classList.remove('hidden');
                    document.getElementById('bodyTypesIcon').classList.add('rotate-180');
                @endif
                
                @if($countries->isNotEmpty())
                    document.getElementById('countriesContent').classList.remove('hidden');
                    document.getElementById('countriesIcon').classList.add('rotate-180');
                @endif
                
                @if($driveTypes->isNotEmpty())
                    document.getElementById('driveTypesContent').classList.remove('hidden');
                    document.getElementById('driveTypesIcon').classList.add('rotate-180');
                @endif
                
                @if($engineTypes->isNotEmpty())
                    document.getElementById('engineTypesContent').classList.remove('hidden');
                    document.getElementById('engineTypesIcon').classList.add('rotate-180');
                @endif
                
                @if($transmissionTypes->isNotEmpty())
                    document.getElementById('transmissionTypesContent').classList.remove('hidden');
                    document.getElementById('transmissionTypesIcon').classList.add('rotate-180');
                @endif
                
                @if($branches->isNotEmpty())
                    document.getElementById('branchesContent').classList.remove('hidden');
                    document.getElementById('branchesIcon').classList.add('rotate-180');
                @endif
            @endif
        });
    </script>
@endsection