<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CarStructureController;
use App\Http\Controllers\Admin\ReferenceController;
use App\Http\Controllers\Admin\EquipmentController;
use App\Models\Equipment;
use App\Models\CarModel;
use App\Models\CarColor;
use App\Models\Brand;
use App\Http\Controllers\AjaxController;
use App\Models\Branch;
use App\Models\Generation;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\ManagerBookingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CarExportController;
use App\Http\Controllers\BookingExportController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\ExportController;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');


Route::middleware(['auth', 'role:superadmin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Список админов
        Route::get('/admins', [AdminController::class, 'index'])
            ->name('admins.index');

        // Форма создания админа
        Route::get('/admins/create', [AdminController::class, 'create'])
            ->name('admins.create');

        // Сохранение нового админа
        Route::post('/admins', [AdminController::class, 'store'])
            ->name('admins.store');

        // Форма редактирования админа
        Route::get('/admins/{user}/edit', [AdminController::class, 'edit'])
            ->name('admins.edit');

        // Обновление данных админа
        Route::put('/admins/{user}', [AdminController::class, 'update'])
            ->name('admins.update');

        Route::delete('/admins/{user}', [AdminController::class, 'destroy'])->name('admins.destroy');
    });

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AuthController::class, 'index'])->name('dashboard');
    Route::put('/profile', [AuthController::class, 'update'])->name('profile.update');
    Route::put('/password', [AuthController::class, 'updatePassword'])->name('password.edit');
    Route::post('/cars/{car}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::delete('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.all');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.all');
    Route::post('/cars/{car}/favorite', [CarController::class, 'favorite'])->name('cars.favorite');
    Route::delete('/favorites/{equipmentId}', [CarController::class, 'removeFromFavorites'])->name('favorites.remove');
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::get('/notifications/list', [NotificationController::class, 'list'])->name('notifications.index');
});


// Password Reset Routes
Route::middleware('guest')->group(function () {
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');
                
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store']) 
                ->name('password.email');
                
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');
                
    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.update');
});

// Email Verification Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
         ->name('verification.notice');
         
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
         ->middleware(['signed'])
         ->name('verification.verify');
         
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
         ->middleware(['throttle:6,1'])
         ->name('verification.send');
});


Route::middleware(['auth', 'role:manager'])->prefix('manager')->group(function () {
    Route::get('/bookings', [ManagerBookingController::class, 'index'])->name('manager.bookings.index');
    Route::get('/bookings/edit/{booking}', [ManagerBookingController::class, 'edit'])->name('manager.bookings.edit');
    Route::put('/bookings/{booking}', [ManagerBookingController::class, 'update'])->name('manager.bookings.update');
    Route::delete('/bookings/{booking}', [ManagerBookingController::class, 'destroy'])->name('manager.bookings.destroy');
    Route::get('/bookings/export', [BookingExportController::class, 'export'])->name('bookings.export');
    Route::post('/bookings/{booking}/assign', [ManagerBookingController::class, 'assignToManager'])->name('manager.bookings.assign');
});

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/api/admin/brands', [CarStructureController::class, 'getPaginatedBrands']);
    // Получение марки по ID (для поиска через селект)
    Route::get('/api/admin/brand/{id}', [CarStructureController::class, 'getBrandById']);

    Route::get('/api/admin/generations', [GenerationController::class, 'getPaginatedGenerations']);

    Route::get('/api/addcars/equipment/{equipment}/colors', [AjaxController::class, 'colors'])->name('api.equipment.colors');

    Route::get('/admin/cars/export/sales-report', function (Request $request) {
        // Получаем все параметры из GET-запроса
        $filters = $request->only([
            'brand_id',
            'model_id',
            'generation_id',
            'equipment_id',
            'start_date',
            'end_date'
        ]);
    
        return Excel::download(
            new SalesReportExport($filters),
            'sales_report_' . now()->format('Y-m-d_H-i') . '.xlsx'
        );
    })->name('admin.cars.export.sales-report');

