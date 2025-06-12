<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    // Добавляем методы для отображения форм
    public function registerForm()
    {
        return view('auth.register');
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function register(Request $request)
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
        ], [
            'name.required' => 'Поле имя обязательно для заполнения.',
            'name.string' => 'Имя должно быть строкой.',
            'name.max' => 'Имя не должно превышать :max символов.',
    
            'email.required' => 'Поле email обязательно для заполнения.',
            'email.email' => 'Введите корректный email адрес.',
            'email.max' => 'Email не должен превышать :max символов.',
            'email.unique' => 'Этот email уже занят.',
    
            'phone.required' => 'Поле телефон обязательно для заполнения.',
            'phone.string' => 'Телефон должен быть строкой.',
            'phone.max' => 'Телефон не должен превышать :max символов.',
            'phone.unique' => 'Этот телефон уже занят.',
            'phone.regex' => 'Телефон должен быть в формате: 8 999 999 99 99.',
    
            'password.required' => 'Поле пароль обязательно для заполнения.',
            'password.min' => 'Пароль должен содержать минимум :min символов.',
            'password.confirmed' => 'Пароли не совпадают.',
            'password.regex' => 'Пароль должен содержать хотя бы одну заглавную букву, одну строчную и цифру.',
    
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function login(Request $request)
    {
        // Валидация с кастомными сообщениями
        $request->validate([
            'email' => 'required|email',
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ], [
            'email.required' => 'Поле email обязательно для заполнения.',
            'email.email' => 'Введите корректный email адрес.',
            'password.required' => 'Поле пароль обязательно для заполнения.',
            'password.regex' => 'Пароль должен содержать хотя бы одну заглавную букву, одну строчную и цифру.',
        ]);

        // Попытка входа
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // Если не прошёл вход — возвращаем ошибку на русском языке
        return back()->withErrors([
            'email' => 'Неверный email или пароль.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function index()
    {
        $user = auth()->user();
        $bookings = $user->bookings()
        ->with(['car.equipment.generation.carModel.brand', 'car.branch'])
        ->orderBy('created_at', 'desc')
        ->paginate(5);
        
        return view('dashboard',[
            'user' => $user,
            'bookings' => $bookings
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if ( $user->role === 'manager') {
            return redirect()->route('dashboard')->with('error', 'Вы не можете редактировать свой профиль');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'phone' => [
                'required',
                'string',
                'max:20',
                'unique:users,phone,'.$user->id,
                'regex:/^8 \d{3} \d{3} \d{2} \d{2}$/'
            ],
        ], [
            'name.required' => 'Поле имя обязательно для заполнения.',
            'name.string' => 'Имя должно быть строкой.',
            'name.max' => 'Имя не должно превышать :max символов.',
    
            'email.required' => 'Поле email обязательно для заполнения.',
            'email.email' => 'Введите корректный email адрес.',
            'email.max' => 'Email не должен превышать :max символов.',
            'email.unique' => 'Этот email уже занят.',
    
            'phone.required' => 'Поле телефон обязательно для заполнения.',
            'phone.string' => 'Телефон должен быть строкой.',
            'phone.max' => 'Телефон не должен превышать :max символов.',
            'phone.unique' => 'Этот телефон уже занят.',
            'phone.regex' => 'Телефон должен быть в формате: 8 999 999 99 99.',
        ]);

        // Проверяем, изменился ли email
        $emailChanged = $user->email !== $validated['email'];
        
        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        
        if ($emailChanged) {
            $user->email = $validated['email'];
            $user->email_verified_at = null; // Сбрасываем подтверждение email
        }

        $user->save();

        // Если email изменился, отправляем письмо для подтверждения
        if ($emailChanged) {
            $user->sendEmailVerificationNotification();
            
            return redirect()->route('dashboard')
                ->with('status', 'Email изменен. Пожалуйста, подтвердите новый email.');
        }

        return redirect()->route('dashboard')
            ->with('status', 'Данные успешно обновлены!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ], [
            'current_password.required' => 'Введите текущий пароль.',
            'current_password.current_password' => 'Неверный текущий пароль.',
    
            'password.required' => 'Поле новый пароль обязательно для заполнения.',
            'password.min' => 'Пароль должен содержать минимум :min символов.',
            'password.confirmed' => 'Новые пароли не совпадают.',
            'password.regex' => 'Пароль должен содержать хотя бы одну заглавную букву, одну строчную и цифру.',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('dashboard')->with('status', 'Пароль успешно изменен!');
    }
}