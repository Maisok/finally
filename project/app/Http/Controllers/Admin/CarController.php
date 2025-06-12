<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Brand;
use App\Models\Equipment;
use App\Models\Color;
use App\Models\CarModel;
use App\Models\Branch;
use App\Models\Generation;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CarController extends Controller
{
    // Список всех машин
    public function index(Request $request)
    {
        // Базовый запрос
        $query = Car::with([
            'equipment.generation.carModel.brand',
            'branch', 
            'color'
        ]);
    
        // Обычный поиск
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->orWhere('vin', 'like', "%$search%")
                  ->orWhere('price', 'like', "%$search%")
                  ->orWhere('mileage', 'like', "%$search%");
    
                $q->orWhereHas('equipment.generation.carModel.brand', function ($brandQuery) use ($search) {
                    $brandQuery->where('brands.name', 'like', "%$search%");
                });
                $q->orWhereHas('equipment.generation.carModel', function ($modelQuery) use ($search) {
                    $modelQuery->where('car_models.name', 'like', "%$search%");
                });
                $q->orWhereHas('equipment.generation', function ($genQuery) use ($search) {
                    $genQuery->where('generations.name', 'like', "%$search%");
                });
            });
        }
    
        // Фильтр по марке
        if ($request->filled('brand_id')) {
            $query->whereHas('equipment.generation.carModel', function ($q) use ($request) {
                $q->where('brand_id', $request->input('brand_id'));
            });
        }
    
        // Фильтр по модели
        if ($request->filled('model_id')) {
            $query->whereHas('equipment.generation', function ($q) use ($request) {
                $q->where('car_model_id', $request->input('model_id'));
            });
        }
    
        // Фильтр по поколению
        if ($request->filled('generation_id')) {
            $query->whereHas('equipment', function ($q) use ($request) {
                $q->where('generation_id', $request->input('generation_id'));
            });
        }
    
        // Сортировка
        switch ($request->input('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'mileage_asc':
                $query->orderBy('mileage', 'asc');
                break;
            case 'mileage_desc':
                $query->orderBy('mileage', 'desc');
                break;
            case 'year_asc':
                $query->orderBy('year', 'asc');
                break;
            case 'year_desc':
                $query->orderBy('year', 'desc');
                break;
            default:
                $query->latest();
        }
    
        // Получаем данные
        $cars = $query->paginate(10)->appends($request->except('page'));
    
        // Для Select2
        $brands = Brand::all();
        $models = collect(); // по умолчанию пустые
        $generations = collect();
    
        // Если выбран бренд → загрузи модели
        if ($request->filled('brand_id')) {
            $models = CarModel::where('brand_id', $request->input('brand_id'))->get();
        }
    
        // Если выбрана модель → загрузи поколения
        if ($request->filled('model_id')) {
            $generations = Generation::where('car_model_id', $request->input('model_id'))->get();
        }
    
        return view('admin.cars.index', compact('cars', 'brands', 'models', 'generations'));
    }

    // Форма создания авто
    public function create()
    {
        $brands = Brand::all(); // Все марки
        $colors = collect(); // Пока пусто (будет заполнено JS)
        $branches = Branch::all(); // Все филиалы
    
        return view('admin.cars.create', compact('brands', 'colors', 'branches'));
    }

    public function store(Request $request)
    {
        // Основная валидация
        $data = $request->validate([
            'equipment_id' => [
                'required',
                'exists:equipment,id'
            ],

            'vin' => [
                'required',
                'string',
                'size:17',
                'regex:/^[A-Za-z0-9]{17}$/',
                Rule::unique('cars', 'vin'),
            ],
            'mileage' => [
                'nullable',
                'integer',
                'min:0',
                'max:9999999'
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'branch_id' => [
                'required',
                'exists:branches,id'
            ],
            'color_id' => [
                'nullable',
                'exists:colors,id'
            ],
            'custom_color_name' => [
                'nullable',
                'string',
                'max:50'
            ],
            'custom_color_hex' => [
                'nullable',
                'regex:/^#([A-Fa-f0-9]{6})$/i'
            ],
            'images.*' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg,webp',
                'max:2048'
            ],
            'is_sold' => 'boolean',
        ], [
            'vin.required' => 'VIN обязателен для заполнения',
            'vin.size' => 'VIN должен содержать ровно 17 символов',
            'vin.unique' => 'VIN уже используется',
            'vin.regex' => 'VIN должен содержать ровно 17 символов и состоять только из латинских букв и цифр',
            'equipment_id.required' => 'Комплектация обязательна',
            'equipment_id.exists' => 'Выбранная комплектация не существует',
            'price.required' => 'Цена обязательна',
            'price.numeric' => 'Введите корректную цену',
            'price.min' => 'Цена не может быть отрицательной',
            'mileage.integer' => 'Пробег должен быть числом',
            'mileage.max' => 'Пробег слишком большой',
            'description.string' => 'Описание должно быть строкой',
            'description.max' => 'Описание не должно превышать 1000 символов',
            'branch_id.required' => 'Филиал обязателен',
            'branch_id.exists' => 'Выбранный филиал не существует',
            'color_id.exists' => 'Выбранный цвет не существует',
            'custom_color_name.string' => 'Название цвета должно быть строкой',
            'custom_color_name.max' => 'Название цвета не должно превышать 50 символов',
            'custom_color_hex.regex' => 'HEX-код должен быть в формате #FF5733',
            'images.*.image' => 'Файл должен быть изображением',
            'images.*.mimes' => 'Поддерживаются только форматы jpeg, png, jpg, gif, svg, webp',
            'images.*.max' => 'Размер файла не должен превышать 2 МБ',
        ]);
    
        // Проверка: либо выбран color_id, либо указан свой цвет
        if (!$request->filled('color_id') && !($request->filled('custom_color_name') || $request->filled('custom_color_hex'))) {
            return back()->withErrors(['color_id' => 'Выберите цвет из списка или укажите свой'])->withInput();
        }
    
        // Если указан свой цвет → очищаем color_id
        if ($request->filled('custom_color_name') || $request->filled('custom_color_hex')) {
            $data['color_id'] = null;
        }
    
    
        // Уведомление пользователям
        $equipment = Equipment::with('favoritedByUsers')->find($data['equipment_id']);
        if ($equipment && $equipment->favoritedByUsers->isNotEmpty()) {
            foreach ($equipment->favoritedByUsers as $user) {
                $user->notifications()->create([
                    'car_id' => $car->id,
                    'type' => 'favorite_car_available',
                    'message' => "Появился в продаже автомобиль {$car->full_name}",
                    'url' => route('cars.show', $car->id),
                ]);
            }
        }
    

        if (!$request->hasFile('images')) {
            return back()
                ->withErrors(['images' => 'Необходимо загрузить хотя бы одно фото'])
                ->withInput();
        }
        $car = Car::create($data);
        $newImages = $request->file('images');
        
        // Проверяем, не больше ли 20 файлов
        $allowedImages = array_slice($newImages, 0, 20);
        
        if (count($newImages) > 20) {
            session()->flash('warning', 'Загружено более 20 фото. Сохранены только первые 20.');
        }
        
        if (!empty($allowedImages)) {
            foreach ($allowedImages as $key => $image) {
                $path = $image->store('cars', 'public');
                $car->images()->create([
                    'path' => $path,
                    'is_main' => $key === 0, // первое фото — главное
                ]);
            }
        }

      
        return redirect()->route('admin.cars.index')->with('success', 'Автомобиль успешно добавлен');
    }

    // Форма редактирования авто
    public function edit(Car $car)
    {
        // Загружаем связи
        $car->load(['equipment.generation.carModel.brand', 'branch', 'color']);
    
        // Получаем ID связанных сущностей
        $brandId = optional($car->equipment->generation->carModel->brand)->id;
        $modelId = optional($car->equipment->generation->carModel)->id;
        $generationId = optional($car->equipment->generation)->id;
        $equipmentId = optional($car->equipment)->id;
    
        // Все справочники
        $brands = Brand::all();
        $colors = Color::all();
        $branches = Branch::all();
    
        return view('admin.cars.edit', compact(
            'car', 'brands', 'colors', 'branches', 'brandId', 'modelId', 'generationId', 'equipmentId'
        ));
    }

    public function update(Request $request, Car $car)
    {

        $vinRules = [
            'required',
            'string',
            'size:17',
            'regex:/^[A-Za-z0-9]{17}$/',
            Rule::unique('cars')->ignore($car->id),
        ];
        
        // Валидация данных
        $data = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'vin' => $vinRules,
            'mileage' => 'nullable|integer|min:0|max:9999999',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:5000',
            'branch_id' => 'required|exists:branches,id',
            'color_id' => 'nullable|exists:colors,id',
            'custom_color_name' => 'nullable|string|max:50',
            'custom_color_hex' => 'nullable|regex:/^#([A-Fa-f0-9]{6})$/i',
            'is_sold' => 'boolean',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
           'vin.size' => 'VIN должен содержать ровно 17 символов',
            'vin.unique' => 'VIN уже используется другим автомобилем',
            'vin.regex' => 'VIN должен содержать ровно 17 символов и состоять только из латинских букв и цифр',
            'price.min' => 'Цена не может быть отрицательной',
            'mileage.max' => 'Пробег слишком большой',
            'custom_color_name.max' => 'Название цвета не должно превышать 50 символов',
            'custom_color_hex.regex' => 'HEX-код должен быть в формате #FF5733',
            'new_images.*.mimes' => 'Поддерживаются только форматы: jpeg, png, jpg, gif, svg',
            'new_images.*.max' => 'Фото не должно превышать 2 Мб',
        ]);
    
        // Кастомная валидация цвета (либо color_id, либо custom_color_*)
        $validator = Validator::make($request->all(), [
            'color_id' => 'nullable|exists:colors,id',
            'custom_color_name' => 'nullable|string|max:50',
            'custom_color_hex' => 'nullable|regex:/^#([A-Fa-f0-9]{6})$/i',
        ]);
    
        $validator->after(function ($validator) use ($request) {
            if (!$request->filled('color_id') &&
                (!$request->filled('custom_color_name') || !$request->filled('custom_color_hex'))) {
                $validator->errors()->add(
                    'color_validation',
                    'Необходимо выбрать цвет из списка или указать свой'
                );
            }
        });
    
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->get('*'));
        }
    

        if ($request->filled('custom_color_name') && $request->filled('custom_color_hex')) {

            $data['custom_color_name'] = $request->input('custom_color_name');
            $data['custom_color_hex'] = $request->input('custom_color_hex');
            $data['color_id'] = null; 
        } else {

            $data['custom_color_name'] = null;
            $data['custom_color_hex'] = null;
        }
    
        // Обновляем данные авто
        $car->update($data);
    
        // Загрузка новых фото
        if ($request->hasFile('new_images')) {
            $existingCount = $car->images()->count();
            $newImages = $request->file('new_images');
            $total = $existingCount + count($newImages);
    
            if ($total > 20) {
                session()->flash('warning', 'Вы загрузили больше фото, чем можно хранить. Сохранены только первые 20.');
    
                $allowedImages = array_slice($newImages, 0, max(0, 20 - $existingCount));
    
                foreach ($allowedImages as $key => $image) {
                    $path = $image->store('cars', 'public');
                    $car->images()->create([
                        'path' => $path,
                        'is_main' => $existingCount === 0 && $key === 0 ? true : false,
                    ]);
                }
            } else {
                foreach ($newImages as $key => $image) {
                    $path = $image->store('cars', 'public');
                    $car->images()->create([
                        'path' => $path,
                        'is_main' => false,
                    ]);
                }
            }
        }
    
        // Удаление фото
        if ($request->has('delete_images')) {
            $imagesToDelete = $car->images()
                ->whereIn('id', $request->input('delete_images'))
                ->get();
    
            if ($car->images->count() - count($imagesToDelete) < 1) {
                return back()->withErrors(['delete_images' => 'Невозможно удалить все изображения. Оставьте хотя бы одно.']);
            }
    
            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }
    
        // Смена главного фото
        if ($request->has('main_image_id')) {
            $car->images()->update(['is_main' => false]);
            $car->images()
                ->where('id', $request->input('main_image_id'))
                ->update(['is_main' => true]);
        }
    
        return redirect()->route('admin.cars.index')->with('success', 'Автомобиль обновлён');
    }

    // Удаление автомобиля
    public function destroy(Car $car)
    {
        // Проверяем, есть ли у автомобиля бронирования
        if ($car->bookings()->exists()) {
            return back()->with('error', 'Невозможно удалить автомобиль — он имеет бронирования.');
        }
    
        foreach ($car->images as $img) {
            Storage::disk('public')->delete($img->path);
            $img->delete();
        }
    
        $car->delete();
        return back()->with('success', 'Автомобиль удалён');
    }

    public function favorite(Car $car, Request $request)
    {
        $user = $request->user();
        $equipment = $car->equipment;
        $isFavorite = false;
    
        if ($user->favoriteEquipments()->where('equipment_id', $equipment->id)->exists()) {
            $user->favoriteEquipments()->detach($equipment->id);
        } else {
            $user->favoriteEquipments()->attach($equipment->id);
            $isFavorite = true;
        }
    
        return response()->json([
            'is_favorite' => $isFavorite,
            'message' => $isFavorite ? 'Добавлено в избранное' : 'Удалено из избранного'
        ]);
    }

    public function removeFromFavorites(Request $request, $equipment)
    {
        $user = auth()->user();

        // Удалить конкретное оборудование из избранного
        $user->removeFromFavorites($equipment);

        return redirect()->back()->with('success', 'Оборудование удалено из избранного');
    }
}