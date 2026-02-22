<?php

namespace Modules\Fabric\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Fabric\Entities\Fabric;
use Modules\Fabric\App\Http\Requests\FabricStoreRequest;
use Modules\Fabric\App\resources\FabricResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class FabricController extends Controller
{
    public function index()
    {
        $fabrics = Fabric::with('catalog')->get();
        return view('fabric::index', compact('fabrics'));
    }
    
    public function create()
    {
        return view('fabric::create');
    }
    
    public function show(Fabric $fabric)
    {
        $fabric->load(['catalog', 'colors']);
        return view('fabric::show', compact('fabric'));
    }

    public function edit(Fabric $fabric)
    {
        return view('fabric::edit', compact('fabric'));
    }
    
    public function update(Request $request, Fabric $fabric): JsonResponse
    {
        DB::transaction(function () use ($request, $fabric) {
            $fabric->update($request->validated());

            if ($request->has('color_ids')) {
                $fabric->colors()->sync($request->color_ids);
            }
        });

        return response()->json(['message' => 'Fabric updated']);
    }

    public function destroy($id)
    {
        return response()->json(
            ['message' => 'Нельзя удалить ткань, но можно поместить в архив'],
            405
        );
    }

    public function archive(Fabric $fabric): FabricResource
    {
        $fabric->update(['is_archived' => true]);
        return new FabricResource($fabric);
    }
    
    public function unarchive(Fabric $fabric): FabricResource
    {
        $fabric->update(['is_archived' => false]);
        return new FabricResource($fabric);
    }
}
