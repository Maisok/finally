<?php

namespace App\Http\Controllers\Admin;

use App\Models\BodyType;
use App\Models\Country;
use App\Models\DriveType;
use App\Models\EngineType;
use App\Models\Branch;
use App\Models\TransmissionType;
use App\Models\Equipment;
use App\Models\Car;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;

class ReferenceController extends Controller
{
    public function index(Request $request)
    {
        // Поиск
        $search = $request->input('search');

        // Получаем номера страниц
        $bodyTypePage = $request->input('page_body_type', 1);
        $countryPage = $request->input('page_country', 1);
        $driveTypePage = $request->input('page_drive_type', 1);
        $engineTypePage = $request->input('page_engine_type', 1);
        $transmissionTypePage = $request->input('page_transmission_type', 1);
        $branchPage = $request->input('page_branch', 1);

        // Фильтрация и пагинация для каждого справочника
        $bodyTypes = BodyType::when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->paginate(10, ['*'], 'page_body_type', $bodyTypePage);
        $countries = Country::when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->paginate(10, ['*'], 'page_country', $countryPage);
        $driveTypes = DriveType::when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->paginate(10, ['*'], 'page_drive_type', $driveTypePage);
        $engineTypes = EngineType::when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->paginate(10, ['*'], 'page_engine_type', $engineTypePage);
        $transmissionTypes = TransmissionType::when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->paginate(10, ['*'], 'page_transmission_type', $transmissionTypePage);
        $branches = Branch::when($search, fn($q) => $q->where('name', 'like', "%$search%")->orWhere('address', 'like', "%$search%"))
            ->paginate(10, ['*'], 'page_branch', $branchPage);

        // Флаги для открытия аккордеона при совпадении поиска
        $bodyTypesMatched = $bodyTypes->total() > 0 && $search;
        $countriesMatched = $countries->total() > 0 && $search;
        $driveTypesMatched = $driveTypes->total() > 0 && $search;
        $engineTypesMatched = $engineTypes->total() > 0 && $search;
        $transmissionTypesMatched = $transmissionTypes->total() > 0 && $search;
        $branchesMatched = $branches->total() > 0 && $search;

        return view('admin.references.index', compact(
            'bodyTypes',
            'countries',
            'driveTypes',
            'engineTypes',
            'transmissionTypes',
            'branches',
            'bodyTypesMatched',
            'countriesMatched',
            'driveTypesMatched',
            'engineTypesMatched',
            'transmissionTypesMatched',
            'branchesMatched'
        ));
    }

    // ————————————————————————————————
    // BODY TYPE
    // ————————————————————————————————

