<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Branch;

class MainController extends Controller
{
    public function index()
    {
        // Получаем только автомобили в продаже с главными изображениями
        $carQuery = Car::with([
                'equipment.generation.carModel.brand',
                'equipment.generation',
                'images' => function($query) {
                    $query->where('is_main', true);
                }
            ])
            ->where('is_sold', false)
            ->whereHas('images', function($query) {
                $query->where('is_main', true);
            });

        // Популярные автомобили (8 случайных)
        $popularCars = (clone $carQuery)
            ->inRandomOrder()
            ->limit(8)
            ->get();

        // Новые поступления (5 последних)
        $additionalCars = (clone $carQuery)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Для галереи (4 случайных)
        $randomCars = (clone $carQuery)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $branches = Branch::withCount('cars')->get();

        return view('welcome', [
            'popularCars' => $popularCars,
            'additionalCars' => $additionalCars,
            'branches' => $branches,
            'randomCars' => $randomCars
        ]);
    }
}