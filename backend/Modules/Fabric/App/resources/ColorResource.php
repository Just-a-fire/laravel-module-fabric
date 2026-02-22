<?php

namespace Modules\Fabric\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ColorResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            // Выводим данные из pivot, если они загружены
            'percentage' => $this->whenPivotLoaded('color_fabric', function () {
                return $this->pivot->percentage;
            }),
        ];
    }
}
