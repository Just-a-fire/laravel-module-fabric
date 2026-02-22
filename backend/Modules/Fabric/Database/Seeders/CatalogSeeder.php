<?php

namespace Modules\Fabric\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Fabric\Entities\Catalog;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalogs = [
            ['name' => '42'],
            ['name' => '43'],
            ['name' => '44'],
            ['name' => '45'],
            ['name' => '50'],
        ];

        foreach ($catalogs as $catalog) {
            Catalog::firstOrCreate($catalog);
        }
    }
}
