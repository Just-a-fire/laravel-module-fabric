<?php

namespace Modules\Fabric\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Fabric\App\Rules\CheckColorPercentage;

class FabricStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'article'     => 'required|string|max:100',
            'catalog_id'  => 'required|exists:catalog,id',
            'is_archived' => 'boolean',

            // применяем наше правило к самому массиву цветов
            'colors'              => ['nullable', 'array', new CheckColorPercentage],
            'colors.*.id'         => 'required|exists:colors,id',
            'colors.*.percentage' => 'required|integer|min:1|max:100',
        ];
    }
    
    public function authorize(): bool
    {
        return true;
    }
}
