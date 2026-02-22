<?php

namespace Modules\Fabric\App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Fabric\Entities\Color;

class ColorApiController extends Controller
{
    public function index() {
        return response()->json(Color::all());
    }
}
