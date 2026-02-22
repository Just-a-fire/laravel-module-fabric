<?php

namespace Modules\Fabric\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fabric extends Model
{
    protected $fillable = [
        'article',
        'catalog_id',
        'is_archived',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
    ];

    public function catalog(): BelongsTo
    {
        return $this->belongsTo(Catalog::class, 'catalog_id');
    }

    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'color_fabric')
                    ->withPivot('percentage'); // Регистрируем поле
    }

    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }
}
