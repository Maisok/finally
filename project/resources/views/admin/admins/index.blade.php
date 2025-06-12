@extends('layouts.user')

@section('content')
<section class="py-10">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">Список администраторов</h1>
            <a href="{{ route('admin.admins.create') }}" 
               class="px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-all">
                Добавить администратора
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-500/20 border-l-4 border-green-500 text-green-300 p-4 mb-6 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#3C3C3C] rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-[#2D2D2D]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Имя</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Телефон</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($admins as $admin)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">#{{ $admin->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $admin->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $admin->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $admin->phone ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right space-x-2">
                                <a href="{{ route('admin.admins.edit', $admin) }}"
                                   class="inline-block px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded transition-all">
                                    Редактировать
                                </a>
                            
                                <form action="{{ route('admin.admins.destroy', $admin) }}" 
                                      method="POST" 
                                      class="inline-block"
                                      onsubmit="return confirm('Вы уверены, что хотите удалить этого администратора?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded transition-all">
                                        Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-400">
                                Нет зарегистрированных администраторов
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

          
        </div>
    </div>
</section>
@endsection