<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Telegram\Bot\Laravel\Facades\Telegram;

class FavoriteEquipmentController extends Controller
{
    public function add(Equipment $equipment)
    {
        $user = Auth::user();
        
        $user->favoriteEquipments()->syncWithoutDetaching([$equipment->id]);
        
        return back()->with('success', 'Комплектация добавлена в избранное');
    }
    
    public function remove(Equipment $equipment)
    {
        $user = Auth::user();
        
        $user->favoriteEquipments()->detach($equipment->id);
        
        return back()->with('success', 'Комплектация удалена из избранного');
    }
    
    public function connectTelegram(Request $request)
    {
        $request->validate([
            'telegram_chat_id' => 'required|numeric'
        ]);
        
        $user = Auth::user();
        
        // Обновляем chat_id для всех избранных комплектаций
        $user->favoriteEquipments()->updateExistingPivot(
            $user->favoriteEquipments->pluck('id')->toArray(),
            ['telegram_chat_id' => $request->telegram_chat_id]
        );
        
        return back()->with('success', 'Telegram успешно подключен');
    }
}