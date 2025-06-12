<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Generation;
use App\Models\Equipment;
use App\Models\BodyType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use App\Models\DriveType;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // Базовый запрос
        $query = Car::with([
            'equipment.generation.carModel.brand',
            'equipment.bodyType',
            'equipment.engineType',
            'equipment.transmissionType',
            'equipment.driveType'
        ])->where('is_sold', false);

        // Глобальный поиск
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->whereHas('equipment.generation.carModel.brand', function($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%");
                })->orWhereHas('equipment.generation.carModel', function($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%");
                })->orWhereHas('equipment.generation', function($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%");
                });
            });
        }

        // Фильтрация по марке
        if ($request->brand) {
            $query->whereHas('equipment.generation.carModel.brand', function($q) use ($request) {
                $q->where('id', $request->brand);
            });
        }

        // Фильтрация по модели
        if ($request->model) {
            $query->whereHas('equipment.generation.carModel', function($q) use ($request) {
                $q->where('id', $request->model);
            });
        }

        // Фильтрация по поколению
        if ($request->generation) {
            $query->whereHas('equipment.generation', function($q) use ($request) {
                $q->where('id', $request->generation);
            });
        }

        // Фильтрация по комплектации
        if ($request->equipment) {
            $query->where('equipment_id', $request->equipment);
        }

        // Фильтрация по типу кузова
        if ($request->body_type) {
            $query->whereHas('equipment.bodyType', function($q) use ($request) {
                $q->where('id', $request->body_type);
            });
        }

        // Фильтрация по типу трансмиссии
        if ($request->transmission_type) {
            $query->whereHas('equipment.transmissionType', function($q) use ($request) {
                $q->where('id', $request->transmission_type);
            });
        }

        // Фильтрация по типу двигателя
        if ($request->engine_type) {
            $query->whereHas('equipment.engineType', function($q) use ($request) {
                $q->where('id', $request->engine_type);
            });
        }

        // Фильтрация по типу привода
        if ($request->drive_type) {
            $query->whereHas('equipment.driveType', function($q) use ($request) {
                $q->where('id', $request->drive_type);
            });
        }

        // Фильтрация по цене
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Фильтрация по пробегу
        if ($request->min_mileage) {
            $query->where('mileage', '>=', $request->min_mileage);
        }
        if ($request->max_mileage) {
            $query->where('mileage', '<=', $request->max_mileage);
        }

        // Фильтрация по году выпуска
        if ($request->min_year) {
            $query->whereHas('equipment.generation', function($q) use ($request) {
                $q->where('year_from', '>=', $request->min_year);
            });
        }
        if ($request->max_year) {
            $query->whereHas('equipment.generation', function($q) use ($request) {
                $q->where('year_from', '<=', $request->max_year);
            });
        }

        // Фильтрация по объему двигателя
        if ($request->min_engine_volume) {
            $query->whereHas('equipment', function($q) use ($request) {
                $q->where('engine_volume', '>=', $request->min_engine_volume);
            });
        }
        if ($request->max_engine_volume) {
            $query->whereHas('equipment', function($q) use ($request) {
                $q->where('engine_volume', '<=', $request->max_engine_volume);
            });
        }

        // Сортировка
        $sort = $request->sort ?? 'price';
        $direction = $request->direction ?? 'asc';

        $query->orderBy(
            match($sort) {
                'year' => Equipment::select('generations.year_from')
                    ->join('generations', 'generations.id', '=', 'equipment.generation_id')
                    ->whereColumn('equipment.id', 'cars.equipment_id'),
                'mileage' => 'mileage',
                'price' => 'price',
                'engine_volume' => Equipment::select('engine_volume')
                    ->whereColumn('equipments.id', 'cars.equipment_id'),
                default => $sort
            },
            $direction
        );

        $cars = $query->paginate(12);

        // Данные для фильтров
        $brands = Brand::has('models.generations.equipments.cars')->get();
        $models = CarModel::when($request->brand, function($q) use ($request) {
            $q->where('brand_id', $request->brand);
        })->has('generations.equipments.cars')->get();
        
        $generations = Generation::when($request->model, function($q) use ($request) {
            $q->where('car_model_id', $request->model);
        })->has('equipments.cars')->get();
        
        $equipments = Equipment::when($request->generation, function($q) use ($request) {
            $q->where('generation_id', $request->generation);
        })->has('cars')->get();
        
        $bodyTypes = BodyType::has('equipments.cars')->get();
        $engineTypes = EngineType::has('equipments.cars')->get();
        $transmissionTypes = TransmissionType::has('equipments.cars')->get();
        $driveTypes = DriveType::has('equipments.cars')->get();

        return view('catalog.index', compact(
            'cars',
            'brands',
            'models',
            'generations',
            'equipments',
            'bodyTypes',
            'engineTypes',
            'transmissionTypes',
            'driveTypes'
        ));
    }

    public function show(Car $car)
    {
        $car->load([
            'equipment.generation.carModel.brand',
            'equipment.bodyType',
            'equipment.driveType',
            'equipment.engineType',
            'equipment.transmissionType',
            'equipment.country',
            'branch',
            'color',
            'images'
        ]);
    
        // Проверяем доступность 3D модели
        $has3dModel = $car->equipment->model_url !== null;
        
        return view('cars.show', compact('car', 'has3dModel'));
    }
}