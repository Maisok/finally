<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarModel;
use App\Models\Generation;
use App\Models\Equipment;

use Illuminate\Http\JsonResponse;

class AjaxController extends Controller
{
    public function getModels(Request $request)
    {
        $query = CarModel::query();
        
        if ($request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }
        
        if ($request->search) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        
        return $query->select('id', 'name')->get();
    }
    
    public function getGenerations(Request $request)
    {
        $query = Generation::query();
        
        if ($request->model_id) {
            $query->where('car_model_id', $request->model_id);
        }
        
        if ($request->search) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        
        return $query->select('id', 'name', 'year_from')->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'text' => "{$item->name} ({$item->year_from})"
                ];
            });
    }
    
    public function getEquipments(Request $request)
    {
        $query = Equipment::query();
        
        if ($request->generation_id) {
            $query->where('generation_id', $request->generation_id);
        }
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%');
            });
        }
        
        return $query->select('id', 'name')
                    ->get()
                    ->map(function($item) {
                        return [
                            'id' => $item->id,
                            'text' => $item->name
                        ];
                    });
    }

    public function colors(Equipment $equipment): JsonResponse
    {
        $colors = $equipment->colors;

        return response()->json($colors);
    }

    public function getPaginatedBrands(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 10;

        $brands = Brand::paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $brands->items(),
            'next_page_url' => $brands->hasMorePages() ? $brands->url($page + 1) : null,
        ]);
    }

    public function getPaginatedModels(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 10;
        $brandId = $request->input('brand_id');

        $models = CarModel::where('brand_id', $brandId)->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $models->items(),
            'next_page_url' => $models->hasMorePages() ? $models->url($page + 1) : null,
        ]);
    }

    public function getPaginatedGenerations(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 10;
        $modelId = $request->input('model_id');

        $generations = Generation::where('model_id', $modelId)->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $generations->items(),
            'next_page_url' => $generations->hasMorePages() ? $generations->url($page + 1) : null,
        ]);
    }
}
