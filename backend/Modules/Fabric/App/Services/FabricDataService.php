<?php

namespace Modules\Fabric\App\Services;

use InvalidArgumentException;

class FabricDataService
{
    /**
     * Распределение суммы на слагаемые
     * Метод не статический для того, чтобы легко «подменить» его результат в Feature-тестах. С обычным классом Laravel позволяет сделать
     * 
     * @throws InvalidArgumentException
     */
    public function divideIntoSummands(int $count, int $total = 100): array
    {
        // Если слагаемых больше, чем общая сумма, то не удастся получить целых положительных чисел.
        if ($count > $total) {
            throw new InvalidArgumentException("Количество слагаемых ($count) не может быть больше суммы ($total).");
        }

        if ($count <= 0) {
            throw new InvalidArgumentException("Количество слагаемых ($count) должно быть больше нуля.");
        }

        $avg = $total / $count;
        $multiple = (int) min(5, $avg);
        
        $buf = [];
        $remainingTotal = $total;
        
        for ($i = 0; $i < $count; ++$i) {
            if ($i === $count - 1) {
                $buf[] = $remainingTotal;
                break;
            }

            $min = $multiple;
            // Максимум должен гарантировать, что для оставшихся элементов хватит хотя бы по $multiple
            $max = $remainingTotal - $multiple * ($count - 1 - $i);
            
            // Если из-за округления max стал меньше min, берем min
            $percentage = ($min >= $max) ? $min : rand($min, $max);
            $percentage = $this->roundTo($percentage, $multiple);
            
            // Страховка: не выходим за пределы остатка
            $percentage = min($percentage, $remainingTotal - ($multiple * ($count - 1 - $i)));

            $buf[] = (int) $percentage;
            $remainingTotal -= $percentage;
        }
        
        return $buf;        
    }

    private function roundTo(int $num, int $multiple): int
    {
        return (int) (round($num / $multiple) * $multiple);
    }
}
