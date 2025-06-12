<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class CurrentPassword implements Rule
{
    public function passes($attribute, $value)
    {
        return Hash::check($value, auth()->user()->password);
    }

    public function message()
    {
        return 'Текущий пароль указан неверно.';
    }
}