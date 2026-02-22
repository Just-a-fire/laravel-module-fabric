<?php

namespace Modules\Fabric\App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Fabric\Entities\Fabric;
use Modules\Fabric\App\resources\FabricResource;
use Modules\Fabric\App\Rules\CheckColorPercentage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FabricApiController extends Controller
{
    /**
     * Получить список всех тканей (с фильтрацией)
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Fabric::with(['catalog', 'colors']);

        // Фильтр по артиклу
        if ($request->filled('article')) {
            $query->where('article', 'like', '%' . $request->article . '%');
        }

        // Фильтр по архиву
        if ($request->has('is_archived')) {
            $query->where('is_archived', $request->boolean('is_archived'));
        }

        return FabricResource::collection($query->paginate(20));
    }
    
    public function show(Fabric $fabric): FabricResource
    {
        return new FabricResource($fabric->load(['catalog', 'colors']));
    }

    public function store(Request $request)
    {
        // Валидация должна возвращать 422 автоматически
        $data = $request->validate([
            'article'    => 'required|unique:fabrics,article',
            'catalog_id' => 'required|exists:catalogs,id',
            'colors'     => ['required', 'array', new CheckColorPercentage],
            'colors.*.id' => 'required|exists:colors,id',
            'colors.*.percentage' => 'required|integer',
        ]);

        $fabric = DB::transaction(function () use ($request) {
            $fabric = Fabric::create($request->safe()->except('colors'));

            if ($request->has('colors')) {
                // Преобразуем массив в формат для sync
                $syncData = collect($request->colors)->keyBy('id')->map(function ($item) {
                    return ['percentage' => $item['percentage']];
                });

                $fabric->colors()->sync($syncData);
            }

            return $fabric;
        });

        return new FabricResource($fabric->load('colors'));
    }

    /**
     * Пример метода для смены статуса через API
     */
    public function toggleArchive(Fabric $fabric): FabricResource
    {
        $fabric->update(['is_archived' => !$fabric->is_archived]);
        return new FabricResource($fabric);
    }
}
