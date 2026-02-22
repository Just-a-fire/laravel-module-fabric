<?php

namespace Modules\Fabric\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FabricResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'article'     => $this->article,
            'catalog_id'  => $this->catalog_id,
            'is_archived' => (bool) $this->is_archived,
            // Подгружаем каталог только если он загружен в запросе
            'catalog'     => $this->whenLoaded('catalog', function() {
                return [
                    'id'   => $this->catalog->id,
                    'name' => $this->catalog->name,
                ];
            }),
            
            // Подгружаем цвета с их процентами из pivot-таблицы
            'colors'      => $this->whenLoaded('colors', function() {
                return $this->colors->map(fn($color) => [
                    'id'         => $color->id,
                    'name'       => $color->name,
                    'percentage' => $color->pivot->percentage ?? null,
                ]);
            }),
        ];
    }
}