    public function storeBodyType(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:body_types,name',
            ],
        ], [
            'name.required' => 'Пожалуйста, введите название типа кузова.',
            'name.string' => 'Название должно быть строкой.',
            'name.max' => 'Название не может превышать 50 символов.',
            'name.unique' => 'Такой тип кузова уже существует.',
        ]);

        BodyType::create($validated);
        return redirect()->route('admin.references.index')->with('success', 'Тип кузова успешно добавлен');
    }

    public function updateBodyType(Request $request, BodyType $bodyType)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('body_types')->ignore($bodyType->id),
            ],
        ], [
            'name.required' => 'Пожалуйста, введите новое название типа кузова.',
            'name.string' => 'Название должно быть строкой.',
            'name.max' => 'Название не может превышать 50 символов.',
            'name.unique' => 'Такой тип кузова уже существует.',
        ]);

        $bodyType->update($validated);
        return redirect()->route('admin.references.index')->with('success', 'Тип кузова успешно обновлен');
    }

    public function destroyBodyType(BodyType $bodyType)
    {
        if ($bodyType->equipments()->exists()) {
            return back()->with('error', 'Невозможно удалить тип кузова, так как он используется в комплектациях');
        }

        $bodyType->delete();
        return redirect()->route('admin.references.index')->with('success', 'Тип кузова успешно удален');
    }

    // ————————————————————————————————
    // COUNTRY
    // ————————————————————————————————

    public function storeCountry(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:countries,name',
            ],
        ], [
            'name.required' => 'Пожалуйста, введите название страны.',
            'name.string' => 'Название должно быть строкой.',
            'name.max' => 'Название не может превышать 50 символов.',
            'name.unique' => 'Такая страна уже существует.',
        ]);

        Country::create($validated);
        return redirect()->route('admin.references.index')->with('success', 'Страна успешно добавлена');
    }

    public function updateCountry(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('countries')->ignore($country->id),
            ],
        ], [
            'name.required' => 'Пожалуйста, введите новое название страны.',
            'name.string' => 'Название должно быть строкой.',
            'name.max' => 'Название не может превышать 50 символов.',
            'name.unique' => 'Такая страна уже существует.',
        ]);

        $country->update($validated);
        return redirect()->route('admin.references.index')->with('success', 'Страна успешно обновлена');
    }

    public function destroyCountry(Country $country)
    {
        if ($country->equipments()->exists()) {
            return back()->with('error', 'Невозможно удалить страну, так как она используется в комплектациях');
        }

        $country->delete();
        return redirect()->route('admin.references.index')->with('success', 'Страна успешно удалена');
    }

    // ————————————————————————————————
    // DRIVE TYPE
    // ————————————————————————————————

    public function storeDriveType(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:drive_types,name',
            ],
        ], [
            'name.required' => 'Пожалуйста, введите название типа привода.',
            'name.string' => 'Название должно быть строкой.',
            'name.max' => 'Название не может превышать 50 символов.',
            'name.unique' => 'Такой тип привода уже существует.',
        ]);

        DriveType::create($validated);
        return redirect()->route('admin.references.index')->with('success', 'Тип привода успешно добавлен');
    }

    public function updateDriveType(Request $request, DriveType $driveType)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('drive_types')->ignore($driveType->id),
            ],
        ], [
            'name.required' => 'Пожалуйста, введите новое название типа привода.',
            'name.string' => 'Название должно быть строкой.',
            'name.max' => 'Название не может превышать 50 символов.',
            'name.unique' => 'Такой тип привода уже существует.',
        ]);

        $driveType->update($validated);
        return redirect()->route('admin.references.index')->with('success', 'Тип привода успешно обновлен');
    }

    public function destroyDriveType(DriveType $driveType)
    {
        if ($driveType->equipments()->exists()) {
            return back()->with('error', 'Невозможно удалить привод, так как он используется в комплектациях');
        }

        $driveType->delete();
        return redirect()->route('admin.references.index')->with('success', 'Тип привода успешно удален');
    }

    // ————————————————————————————————
    // ENGINE TYPE
    // ————————————————————————————————

    public function storeEngineType(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:engine_types,name',
            ],
        ], [
            'name.required' => 'Пожалуйста, введите название типа двигателя.',
            'name.string' => 'Название должно быть строкой.',
            'name.max' => 'Название не может превышать 50 символов.',
            'name.unique' => 'Такой тип двигателя уже существует.',
        ]);

        EngineType::create($validated);
        return redirect()->route('admin.references.index')->with('success', 'Тип двигателя успешно добавлен');
    }

    public function updateEngineType(Request $request, EngineType $engineType)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('engine_types')->ignore($engineType->id),
            ],
        ], [
            'name.required' => 'Пожалуйста, введите новое название типа двигателя.',
            'name.string' => 'Название должно быть строкой.',
            'name.max' => 'Название не может превышать 50 символов.',
            'name.unique' => 'Такой тип двигателя уже существует.',
        ]);

        $engineType->update($validated);
        return redirect()->route('admin.references.index')->with('success', 'Тип двигателя успешно обновлен');
    }

    public function destroyEngineType(EngineType $engineType)
    {
        if ($engineType->equipments()->exists()) {
            return back()->with('error', 'Невозможно удалить тип двигателя, так как он используется в комплектациях');
        }

        $engineType->delete();
        return redirect()->route('admin.references.index')->with('success', 'Тип двигателя успешно удален');
    }

    // ————————————————————————————————
    // TRANSMISSION TYPE
    // ————————————————————————————————

    public function storeTransmissionType(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:transmission_types,name',
            ],
        ], [
            'name.required' => 'Пожалуйста, введите название типа КПП.',
            'name.string' => 'Название должно быть строкой.',
            'name.max' => 'Название не может превышать 50 символов.',
            'name.unique' => 'Такой тип КПП уже существует.',
        ]);

        TransmissionType::create($validated);
        return redirect()->route('admin.references.index')->with('success', 'Тип КПП успешно добавлен');
    }

    public function updateTransmissionType(Request $request, TransmissionType $transmissionType)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('transmission_types')->ignore($transmissionType->id),
            ],
        ], [
            'name.required' => 'Пожалуйста, введите новое название типа КПП.',
            'name.string' => 'Название должно быть строкой.',
            'name.max' => 'Название не может превышать 50 символов.',
            'name.unique' => 'Такой тип КПП уже существует.',
        ]);

        $transmissionType->update($validated);
        return redirect()->route('admin.references.index')->with('success', 'Тип КПП успешно обновлен');
    }

    public function destroyTransmissionType(TransmissionType $transmissionType)
    {
        if ($transmissionType->equipments()->exists()) {
            return back()->with('error', 'Невозможно удалить тип КПП, так как он используется в комплектациях');
        }

        $transmissionType->delete();
        return redirect()->route('admin.references.index')->with('success', 'Тип КПП успешно удален');
    }

    // ————————————————————————————————
    // BRANCH
    // ————————————————————————————————

    public function storeBranch(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:branches,name',
            ],
            'address' => [
                'required',
                'string',
                'max:100',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[а-яА-ЯёЁ\s\d\-\,\.\\/\(\)]{5,100}$/u', $value)) {
                        $fail('Введите корректный адрес (например: Москва, Велозаводская улица, 13с1)');
                    }
                },
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'name.required' => 'Пожалуйста, введите название филиала.',
            'name.string' => 'Название должно быть строкой.',
            'name.max' => 'Название не может превышать 50 символов.',
            'name.unique' => 'Филиал с таким названием уже существует.',
            'address.required' => 'Пожалуйста, введите адрес филиала.',
            'address.string' => 'Адрес должен быть строкой.',
            'address.max' => 'Адрес не может превышать 100 символов.',
            'image.image' => 'Загрузите корректное изображение (jpeg, png, jpg, gif, webp).',
            'image.mimes' => 'Поддерживаются только форматы: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Изображение не должно превышать 2 МБ.',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('branches', 'public');
        }

        Branch::create($validated);
        return redirect()->route('admin.references.index')->with('success', 'Филиал успешно добавлен');
    }

    public function updateBranch(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('branches')->ignore($branch->id),
            ],
            'address' => [
                'required',
                'string',
                'max:100',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[а-яА-ЯёЁ\s\d\-\,\.\\/\(\)]{5,100}$/u', $value)) {
                        $fail('Введите корректный адрес (например: Москва, Велозаводская улица, 13с1)');
                    }
                },
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'name.required' => 'Пожалуйста, введите название филиала.',
            'name.string' => 'Название должно быть строкой.',
            'name.max' => 'Название не может превышать 50 символов.',
            'name.unique' => 'Филиал с таким названием уже существует.',
            'address.required' => 'Пожалуйста, введите адрес филиала.',
            'address.string' => 'Адрес должен быть строкой.',
            'address.max' => 'Адрес не может превышать 100 символов.',
            'image.image' => 'Загрузите корректное изображение (jpeg, png, jpg, gif, webp).',
            'image.mimes' => 'Поддерживаются только форматы: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Изображение не должно превышать 2 МБ.',
        ]);

        if ($request->hasFile('image')) {
            if ($branch->image && Storage::disk('public')->exists($branch->image)) {
                Storage::disk('public')->delete($branch->image);
            }
            $validated['image'] = $request->file('image')->store('branches', 'public');
        }

        $branch->update($validated);
        return redirect()->route('admin.references.index')->with('success', 'Филиал успешно обновлен');
    }

    public function destroyBranch(Branch $branch)
    {
        if ($branch->cars()->exists()) {
            return back()->with('error', 'Невозможно удалить филиал, так как он используется в автомобилях');
        }

        if ($branch->image && Storage::disk('public')->exists($branch->image)) {
            Storage::disk('public')->delete($branch->image);
        }

        $branch->delete();
        return redirect()->route('admin.references.index')->with('success', 'Филиал успешно удален');
    }
}