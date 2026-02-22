<?php

namespace Modules\Fabric\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Fabric\Entities\Catalog;

class CatalogController extends Controller
{
    public function index()
    {
        return view('fabric::catalogs.index');
    }

    public function create()
    {
        return view('fabric::create');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate(['name' => 'required|string|unique:catalogs,name']);
        $catalog = Catalog::create($data);
        return response()->json($catalog, 201);
    }

    public function show(Catalog $catalog)
    {
        return view('fabric::catalogs.show', $catalog);
    }

    public function edit($id)
    {
        return view('fabric::edit');
    }
    
    public function update(Request $request, Catalog $catalog): JsonResponse
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // Проверка уникальности, исключая текущую запись
                Rule::unique('catalogs', 'name')->ignore($catalog->id),
            ],
        ]);

        $catalog->update($data);

        return response()->json([
            'message' => 'Каталог успешно обновлен',
            'data'    => $catalog
        ]);
    }
}