Route::get('/export/bookings', [ExportController::class, 'exportBookings'])->name('export.bookings');
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/managers', [ManagerController::class, 'index'])->name('managers.index');
        Route::post('/managers', [ManagerController::class, 'store'])->name('managers.store');
        Route::put('/managers/{manager}', [ManagerController::class, 'update'])->name('managers.update');
        Route::delete('/managers/{manager}', [ManagerController::class, 'destroy'])->name('managers.destroy');
    
        Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
        Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create');
        Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
        Route::get('/cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
        Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
        Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');
    
        Route::get('/references', [ReferenceController::class, 'index'])->name('references.index');
    
        Route::post('/body-types', [ReferenceController::class, 'storeBodyType'])->name('body-types.store');
        Route::put('/body-types/{bodyType}', [ReferenceController::class, 'updateBodyType'])->name('body-types.update');
        Route::delete('/body-types/{bodyType}', [ReferenceController::class, 'destroyBodyType'])->name('body-types.destroy');
    
        Route::post('/countries', [ReferenceController::class, 'storeCountry'])->name('countries.store');
        Route::put('/countries/{country}', [ReferenceController::class, 'updateCountry'])->name('countries.update');
        Route::delete('/countries/{country}', [ReferenceController::class, 'destroyCountry'])->name('countries.destroy');
    
        Route::post('/drive-types', [ReferenceController::class, 'storeDriveType'])->name('drive-types.store');
        Route::put('/drive-types/{driveType}', [ReferenceController::class, 'updateDriveType'])->name('drive-types.update');
        Route::delete('/drive-types/{driveType}', [ReferenceController::class, 'destroyDriveType'])->name('drive-types.destroy');
    
        Route::post('/engine-types', [ReferenceController::class, 'storeEngineType'])->name('engine-types.store');
        Route::put('/engine-types/{engineType}', [ReferenceController::class, 'updateEngineType'])->name('engine-types.update');
        Route::delete('/engine-types/{engineType}', [ReferenceController::class, 'destroyEngineType'])->name('engine-types.destroy');
    
        Route::post('/transmission-types', [ReferenceController::class, 'storeTransmissionType'])->name('transmission-types.store');
        Route::put('/transmission-types/{transmissionType}', [ReferenceController::class, 'updateTransmissionType'])->name('transmission-types.update');
        Route::delete('/transmission-types/{transmissionType}', [ReferenceController::class, 'destroyTransmissionType'])->name('transmission-types.destroy');
    
        Route::post('/branches', [ReferenceController::class, 'storeBranch'])->name('branches.store');
        Route::put('/branches/{branch}', [ReferenceController::class, 'updateBranch'])->name('branches.update');
        Route::delete('/branches/{branch}', [ReferenceController::class, 'destroyBranch'])->name('branches.destroy');
    
        Route::delete('/equipments/{equipment}', [EquipmentController::class, 'destroy'])->name('equipments.destroy');
        Route::get('/equipments', [EquipmentController::class, 'index'])->name('equipments.index');
        Route::get('/equipments/create', [EquipmentController::class, 'create'])->name('equipments.create');
        Route::post('/equipments', [EquipmentController::class, 'store'])->name('equipments.store');
        Route::get('/equipments/{equipment}/edit', [EquipmentController::class, 'edit'])->name('equipments.edit');
        Route::put('/equipments/{equipment}', [EquipmentController::class, 'update'])->name('equipments.update');
        Route::delete('/equipments/{equipment}/colors/{color}', [EquipmentController::class, 'detachColor'])->name('equipment.colors.detach');
        Route::get('/cars/export/not-sold', [CarExportController::class, 'exportNotSold'])->name('cars.export.not_sold');
    });

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
    Route::get('/bookings/{booking}/edit-manager', [AdminBookingController::class, 'editManager'])->name('admin.bookings.edit-manager');
    Route::put('/bookings/{booking}/edit-manager', [AdminBookingController::class, 'updateManager'])->name('admin.bookings.update-manager');



    Route::prefix('colors')->group(function () {
        Route::post('/update', [EquipmentController::class, 'updateColor'])->name('admin.colors.update');
        Route::post('/delete', [EquipmentController::class, 'deleteColor'])->name('admin.colors.delete');
    });

    Route::get('/api/brand/{brandId}/models', function (\App\Models\Brand $brandId) {
        return response()->json($brandId->models);
    });
    
    // Получить поколения по модели
    Route::get('/api/model/{modelId}/generations', function (\App\Models\CarModel $modelId) {
        return response()->json($modelId->generations);
    });

    Route::get('/api/brand/{brandId}/models', function (\App\Models\Brand $brandId) {
        return response()->json($brandId->models);
    });
    
    Route::get('/api/model/{modelId}/generations', function (\App\Models\CarModel $modelId) {
        return response()->json($modelId->generations);
    });

    Route::get('/car-structure', [CarStructureController::class, 'index'])->name('admin.car-structure.index');

    Route::get('/api/model', [CarStructureController::class, 'getModelById']);
    // routes/api.php
    Route::get('/api/generation', [CarStructureController::class, 'getGenerationById']);
    // API для динамических селектов
    Route::get('/api/brands', [CarStructureController::class, 'getAllBrands']);
    Route::get('/api/models', [CarStructureController::class, 'getModelsByBrand']);
    Route::get('/api/generations', [CarStructureController::class, 'getGenerationsByModel']);

    // Для селектов (все элементы сразу)
    Route::get('/api/all/models', [CarStructureController::class, 'getAllModels']);
    Route::get('/api/all/generations', [CarStructureController::class, 'getAllGenerations']);

    // Для дерева (с пагинацией)
    Route::get('/api/paginated/models', [CarStructureController::class, 'getPaginatedModels']);
    Route::get('/api/paginated/generations', [CarStructureController::class, 'getPaginatedGenerations']);

    // API для Select2
    Route::get('/api/brand/{brandId}/models', function (\App\Models\Brand $brandId) {
        return response()->json($brandId->models);
    });

    Route::get('/api/generation/{generationId}', function (\App\Models\Generation $generationId) {
        return response()->json($generationId->load('carModel.brand'));
    });

    Route::get('/api/model/{modelId}/generations', function (\App\Models\CarModel $modelId) {
        return response()->json($modelId->generations);
    });

    Route::get('/api/generation/{generationId}/equipments', function (\App\Models\Generation $generationId) {
        return response()->json($generationId->equipments);
    });

    // Для моделей (с поддержкой пагинации и highlight)
    Route::get('/models', [CarStructureController::class, 'getPaginatedModels']);

    // Для поколений (с поддержкой пагинации и highlight)
    Route::get('/generations', [CarStructureController::class, 'getPaginatedGenerations']);

    // Форма добавления и т.д.
    Route::post('/brands', [CarStructureController::class, 'storeBrand'])->name('brands.store');
    Route::put('/brands/{brand}', [CarStructureController::class, 'updateBrand'])->name('brands.update');
    Route::delete('/brands/{brand}', [CarStructureController::class, 'destroyBrand'])->name('brands.destroy');

    Route::post('/models', [CarStructureController::class, 'storeModel'])->name('models.store');
    Route::put('/models/{model}', [CarStructureController::class, 'updateModel'])->name('models.update');
    Route::delete('/models/{model}', [CarStructureController::class, 'destroyModel'])->name('models.destroy');

    Route::post('/generations', [CarStructureController::class, 'storeGeneration'])->name('generations.store');
    Route::put('/generations/{generation}', [CarStructureController::class, 'updateGeneration'])->name('generations.update');
    Route::delete('/generations/{generation}', [CarStructureController::class, 'destroyGeneration'])->name('generations.destroy');

});



Route::get('/', [MainController::class, 'index'])->name('welcome');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/cars/{car}', [CatalogController::class, 'show'])->name('cars.show');





Route::get('/brand/{brand}/models', function (Brand $brand) {
    return response()->json($brand->models);
})->name('api.brand.models');

Route::get('/model/{model}/generations', function ($modelId) {
    return Generation::where('car_model_id', $modelId)->get(['id', 'name']);
});

Route::get('/api/equipment/{equipment}/colors', function (Equipment $equipment) {
    return response()->json($equipment->colors);
});

Route::get('/branches/{branch}/cars', function (Branch $branch) {
    return $branch->cars->map(function($car) {
        return [
            'id' => $car->id,
            'brand' => $car->equipment->generation->carModel->brand->name,
            'model' => $car->equipment->generation->carModel->name,
            'year' => $car->equipment->generation->year_from,
            'mileage' => $car->mileage,
            'price' => $car->price,
            'main_image' => $car->mainImage
        ];
    });
});


Route::get('/catalog/api/models', [AjaxController::class, 'getModels']);
Route::get('/catalog/api/generations', [AjaxController::class, 'getGenerations']);
Route::get('/catalog/api/equipments', [AjaxController::class, 'getEquipments']);