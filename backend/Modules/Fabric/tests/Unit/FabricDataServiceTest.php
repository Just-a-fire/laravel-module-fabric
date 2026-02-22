<?php

namespace Modules\Fabric\tests\Unit;

use PHPUnit\Framework\TestCase;
use Modules\Fabric\App\Services\FabricDataService;
use InvalidArgumentException;

class FabricDataServiceTest extends TestCase
{
    private FabricDataService $service;

    protected function setUp(): void
    {
        $this->service = new FabricDataService();
    }

    /** @test */
    public function it_calculates_sum_correctly()
    {
        $total = 100;
        $count = 3;
        
        $result = $this->service->divideIntoSummands($count, $total);
        
        $this->assertCount($count, $result);
        $this->assertEquals($total, array_sum($result), "Сумма элементов должна быть равна $total");
    }

    /**
     * @test
     * @dataProvider countProvider
     */
    public function it_calculates_sum_correctly_and_is_never_zero(int $count, int $total)
    {
        // Запускаем 100 итераций для каждого набора данных, чтобы проверить рандом
        for ($i = 0; $i < 100; $i++) {
            $result = $this->service->divideIntoSummands($count, $total);

            $this->assertCount($count, $result, "Неверное количество элементов для count=$count");
            $this->assertEquals($total, array_sum($result), "Сумма элементов не равна $total для итерации $i");
            
            foreach ($result as $value) {
                $this->assertGreaterThan(0, $value, "Найдено нулевое или отрицательное слагаемое: $value при count=$count");
            }
        }
    }

    /** @test */
    public function it_throws_exception_if_count_greater_than_total()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->divideIntoSummands(110, 100);
    }

    /** @test */
    public function it_returns_only_positive_integers()
    {
        $result = $this->service->divideIntoSummands(5, 100);
        
        foreach ($result as $value) {
            $this->assertGreaterThan(0, $value);
            $this->assertIsInt($value);
        }
    }

    /**
     * Поставщик данных для теста
     */
    public static function countProvider(): array
    {
        return [
            '1 слагаемое'  => [1, 100],
            '2 слагаемых' => [2, 100],
            '3 слагаемых' => [3, 100],
            '5 слагаемых' => [5, 100],
            '10 слагаемых' => [10, 100],
            'Максимальное (100 из 100)' => [100, 100],
            'Другой total' => [4, 50],
        ];
    }
}
