<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\CarModel;
use App\Models\Generation;
use App\Models\Equipment;

Route::get('/models', function(Request $request) {
    return CarModel::when($request->brand_id, function($q) use ($request) {
        $q->where('brand_id', $request->brand_id);
    })->get();
});

Route::get('/generations', function(Request $request) {
    return Generation::when($request->model_id, function($q) use ($request) {
        $q->where('car_model_id', $request->model_id);
    })->get();
});

Route::get('/equipments', function(Request $request) {
    return Equipment::when($request->generation_id, function($q) use ($request) {
        $q->where('generation_id', $request->generation_id);
    })->get();
});