<?php

namespace Modules\Fabric\App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Fabric\Entities\Catalog;

class CatalogApiController extends Controller
{
    public function index()
    {
        return response()->json(Catalog::withCount('fabrics')->get());
    }

    public function show(Catalog $catalog)
    {
        return response()->json($catalog->load('fabrics'));
    }
    
    public function destroy(Catalog $catalog)
    {
        if ($catalog->fabrics()->exists()) {
            return response()->json(['error' => 'Нельзя удалить каталог, содержащий ткани'], 422);
        }

        $catalog->delete();
        return response()->json(null, 204);
    }
}
