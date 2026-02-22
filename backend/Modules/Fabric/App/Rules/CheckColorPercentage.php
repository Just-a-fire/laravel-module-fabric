<?php

namespace Modules\Fabric\App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckColorPercentage implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /// $value — массив вида [ ['id' => 1, 'percentage' => 70], ... ]
        if (!is_array($value)) return;

        $total = collect($value)
            ->map(fn($item) => (int)($item['percentage'] ?? 0))
            ->sum();

        if ($total > 100) {
            $fail("Сумма процентов всех цветов не должна превышать 100%. Сейчас: {$total}%.");
        }
    }
}
