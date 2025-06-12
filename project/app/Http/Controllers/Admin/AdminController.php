<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:191|unique:users',
            'phone' => [
                'required',
                'string',
                'max:20',
                'unique:users',
                'regex:/^8 \d{3} \d{3} \d{2} \d{2}$/'
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ]);
    
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);
    
        return redirect()->route('admin.admins.index')
            ->with('success', 'Админ успешно создан.');
    }
    
    public function update(Request $request, User $user)
    {
        // Валидация
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'string',
                'email',
                'max:191',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users')->ignore($user->id, 'phone'),
                'regex:/^8 \d{3} \d{3} \d{2} \d{2}$/'
            ],
            'password' => [
                'nullable',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ]);
    
        // Подготовка данных для обновления
        $data = $request->only(['name', 'email', 'phone']);
        
        // Если указан пароль — хэшируем и добавляем к данным
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
    
        // Обновляем данные пользователя
        $user->update($data);
    
        return redirect()->route('admin.admins.index')
            ->with('success', 'Данные админа успешно обновлены.');
    }

    public function edit(User $user)
    {
        if ($user->role !== 'admin') {
            abort(404);
        }

        return view('admin.admins.edit', compact('user'));
    }

    public function destroy(User $user)
    {
        // Проверка, чтобы нельзя было удалить самого себя или суперадмина
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Нельзя удалить самого себя.');
        }

        $user->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Администратор успешно удален.');
    }
}