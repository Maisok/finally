<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Generation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CarStructureController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::paginate(10);
        $allModels = CarModel::all();
        return view('admin.car-structure.index', compact('brands', "allModels"));
    }

    // --- BRAND ---

    public function storeBrand(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('brands', 'name'),
            ],
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'name.required' => 'Поле "Название марки" обязательно для заполнения.',
            'name.string' => 'Название марки должно быть строкой.',
            'name.max' => 'Название марки не должно превышать 50 символов.',
            'name.unique' => 'Марка с таким названием уже существует.',
            'logo.required' => 'Загрузите логотип марки.',
            'logo.image' => 'Логотип должен быть изображением.',
            'logo.mimes' => 'Поддерживаются форматы: jpeg, png, jpg, gif, webp.',
            'logo.max' => 'Размер логотипа не должен превышать 2 МБ.',
        ]);

        $data = ['name' => $request->name];
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        Brand::create($data);

        return redirect()->route('admin.car-structure.index')->with('success', 'Марка успешно добавлена');
    }

    public function updateBrand(Request $request, Brand $brand)
    {
        $request->validate([
            'brand_name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('brands', 'name')->ignore($brand->id),
            ],
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'brand_name.required' => 'Поле "Название марки" обязательно для заполнения.',
            'brand_name.string' => 'Название марки должно быть строкой.',
            'brand_name.max' => 'Название марки не должно превышать 50 символов.',
            'brand_name.unique' => 'Марка с таким названием уже существует.',
            'logo.image' => 'Логотип должен быть изображением.',
            'logo.mimes' => 'Поддерживаются форматы: jpeg, png, jpg, gif, webp.',
            'logo.max' => 'Размер логотипа не должен превышать 2 МБ.',
        ]);

        $data = ['name' => $request->input('brand_name')];

        if ($request->hasFile('logo')) {
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($data);

        return redirect()->route('admin.car-structure.index')->with('success', 'Марка успешно обновлена');
    }

    public function destroyBrand(Brand $brand)
    {
        if ($brand->models()->exists()) {
            return back()->with('error', 'Невозможно удалить марку, так как у нее есть модели');
        }

        if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return redirect()->route('admin.car-structure.index')->with('success', 'Марка успешно удалена');
    }

    // --- MODEL ---

    public function storeModel(Request $request)
    {
        $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('car_models')->where(fn($query) => $query->where('brand_id', $request->brand_id)),
            ],
        ], [
            'brand_id.required' => 'Поле "Марка" обязательно для выбора.',
            'brand_id.exists' => 'Выбранная марка не существует.',
            'name.required' => 'Поле "Название модели" обязательно для заполнения.',
            'name.string' => 'Название модели должно быть строкой.',
            'name.max' => 'Название модели не должно превышать 50 символов.',
            'name.unique' => 'Модель с таким названием уже существует у этой марки.',
        ]);

        CarModel::create([
            'brand_id' => $request->brand_id,
            'name' => $request->name,
        ]);

        return redirect()->route('admin.car-structure.index')->with('success', 'Модель успешно добавлена');
    }

    public function updateModel(Request $request, CarModel $model)
{
    $request->validate([
        'brand_id' => 'required|exists:brands,id',
        'model_name' => [
            'required',
            'string',
            'max:50',
            Rule::unique('car_models', 'name')
                ->where(fn($query) => $query->where('brand_id', $request->brand_id))
                ->ignore($model->id),
        ],
    ], [
        'brand_id.required' => 'Поле "Марка" обязательно для выбора.',
        'brand_id.exists' => 'Выбранная марка не существует.',
        'model_name.required' => 'Поле "Название модели" обязательно для заполнения.',
        'model_name.string' => 'Название модели должно быть строкой.',
        'model_name.max' => 'Название модели не должно превышать 50 символов.',
        'model_name.unique' => 'Модель с таким названием уже существует у этой марки.',
    ]);

    $model->update([
        'brand_id' => $request->brand_id,
        'name' => $request->model_name,
    ]);

    return redirect()->route('admin.car-structure.index')->with('success', 'Модель успешно обновлена');
}

    public function destroyModel(CarModel $model)
    {
        if ($model->generations()->exists()) {
            return back()->with('error', 'Невозможно удалить модель, так как у нее есть поколения');
        }

        $model->delete();

        return redirect()->route('admin.car-structure.index')->with('success', 'Модель успешно удалена');
    }

    // --- GENERATION ---

    public function storeGeneration(Request $request)
    {
        $currentYear = date('Y');

        $request->validate([
            'car_model_id' => 'required|exists:car_models,id',
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('generations')->where(fn($query) => $query->where('car_model_id', $request->car_model_id)),
            ],
            'year_from' => "required|integer|min:1900|max:" . ($currentYear + 10),
            'year_to' => "nullable|integer|min:1900|max:" . ($currentYear + 10) . "|gte:year_from",
        ], [
            'car_model_id.required' => 'Поле "Модель" обязательно для выбора.',
            'car_model_id.exists' => 'Выбранная модель не существует.',
            'name.required' => 'Поле "Название поколения" обязательно для заполнения.',
            'name.string' => 'Название поколения должно быть строкой.',
            'name.max' => 'Название поколения не должно превышать 50 символов.',
            'name.unique' => 'Поколение с таким названием уже существует у этой модели.',
            'year_from.required' => 'Поле "Год начала выпуска" обязательно для заполнения.',
            'year_from.integer' => 'Год начала выпуска должен быть числом.',
            'year_from.min' => 'Год начала выпуска не может быть меньше 1900.',
            'year_from.max' => 'Год начала выпуска не может быть больше ' . ($currentYear + 10),
            'year_to.integer' => 'Год окончания выпуска должен быть числом.',
            'year_to.min' => 'Год окончания выпуска не может быть меньше 1900.',
            'year_to.max' => 'Год окончания выпуска не может быть больше ' . ($currentYear + 10),
            'year_to.gte' => 'Год окончания выпуска должен быть позже года начала.',
        ]);

        Generation::create([
            'car_model_id' => $request->car_model_id,
            'name' => $request->name,
            'year_from' => $request->year_from,
            'year_to' => $request->year_to,
        ]);

        return redirect()->route('admin.car-structure.index')->with('success', 'Поколение успешно добавлено');
    }

    public function updateGeneration(Request $request, Generation $generation)
    {
        $currentYear = date('Y');

        $request->validate([
            'car_model_id' => 'required|exists:car_models,id',
            'generation_name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('generations', 'name')->where(fn($query) => $query->where('car_model_id', $request->car_model_id))
                    ->ignore($generation->id),
            ],
            'year_from' => "required|integer|min:1900|max:" . ($currentYear + 10),
            'year_to' => "nullable|integer|min:1900|max:" . ($currentYear + 10) . "|gte:year_from",
        ], [
            'car_model_id.required' => 'Поле "Модель" обязательно для выбора.',
            'car_model_id.exists' => 'Выбранная модель не существует.',
            'generation_name.required' => 'Поле "Название поколения" обязательно для заполнения.',
            'generation_name.string' => 'Название поколения должно быть строкой.',
            'generation_name.max' => 'Название поколения не должно превышать 50 символов.',
            'generation_name.unique' => 'Поколение с таким названием уже существует у этой модели.',
            'year_from.required' => 'Поле "Год начала выпуска" обязательно для заполнения.',
            'year_from.integer' => 'Год начала выпуска должен быть числом.',
            'year_from.min' => 'Год начала выпуска не может быть меньше 1900.',
            'year_from.max' => 'Год начала выпуска не может быть больше ' . ($currentYear + 10),
            'year_to.integer' => 'Год окончания выпуска должен быть числом.',
            'year_to.min' => 'Год окончания выпуска не может быть меньше 1900.',
            'year_to.max' => 'Год окончания выпуска не может быть больше ' . ($currentYear + 10),
            'year_to.gte' => 'Год окончания выпуска должен быть позже года начала.',
        ]);     

        $generation->update([
            'car_model_id' => $request->car_model_id,
            'name' => $request->generation_name,
            'year_from' => $request->year_from,
            'year_to' => $request->year_to,
        ]);

        return redirect()->route('admin.car-structure.index')->with('success', 'Поколение успешно обновлено');
    }

    public function destroyGeneration(Generation $generation)
    {
        if ($generation->equipments()->exists()) {
            return back()->with('error', 'Невозможно удалить поколение, так как у него есть комплектации');
        }

        $generation->delete();

        return redirect()->route('admin.car-structure.index')->with('success', 'Поколение успешно удалено');
    }

    // --- AJAX HELPERS ---
    public function getModelsByBrand(Request $request)
    {
        $brandId = $request->query('brand_id');
        $page = $request->query('page', 1);
        $perPage = 10;
        if (!$brandId) return response()->json([]);
        $models = CarModel::where('brand_id', $brandId)->paginate($perPage, ['*'], 'page', $page);
        return response()->json([
            'data' => $models->items(),
            'next_page_url' => $models->hasMorePages() ? $models->url($models->currentPage() + 1) : null
        ]);
    }

    public function getGenerationsByModel(Request $request)
    {
        $modelId = $request->query('model_id');
        $page = $request->query('page', 1);
        $perPage = 10;
        if (!$modelId) return response()->json([]);
        $generations = Generation::where('car_model_id', $modelId)->paginate($perPage, ['*'], 'page', $page);
        return response()->json([
            'data' => $generations->items(),
            'next_page_url' => $generations->hasMorePages() ? $generations->url($generations->currentPage() + 1) : null
        ]);
    }

    public function getAllModels(Request $request)
    {
        $brandId = $request->query('brand_id');
        if (!$brandId) return response()->json([]);
        return response()->json(CarModel::where('brand_id', $brandId)->get());
    }

    public function getAllGenerations(Request $request)
    {
        $modelId = $request->query('model_id');
        if (!$modelId) return response()->json([]);
        return response()->json(Generation::where('car_model_id', $modelId)->get());
    }

    public function getPaginatedModels(Request $request)
    {
        $brandId = $request->query('brand_id');
        $highlightId = $request->query('highlight_id');
        $page = $request->query('page', 1);
        $perPage = 10;
        $query = CarModel::where('brand_id', $brandId);
        $models = $query->paginate($perPage, ['*'], 'page', $page);
        $items = $models->items();
        if ($highlightId && $page === 1) {
            $items = collect($items)->reject(fn($item) => $item->id == $highlightId)->values()->all();
        }
        return response()->json([
            'data' => $items,
            'next_page_url' => $models->hasMorePages() ? $models->url($models->currentPage() + 1) : null
        ]);
    }

    public function getModelById(Request $request)
    {
        $id = $request->query('id');
        $model = CarModel::find($id);
        return response()->json($model);
    }

    public function getGenerationById(Request $request)
    {
        $id = $request->query('id');
        $gen = Generation::find($id);
        if (!$gen) {
            return response()->json(null, 404);
        }
        return response()->json($gen);
    }

    public function getPaginatedGenerations(Request $request)
    {
        $modelId = $request->query('model_id');
        $highlightId = $request->query('highlight_id');
        $page = $request->query('page', 1);
        $perPage = 10;
        $query = Generation::where('car_model_id', $modelId);
        $generations = $query->paginate($perPage, ['*'], 'page', $page);
        $items = $generations->items();
        if ($highlightId && $page === 1) {
            $items = collect($items)->reject(fn($item) => $item->id == $highlightId)->values()->all();
        }
        return response()->json([
            'data' => $items,
            'next_page_url' => $generations->hasMorePages() ? $generations->url($generations->currentPage() + 1) : null
        ]);
    }


    public function getPaginatedBrands(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 10;

        $brands = Brand::withCount('models')->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $brands->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'logo' => $brand->logo,
                    'models_count' => $brand->models_count,
                ];
            }),
            'next_page_url' => $brands->hasMorePages() ? $brands->url($page + 1) : null,
        ]);
    }

    public function getBrandById($id)
    {
        $brand = Brand::with('models')->find($id);

        if (!$brand) {
            return response()->json(['error' => 'Марка не найдена'], 404);
        }

        // Формируем структуру данных
        return response()->json([
            'id' => $brand->id,
            'name' => $brand->name,
            'logo' => $brand->logo,
            'models' => $brand->models->map(function ($model) {
                return [
                    'id' => $model->id,
                    'name' => $model->name,
                    'brand_id' => $model->brand_id,
                ];
            }),
        ]);
    }
}