<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ManagerController extends Controller
{

    public function index(Request $request)
    {
        // Начинаем запрос
        $query = User::where('role', 'manager');
    
        // Поиск по имени
        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
    
        // Поиск по телефону
        if ($request->has('phone') && $request->phone != '') {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }
    
        // Поиск по email
        if ($request->has('email') && $request->email != '') {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
    
        // Получаем результаты
        $managers = $query->paginate(10);
    
        // Передаём данные в представление
        return view('admin.managers.index', compact('managers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
          'phone' => [
                'required',
                'string',
                'max:20',
                'unique:users',
                'regex:/^8 \d{3} \d{3} \d{2} \d{2}$/'
            ],
           'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
                'confirmed'
            ],
        ], [
            'name.required' => 'Поле "Имя" обязательно.',
            'name.string' => 'Имя должно быть строкой.',
            'name.max' => 'Имя не должно превышать 255 символов.',
        
            'email.required' => 'Поле "Email" обязательно.',
            'email.email' => 'Введите корректный email.',
            'email.unique' => 'Пользователь с таким email уже существует.',
            'email.max' => 'Email не должен превышать 255 символов.',
        
            'phone.required' => 'Поле "Телефон" обязательно.',
            'phone.string' => 'Телефон должен быть строкой.',
            'phone.unique' => 'Пользователь с таким телефоном уже существует.',
            'phone.max' => 'Телефон не должен превышать 20 символов.',
            'phone.regex' => 'Телефон должен быть в формате: 8 999 999 99 99.',
        
            'password.required' => 'Пароль обязателен.',
            'password.confirmed' => 'Пароли не совпадают.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
            'password.regex' => 'Пароль должен содержать хотя бы одну заглавную букву, строчную букву и цифру.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'manager',
        ]);

        return redirect()->route('admin.managers.index')->with('success', 'Менеджер успешно добавлен');
    }

    public function update(Request $request, User $manager)
{
    // Валидация данных
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $manager->id,
        'phone' => 'required|string|max:20|regex:/^8 \d{3} \d{3} \d{2} \d{2}$/|unique:users,phone,' . $manager->id,
       'password' => [
            'nullable',
            'string',
            'min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/'
        ],
    ];

    $messages = [
        'name.required' => 'Поле "Имя" обязательно для заполнения.',
        'name.string' => 'Поле "Имя" должно быть строкой.',
        'name.max' => 'Поле "Имя" не должно превышать 255 символов.',

        'email.required' => 'Поле "Email" обязательно для заполнения.',
        'email.email' => 'Введите корректный email.',
        'email.max' => 'Поле "Email" не должно превышать 255 символов.',
        'email.unique' => 'Пользователь с таким email уже существует.',

        'phone.required' => 'Поле "Телефон" обязательно для заполнения.',
        'phone.string' => 'Поле "Телефон" должно быть строкой.',
        'phone.max' => 'Поле "Телефон" не должно превышать 20 символов.',
        'phone.unique' => 'Пользователь с таким телефоном уже существует.',
        'phone.regex' => 'Телефон должен быть в формате: 8 999 999 99 99.',

        'password.string' => 'Пароль должен быть строкой.',
        'password.min' => 'Пароль должен содержать минимум 8 символов.',
        'password.regex' => 'Пароль должен содержать хотя бы одну заглавную букву, строчную букву и цифру.',
    ];

    $request->validate($rules, $messages);

    // Подготовка данных для обновления
    $data = $request->only(['name', 'email', 'phone']);

    // Если пароль указан — добавляем его
    if ($request->filled('password')) {
        $data['password'] = bcrypt($request->password);
    }

    // Обновление пользователя
    $manager->update($data);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'manager' => $manager
        ]);
    }

    return redirect()->route('admin.managers.index')->with('success', 'Менеджер успешно обновлен');
}

public function destroy(User $manager)
{
    // Проверяем, есть ли у менеджера бронирования
    if ($manager->bookings()->exists()) {
        return back()->with('error', 'Невозможно удалить менеджера — у него есть бронирования.');
    }

    // Если бронирований нет — удаляем
    $manager->delete();

    return redirect()->route('admin.managers.index')->with('success', 'Менеджер успешно удален');
}
}