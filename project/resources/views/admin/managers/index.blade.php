@extends('layouts.admin')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="space-y-6">
        <!-- Заголовок и хлебные крошки -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Управление менеджерами</h1>
               
            </div>
            <div class="flex-shrink-0">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Всего менеджеров: {{ $managers->total() }}
                </span>
            </div>
        </div>

        <!-- Уведомления -->
        <div class="space-y-4">
            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4 border border-red-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Ошибки при обработке формы</h3>
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

            @if(session('success'))
                <div class="rounded-md bg-green-50 p-4 border border-green-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-md bg-red-50 p-4 border border-red-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Основной контент -->
        <div class="bg-white shadow rounded-lg divide-y divide-gray-200">
            <!-- Поиск и фильтры -->
            <div class="px-4 py-5 sm:px-6">
                <div class="-ml-4 -mt-2 flex flex-wrap items-center justify-between sm:flex-nowrap">
                    <div class="ml-4 mt-2">
                        <h3 class="text-base font-medium text-gray-900">Поиск менеджеров</h3>
                    </div>
                    <div class="ml-4 mt-2 flex-shrink-0">
                        <button type="button" onclick="document.getElementById('search-form').classList.toggle('hidden')"
                                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class=" h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                <form id="search-form" method="GET" action="{{ route('admin.managers.index') }}" class="mt-4 space-y-4">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-3">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="{{ request('name') }}"
                                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <div class="mt-1">
                                <input type="text" name="email" id="email" value="{{ request('email') }}"
                                       class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                            </div>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Телефон</label>
                            <div class="mt-1">
                                <input type="text" name="phone" id="phone" value="{{ request('phone') }}"
                                       class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.managers.index') }}"
                           class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Сбросить
                        </a>
                        <button type="submit"
                                class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Применить
                        </button>
                    </div>
                </form>
            </div>

            <!-- Добавление нового менеджера -->
            <div class="px-4 py-5 sm:p-6">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="min-w-0 flex-1">
                        <h3 class="text-base font-medium text-gray-900">Добавить нового менеджера</h3>
                        <p class="mt-1 text-sm text-gray-500">Заполните форму для создания новой учетной записи менеджера.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.managers.store') }}" class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    @csrf
                    <div class="sm:col-span-2">
                        <label for="new-name" class="block text-sm font-medium text-gray-700">Имя</label>
                        <div class="mt-1">
                            <input type="text" name="name" id="new-name" required
                                   class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="new-email" class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1">
                            <input type="email" name="email" id="new-email" required
                                   class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="new-phone" class="block text-sm font-medium text-gray-700">Телефон</label>
                        <div class="mt-1">
                            <input type="text" name="phone" id="new-phone" required
                                   class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="new-password" class="block text-sm font-medium text-gray-700">Пароль</label>
                        <div class="mt-1">
                            <input type="password" name="password" id="new-password" required
                                   class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="new-password-confirmation" class="block text-sm font-medium text-gray-700">Подтверждение пароля</label>
                        <div class="mt-1">
                            <input type="password" name="password_confirmation" id="new-password-confirmation" required
                                   class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-md">
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="ml-3 inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Добавить менеджера
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Список менеджеров -->
            <div class="px-4 py-5 sm:p-6">
                <div class="-ml-4 -mt-2 flex flex-wrap items-center justify-between sm:flex-nowrap">
                    <div class="ml-4 mt-2">
                        <h3 class="text-base font-medium text-gray-900">Список менеджеров</h3>
                    </div>
                    <div class="ml-4 mt-2 flex-shrink-0">
                        <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-0.5 text-sm font-medium text-gray-800">
                            Страница {{ $managers->currentPage() }} из {{ $managers->lastPage() }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 overflow-hidden border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Имя</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Телефон</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($managers as $manager)
                            <tr class="hover:bg-gray-50" data-id="{{ $manager->id }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $manager->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $manager->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $manager->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $manager->phone }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <button onclick="openEditModal('{{ $manager->id }}')"
                                                class="text-blue-600 hover:text-blue-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('admin.managers.destroy', $manager) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Вы уверены, что хотите удалить этого менеджера?')"
                                                    class="text-red-600 hover:text-red-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
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

                <!-- Пагинация -->
                <div class="mt-4">
                    {{ $managers->links('components.custom-pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно редактирования -->
<div id="edit-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Редактирование менеджера</h3>
                    <div class="mt-2">
                        <form id="edit-form" method="POST" action="" class="space-y-4">
                            @csrf
                            @method('PUT')
                            
                            <input type="hidden" name="manager_id" id="modal-manager-id">

                            <div>
                                <label for="modal-name" class="block text-sm font-medium text-gray-700">Имя</label>
                                <input type="text" name="name" id="modal-name" required
                                class="mt-1 block w-full border rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-md">
                            </div>

                            <div>
                                <label for="modal-email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="modal-email" required
                                class="mt-1 block w-full border rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-md">
                            </div>

                            <div>
                                <label for="modal-phone" class="block text-sm font-medium text-gray-700">Телефон</label>
                                <input type="text" name="phone" id="modal-phone" required
                                class="mt-1 block w-full border rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-md">
                            </div>

                            <div>
                                <label for="modal-password" class="block text-sm font-medium text-gray-700">Новый пароль (необязательно)</label>
                                <input type="password" name="password" id="modal-password"
                                class="mt-1 block w-full border rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-md">
                            </div>

                            <div>
                                <label for="modal-password-confirmation" class="block text-sm font-medium text-gray-700">Подтверждение пароля</label>
                                <input type="password" name="password_confirmation" id="modal-password-confirmation"
                                       class="mt-1 block w-full border rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-md">
                                       
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <button type="button" onclick="submitEditForm()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-2 sm:text-sm">
                    Сохранить
                </button>
                <button type="button" onclick="closeEditModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                    Отмена
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openEditModal(managerId) {
        // Получаем данные менеджера из таблицы
        const row = document.querySelector(`tr[data-id="${managerId}"]`);
        const name = row.querySelector('td:nth-child(2)').textContent.trim();
        const email = row.querySelector('td:nth-child(3)').textContent.trim();
        const phone = row.querySelector('td:nth-child(4)').textContent.trim();
        
        // Заполняем форму модального окна
        document.getElementById('modal-manager-id').value = managerId;
        document.getElementById('modal-name').value = name;
        document.getElementById('modal-email').value = email;
        document.getElementById('modal-phone').value = phone;
        
        // Устанавливаем action формы
        document.getElementById('edit-form').action = `/admin/managers/${managerId}`;
        
        // Показываем модальное окно
        document.getElementById('edit-modal').classList.remove('hidden');
    }
    
    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }
    
    function submitEditForm() {
        document.getElementById('edit-form').submit();
    }
    
    // Закрытие модального окна при клике вне его
    document.getElementById('edit-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
    
    // Форматирование телефона
    document.querySelectorAll('input[name="phone"]').forEach(input => {
        input.addEventListener("input", function (e) {
            let input = this.value.replace(/\D/g, '');
            let formatted = '';
            if (input.length > 0) formatted = '8 ';
            if (input.length > 1) formatted += input.substring(1, 4);
            if (input.length > 4) formatted += ' ' + input.substring(4, 7);
            if (input.length > 7) formatted += ' ' + input.substring(7, 9);
            if (input.length > 9) formatted += ' ' + input.substring(9, 11);
            this.value = formatted;
        });
    });
</script>
@endsection