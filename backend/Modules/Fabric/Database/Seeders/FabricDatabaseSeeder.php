<?php

namespace Modules\Fabric\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Fabric\Entities\Fabric;
use Modules\Fabric\Entities\Color;
use Modules\Fabric\App\Services\FabricDataService;

class FabricDatabaseSeeder extends Seeder
{
    public function run(FabricDataService $service): void
    {
        $this->call([
            ColorSeeder::class,
            CatalogSeeder::class,
        ]);

        // ['article' => '7339', 'catalog_id' => 3, 'is_archived' => 0, 'colorIds' => [2, 3, 4, 5]],
        $fabrics = [
            ['article' => '1900', 'catalog_id' => 1, 'is_archived' => 0],
            ['article' => '1910', 'catalog_id' => 1, 'is_archived' => 0],
            ['article' => '1911', 'catalog_id' => 1, 'is_archived' => 0],
            ['article' => '4339', 'catalog_id' => 2, 'is_archived' => 0],
            ['article' => '5102', 'catalog_id' => 2, 'is_archived' => 0],
            ['article' => '6172', 'catalog_id' => 2, 'is_archived' => 1],
            ['article' => '7119', 'catalog_id' => 3, 'is_archived' => 0],
            ['article' => '7122', 'catalog_id' => 3, 'is_archived' => 0],
            ['article' => '7172', 'catalog_id' => 3, 'is_archived' => 1],
            ['article' => '7219', 'catalog_id' => 3, 'is_archived' => 0],
            ['article' => '7222', 'catalog_id' => 3, 'is_archived' => 0],
            ['article' => '7272', 'catalog_id' => 3, 'is_archived' => 1],
            ['article' => '7322', 'catalog_id' => 3, 'is_archived' => 0],
            ['article' => '7325', 'catalog_id' => 3, 'is_archived' => 1],
            ['article' => '7339', 'catalog_id' => 3, 'is_archived' => 0],
            ['article' => '7352', 'catalog_id' => 3, 'is_archived' => 0],
            ['article' => '7372', 'catalog_id' => 3, 'is_archived' => 1],
            ['article' => '7422', 'catalog_id' => 3, 'is_archived' => 0],
            ['article' => '7432', 'catalog_id' => 3, 'is_archived' => 1],
            ['article' => '7439', 'catalog_id' => 3, 'is_archived' => 0],
            ['article' => '7445', 'catalog_id' => 3, 'is_archived' => 0],
            ['article' => '7472', 'catalog_id' => 3, 'is_archived' => 1],
        ];

        $allColors = Color::all();

        foreach ($fabrics as $data) {

            // Создаем или находим ткань по артикулу (чтобы не было дублей при повторном запуске)
            $fabric = Fabric::firstOrCreate(
                ['article' => $data['article']], // Условие поиска
                $data                            // Данные для вставки, если не найдено
            );
            
            $count = min(rand(1, 8), $allColors->count());            
            $randomColors = $allColors->random($count);

            // Заполняем Pivot-таблицу
            $percentages = $service->divideIntoSummands($count, 100);
        
            $syncData = [];
            foreach ($randomColors as $index => $color) {
                $syncData[$color->id] = ['percentage' => $percentages[$index]];
            }
            
            $fabric->colors()->sync($syncData);
        }
    }
}
