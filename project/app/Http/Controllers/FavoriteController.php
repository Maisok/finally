<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $favorites = $user->favoriteEquipments;

        return view('favorites.index', compact('favorites'));
    }
}