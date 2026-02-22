<?php

namespace Modules\Fabric\tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Fabric\Entities\Fabric;
use Modules\Fabric\Entities\Catalog;
use Modules\Fabric\Entities\Color;

class FabricApiTest extends TestCase
{
    use RefreshDatabase; // Очищаем БД перед каждым тестом

    /** @test */
    public function it_validates_color_percentage_sum_is_not_over_100()
    {
        $catalog = Catalog::create(['name' => 'Test Catalog']);
        $color = Color::create(['name' => 'Red']);

        $response = $this->postJson('/api/fabrics', [
            'article' => 'ERROR-100',
            'catalog_id' => $catalog->id,
            'colors' => [
                ['id' => $color->id, 'percentage' => 110] // Ошибка: > 100
            ]
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['colors']);
    }

    /** @test */
    public function it_prevents_deleting_catalog_with_fabrics()
    {
        $catalog = Catalog::create(['name' => 'Non Empty']);
        Fabric::create([
            'article' => 'FB-1',
            'catalog_id' => $catalog->id
        ]);

        // Пытаемся удалить через API
        $response = $this->deleteJson("/api/catalogs/{$catalog->id}");

        $response->assertStatus(422)
                 ->assertJsonFragment(['error' => 'Нельзя удалить каталог, содержащий ткани']);
        
        $this->assertDatabaseHas('catalogs', ['id' => $catalog->id]);
    }

    /** @test */
    public function it_returns_correct_fabric_json_structure()
    {
        $catalog = Catalog::create(['name' => 'Structure Test']);
        $fabric = Fabric::create(['article' => 'JSON-1', 'catalog_id' => $catalog->id]);

        $response = $this->getJson("/api/fabrics/{$fabric->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => ['id', 'article', 'is_archived', 'catalog', 'colors']
                 ]);
    }
}
