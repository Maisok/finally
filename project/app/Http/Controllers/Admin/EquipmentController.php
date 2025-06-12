<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Equipment;
use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\Generation;
use App\Models\BodyType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use App\Models\DriveType;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Zipper;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        // === Обработка поиска комплектаций ===
        $equipmentPage = $request->input('page_equipment', 1); // Номер страницы для комплектаций
        $query = Equipment::with(['generation.carModel.brand', 'colors']);
    
        if ($request->filled('brand_id')) {
            $query->whereHas('generation.carModel', fn($q) => $q->where('brand_id', $request->input('brand_id')));
        }
        if ($request->filled('model_id')) {
            $query->whereHas('generation', fn($q) => $q->where('car_model_id', $request->input('model_id')));
        }
        if ($request->filled('generation_id')) {
            $query->where('generation_id', $request->input('generation_id'));
        }
    
        $equipments = $query->paginate(10, ['*'], 'page_equipment', $equipmentPage);
    
       // === Обработка поиска цветов ===
$colorPage = $request->input('page_color', 1); // Номер страницы для цветов

// Загружаем ВСЕ цвета, а не только те, что связаны с комплектациями
$colorQuery = Color::with('equipments'); // Убрали has('equipments')

if ($request->filled('color_search')) {
    $search = $request->input('color_search');
    $colorQuery->where(function ($q) use ($search) {
        $q->where('name', 'like', "%$search%")
          ->orWhere('hex_code', 'like', "%$search%");
    });
}

if ($request->filled('equipment_id')) {
    $equipmentId = $request->input('equipment_id');
    $colorQuery->whereHas('equipments', function ($q) use ($equipmentId) {
        $q->where('equipment_id', $equipmentId);
    });
}

