<?php

namespace Modules\Fabric\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Fabric\Entities\Color;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
         $colors = [
            ['name' => 'Белый'],
            ['name' => 'Зелёный'],
            ['name' => 'Синий'],
            ['name' => 'Красный'],
            ['name' => 'Чёрный'],
        ];

        foreach ($colors as $color) {
            Color::firstOrCreate($color);
        }
    }
}