$colors = $colorQuery->paginate(10, ['*'], 'page_color', $colorPage);
    
        // Для Select2 в форме
        $brands = Brand::all();
        $models = collect();
        $generations = collect();
    
        if ($request->filled('brand_id')) {
            $models = CarModel::where('brand_id', $request->input('brand_id'))->get();
        }
        if ($request->filled('model_id')) {
            $generations = Generation::where('car_model_id', $request->input('model_id'))->get();
        }
    
        // Все комплектации для Select2
        $allEquipments = Equipment::with(['generation.carModel.brand'])->get()->map(function ($eq) {
            $brandName = $eq->generation?->carModel?->brand?->name ?? '';
            $modelName = $eq->generation?->carModel?->name ?? '';
            $generationName = $eq->generation?->name ?? '';
            return [
                'id' => $eq->id,
                'text' => trim("{$brandName} → {$modelName} → {$generationName}", " → "),
            ];
        });
    
        return view('admin.equipments.index', compact(
            'equipments',
            'brands',
            'models',
            'generations',
            'colors',
            'allEquipments'
        ));
    }

    // Форма создания
    public function create()
    {
        $brands = Brand::all();
        $generations = Generation::all();
        $bodyTypes = BodyType::all();
        $engineTypes = EngineType::all();
        $transmissionTypes = TransmissionType::all();
        $driveTypes = DriveType::all();
        $countries = Country::all();
        $colors = Color::all();

        return view('admin.equipments.create', compact(
            'brands', 'generations', 'bodyTypes', 'engineTypes',
            'transmissionTypes', 'driveTypes', 'countries', 'colors'
        ));
    }


    
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Доступ запрещён');
        }
    
        // Определяем динамически правила валидации
        $rules = [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('equipment')->where(fn ($query) => $query->where('generation_id', $request->input('generation_id')))
            ],
            'generation_id' => 'required|exists:generations,id',
            'body_type_id' => 'required|exists:body_types,id',
            'engine_type_id' => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id' => 'required|exists:drive_types,id',
            'country_id' => 'required|exists:countries,id',
            'engine_volume' => 'required|numeric|min:0.8|max:8.0',
            'engine_power' => 'required|integer|min:40|max:2000',
            'description' => 'required|string|max:1000',
            'range' => 'required|integer|min:50|max:1000',
            'max_speed' => 'required|integer|min:50|max:450',
            'model_folder' => 'nullable|file|mimes:zip|max:51200',
    
            // Цвета (массив для временной валидации)
            'colors' => 'nullable|array|exists:colors,id',
            'new_colors' => 'nullable|array',
        ];
    
        // Условно добавляем правила для new_colors только если НИЧЕГО не выбрано в селекте colors
        if (empty($request->input('colors'))) {
            $rules['new_colors.*.name'] = 'required|string|max:50';
            $rules['new_colors.*.hex'] = [
                'required',
                'regex:/^#([A-Fa-f0-9]{6})$/i'
            ];
        }
    
        // Валидируем запрос
        $data = $request->validate($rules, [
            'name.required' => 'Поле "Название" обязательно.',
            'name.string' => 'Поле "Название" должно быть строкой.',
            'name.max' => 'Поле "Название" не должно превышать 50 символов.',
            'name.unique' => 'Комплектация с таким названием уже существует для этого поколения.',
            'generation_id.required' => 'Поле "Поколение" обязательно.',
            'generation_id.exists' => 'Выбранное поколение не существует.',
            'body_type_id.required' => 'Поле "Тип кузова" обязательно.',
            'body_type_id.exists' => 'Выбранный тип кузова не существует.',
            'engine_type_id.required' => 'Поле "Тип двигателя" обязательно.',
            'engine_type_id.exists' => 'Выбранный тип двигателя не существует.',
            'transmission_type_id.required' => 'Поле "Тип КПП" обязательно.',
            'transmission_type_id.exists' => 'Выбранный тип КПП не существует.',
            'drive_type_id.required' => 'Поле "Привод" обязательно.',
            'drive_type_id.exists' => 'Выбранный привод не существует.',
            'country_id.required' => 'Поле "Страна сборки" обязательно.',
            'country_id.exists' => 'Выбранная страна не существует.',
            'engine_volume.required' => 'Поле "Объем двигателя" обязательно.',
            'engine_volume.numeric' => 'Объем двигателя должен быть числом.',
            'engine_volume.min' => 'Объем двигателя не может быть меньше 0.8.',
            'engine_volume.max' => 'Объем двигателя не может быть больше 8.0.',
            'engine_power.required' => 'Поле "Мощность двигателя" обязательно.',
            'engine_power.integer' => 'Мощность двигателя должна быть целым числом.',
            'engine_power.min' => 'Мощность двигателя не может быть меньше 40.',
            'engine_power.max' => 'Мощность двигателя не может быть больше 2000.',
            'description.required' => 'Поле "Описание" обязательно.',
            'description.string' => 'Описание должно быть строкой.',
            'description.max' => 'Описание не должно превышать 1000 символов.',
            'range.required' => 'Поле "Запас хода" обязательно.',
            'range.integer' => 'Запас хода должен быть целым числом.',
            'range.min' => 'Запас хода не может быть меньше 50.',
            'range.max' => 'Запас хода не может быть больше 1000.',
            'max_speed.required' => 'Поле "Максимальная скорость" обязательно.',
            'max_speed.integer' => 'Максимальная скорость должна быть целым числом.',
            'max_speed.min' => 'Максимальная скорость не может быть меньше 50.',
            'max_speed.max' => 'Максимальная скорость не может быть больше 450.',
            'model_folder.file' => 'Вы должны загрузить файл.',
            'model_folder.mimes' => 'Формат файла должен быть ZIP.',
            'model_folder.max' => 'Размер файла не должен превышать 50 МБ.',
            'colors.array' => 'Поле "Цвета" должно быть массивом.',
            'colors.*.exists' => 'Один или несколько выбранных цветов не существуют.',
            'new_colors.*.name.required' => 'Название цвета обязательно, если указан HEX код.',
            'new_colors.*.hex.required' => 'HEX код обязателен, если указано название.',
            'new_colors.*.hex.regex' => 'HEX код должен быть в формате #FFFFFF.',
        ]);
    
        // Если выбраны существующие цвета, игнорируем новые
        if ($request->filled('colors') && !$request->filled('new_colors')) {
            $request->merge(['new_colors' => null]);
        }
    
        // Проверка: хотя бы один цвет должен быть выбран/добавлен
        $existingColors = $request->input('colors', []);
        $hasExistingColors = is_array($existingColors) && count($existingColors) > 0;
        
        $newColors = $request->input('new_colors', []);
        $hasNewColors = is_array($newColors) && !empty($newColors);
        
        if (!$hasExistingColors && !$hasNewColors) {
            return back()
                ->withInput()
                ->withErrors(['colors' => 'Необходимо выбрать хотя бы один цвет или добавить новый.']);
        }
    
        DB::beginTransaction();
        try {
            $equipment = Equipment::create($data);
    
            // Обрабатываем модель
            if ($request->hasFile('model_folder')) {
                $this->process3dModel($request->file('model_folder'), $equipment);
            }
    
            // Обработка цветов
            $this->processColors($request, $equipment);
    
            DB::commit();
    
            return redirect()->route('admin.equipments.index')
                ->with('success', 'Комплектация успешно добавлена');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Ошибка при сохранении: ' . $e->getMessage());
        }
    }

    protected function process3dModel($file, $equipment)
    {
        $publicDir = storage_path('app/public');
        $extractPath = $publicDir.'/3d_models/equipment_'.$equipment->id;
    
        // Удаление старой модели, если есть
        if (file_exists($extractPath)) {
            $this->deleteDirectory($extractPath);
        }
    
        // Создаем временную папку, если её нет
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
    
        // Сохраняем временный ZIP
        $tempName = 'model_'.$equipment->id.'_'.time().'.zip';
        $tempPath = $tempDir.'/'.$tempName;
        file_put_contents($tempPath, file_get_contents($file->getRealPath()));
    
        // Распаковка
        $zip = new \ZipArchive;
        if ($zip->open($tempPath) !== true) {
            unlink($tempPath);
            throw new \Exception("Ошибка открытия ZIP");
        }
    
        mkdir($extractPath, 0755, true);
        $zip->extractTo($extractPath);
        $zip->close();
        unlink($tempPath);
    
        // Проверка наличия scene.gltf
        if (!file_exists($extractPath.'/scene.gltf')) {
            $this->deleteDirectory($extractPath);
            throw new \Exception("Файл scene.gltf не найден в архиве");
        }
    
        // Сохраняем путь к модели
        $equipment->update([
            'model_path' => '3d_models/equipment_'.$equipment->id
        ]);
    }

    protected function processColors(Request $request, Equipment $equipment)
    {
        $existingColors = $request->input('colors', []);
        $newColors = $request->input('new_colors', []);
    
        // Прикрепляем существующие
        if (!empty($existingColors)) {
            $equipment->colors()->attach($existingColors);
        }
    
        // Добавляем новые цвета
        foreach ($newColors as $colorData) {
            if (!empty($colorData['name']) && !empty($colorData['hex'])) {
                $color = Color::firstOrCreate(
                    ['hex_code' => $colorData['hex']],
                    ['name' => $colorData['name']]
                );
                $equipment->colors()->attach($color->id);
            }
        }
    }
    // Редактирование
    public function edit(Equipment $equipment)
    {
        $brands = Brand::all();
        $models = collect();
        $generations = collect();

        if ($equipment->generation && $equipment->generation->carModel && $equipment->generation->carModel->brand) {
            $models = $equipment->generation->carModel->brand->models()->get();
        }

        if ($equipment->generation && $equipment->generation->carModel) {
            $generations = $equipment->generation->carModel->generations()->get();
        }

        $bodyTypes = BodyType::all();
        $engineTypes = EngineType::all();
        $transmissionTypes = TransmissionType::all();
        $driveTypes = DriveType::all();
        $countries = Country::all();
        $colors = Color::all();

        return view('admin.equipments.edit', compact(
            'equipment', 'brands', 'models', 'generations',
            'bodyTypes', 'engineTypes', 'transmissionTypes',
            'driveTypes', 'countries', 'colors'
        ));
    }

    
    public function update(Request $request, Equipment $equipment)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Доступ запрещён');
        }
    
        // Подготовка данных для валидации
        $newColors = $request->input('new_colors', []);
        $existingColors = $request->input('colors', []);
    
        // Проверяем, есть ли хоть какие-то цвета
        $hasNewColors = collect($newColors)->filter(fn($c) => !empty($c['name']) && !empty($c['hex']))->isNotEmpty();
        $hasExistingColors = count($existingColors) > 0;
    
        // Если ни новых, ни старых цветов нет — ошибка
        if (!$hasNewColors && !$hasExistingColors) {
            return back()->withErrors(['colors' => 'Необходимо выбрать хотя бы один цвет или добавить новый'])
                         ->withInput();
        }
    
        // Валидация основных данных
        $data = $request->validate([
            // Основные поля остаются без изменений
            'generation_id' => 'required|exists:generations,id',
            'body_type_id' => 'required|exists:body_types,id',
            'engine_type_id' => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id' => 'required|exists:drive_types,id',
            'country_id' => 'required|exists:countries,id',
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('equipment')->where(fn ($query) => $query->where('generation_id', $request->input('generation_id')))
                    ->ignore($equipment->id)
            ],
            'engine_volume' => 'nullable|numeric|min:0.8|max:8.0',
            'engine_power' => 'nullable|integer|min:40|max:2000',
            'description' => 'nullable|string|max:1000',
            'range' => 'nullable|integer|min:50|max:1000',
            'max_speed' => 'nullable|numeric|min:50|max:450',
            'model_folder' => 'nullable|file|mimes:zip|max:51200',
            'remove_model' => 'nullable|boolean',
    
            // Цвета — валидируем только если они используются
            'colors' => $hasNewColors ? 'array|nullable' : 'required|array|min:1',
            'colors.*' => 'exists:colors,id',
            'new_colors' => 'array|nullable',
            'new_colors.*.name' => 'string|max:255|nullable',
            'new_colors.*.hex' => ['regex:/^#([A-Fa-f0-9]{6})$/i', 'nullable'],
        ], [
            // Сообщения об ошибках — как были, так и остаются
            'name.required' => 'Поле "Название" обязательно.',
            'name.string' => 'Поле "Название" должно быть строкой.',
            'name.max' => 'Поле "Название" не должно превышать 50 символов.',
            'name.unique' => 'Комплектация с таким названием уже существует для этого поколения.',
            'generation_id.required' => 'Поле "Поколение" обязательно.',
            'generation_id.exists' => 'Выбранное поколение не существует.',
            'body_type_id.required' => 'Поле "Тип кузова" обязательно.',
            'body_type_id.exists' => 'Выбранный тип кузова не существует.',
            'engine_type_id.required' => 'Поле "Тип двигателя" обязательно.',
            'engine_type_id.exists' => 'Выбранный тип двигателя не существует.',
            'transmission_type_id.required' => 'Поле "Тип КПП" обязательно.',
            'transmission_type_id.exists' => 'Выбранный тип КПП не существует.',
            'drive_type_id.required' => 'Поле "Привод" обязательно.',
            'drive_type_id.exists' => 'Выбранный привод не существует.',
            'country_id.required' => 'Поле "Страна сборки" обязательно.',
            'country_id.exists' => 'Выбранная страна не существует.',
            'engine_volume.numeric' => 'Объем двигателя должен быть числом.',
            'engine_volume.min' => 'Объем двигателя не может быть меньше 0.8.',
            'engine_volume.max' => 'Объем двигателя не может быть больше 8.0.',
            'engine_power.integer' => 'Мощность двигателя должна быть целым числом.',
            'engine_power.min' => 'Мощность двигателя не может быть меньше 40.',
            'engine_power.max' => 'Мощность двигателя не может быть больше 2000.',
            'description.string' => 'Описание должно быть строкой.',
            'description.max' => 'Описание не должно превышать 1000 символов.',
            'range.integer' => 'Запас хода должен быть целым числом.',
            'range.min' => 'Запас хода не может быть меньше 50.',
            'range.max' => 'Запас хода не может быть больше 1000.',
            'max_speed.numeric' => 'Максимальная скорость должна быть числом.',
            'max_speed.min' => 'Максимальная скорость не может быть меньше 50.',
            'max_speed.max' => 'Максимальная скорость не может быть больше 450.',
            'model_folder.file' => 'Вы должны загрузить файл.',
            'model_folder.mimes' => 'Формат файла должен быть ZIP.',
            'model_folder.max' => 'Размер файла не должен превышать 50 МБ.',
            'remove_model.boolean' => 'Поле "Удалить модель" должно быть установлено или нет.',
            'colors.array' => 'Поле "Цвета" должно быть массивом.',
            'colors.min' => 'Необходимо выбрать хотя бы один цвет.',
            'colors.*.exists' => 'Выбранный цвет не существует.',
            'new_colors.*.name.string' => 'Название нового цвета должно быть строкой.',
            'new_colors.*.name.max' => 'Название нового цвета не должно превышать 255 символов.',
            'new_colors.*.hex.regex' => 'HEX код должен быть в формате #FFFFFF.',
        ]);
    
        DB::beginTransaction();
        try {
            // Удаление модели (если нужно)
            if ($request->has('remove_model')) {
                $this->delete3dModel($equipment);
            }
    
            // Загрузка новой модели (если есть)
            if ($request->hasFile('model_folder')) {
                $this->process3dModel($request->file('model_folder'), $equipment);
            }
    
            // Обновляем основные данные
            $equipment->update(array_merge(
                $request->only([
                    'name', 'generation_id', 'body_type_id', 'engine_type_id',
                    'engine_volume', 'engine_power', 'transmission_type_id',
                    'transmission_name', 'drive_type_id', 'country_id',
                    'description', 'weight', 'load_capacity', 'seats',
                    'fuel_consumption', 'fuel_tank_volume', 'battery_capacity',
                    'range', 'max_speed', 'clearance'
                ]),
                ['model_path' => $equipment->model_path]
            ));
    
            // Сбрасываем старые цвета перед обновлением
            $syncData = [];
    
            if ($hasExistingColors) {
                $syncData = array_merge($syncData, $request->input('colors', []));
            }
    
            if ($hasNewColors) {
                foreach ($newColors as $colorData) {
                    if (!empty($colorData['name']) && !empty($colorData['hex'])) {
                        $color = Color::firstOrCreate(
                            ['name' => $colorData['name']],
                            ['hex_code' => $colorData['hex']]
                        );
                        $syncData[] = $color->id;
                    }
                }
            }
    
            $equipment->colors()->sync(array_unique($syncData));
    
            DB::commit();
            return redirect()
                ->route('admin.equipments.index')
                ->with('success', 'Комплектация обновлена');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Ошибка при редактировании комплектации: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Ошибка: ' . $e->getMessage());
        }
    }

    protected function delete3dModel(Equipment $equipment)
    {
        if ($equipment->model_path) {
            $path = storage_path('app/public/' . $equipment->model_path);
            if (file_exists($path)) {
                $this->deleteDirectory($path); // <-- Теперь удаляет правильно
            }
            $equipment->update(['model_path' => null]);
        }
    }

    public function detachColor(Equipment $equipment, Color $color)
    {
        // Сколько всего цветов у этой комплектации?
        $totalColors = $equipment->colors()->count();
    
        if ($totalColors <= 1) {
            return back()->with('error', 'Невозможно удалить последний цвет комплектации');
        }
    
        // Удаляем связь с этим цветом
        $equipment->colors()->detach($color);
    
        return back()->with('success', 'Цвет успешно отвязан');
    }

    public function destroy(Equipment $equipment)
    {
        // Проверяем, есть ли связанные автомобили
        if ($equipment->cars()->exists()) {
            return back()->with('error', 'Невозможно удалить комплектацию, так как существуют автомобили с этой комплектацией.');
        }
    
        // Если нет связанных авто — удаляем
        $equipment->delete();
    
        return back()->with('success', 'Комплектация удалена.');
    }

    public function updateColor(Request $request)
    {
        $validator = Validator::make($request->only('name', 'hex_code', 'color_id'), [
            'name' => 'required|string|max:50',
            'hex_code' => ['required', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'color_id' => 'required|exists:colors,id',
        ], [
            'name.required' => 'Поле "Название" обязательно для заполнения.',
            'name.string' => 'Поле "Название" должно быть строкой.',
            'name.max' => 'Поле "Название" не должно превышать 50 символов.',
            'hex_code.required' => 'Поле "HEX код" обязательно для заполнения.',
            'hex_code.regex' => 'HEX код должен быть в формате #FFFFFF.',
            'color_id.required' => 'ID цвета не указан.',
            'color_id.exists' => 'Такого цвета не существует.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors(['colorErrors' => $validator->errors()->all()])
                ->withInput();
        }

        $color = Color::findOrFail($request->input('color_id'));
        $color->update([
            'name' => $request->input('name'),
            'hex_code' => $request->input('hex_code'),
        ]);

        return back()->with('success', 'Цвет успешно обновлён');
    }

    public function deleteColor(Request $request)
    {
        $request->validate([
            'color_id' => 'required|exists:colors,id',
        ], [
            'color_id.required' => 'ID цвета не указан.',
            'color_id.exists' => 'Такого цвета не существует.',
        ]);
    
        $color = Color::findOrFail($request->input('color_id'));
    
        if ($color->equipments()->exists()) {
            return back()->with('error', 'Невозможно удалить цвет — он используется в комплектациях.');
        }
    
        $color->delete();
        return back()->with('success', 'Цвет успешно удалён');
    }


    /**
 * Рекурсивно удаляет директорию и всё её содержимое
 *
 * @param string $dir
 */
protected function deleteDirectory($dir)
{
    if (!file_exists($dir)) {
        return;
    }

    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;

        $path = $dir . DIRECTORY_SEPARATOR . $item;

        if (is_dir($path)) {
            $this->deleteDirectory($path); // Рекурсия
        } else {
            @unlink($path); // Игнорируем ошибки
        }
    }

    @rmdir($dir); // Игнорируем ошибки
}
}