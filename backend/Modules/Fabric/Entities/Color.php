<?php

namespace Modules\Fabric\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Color extends Model
{
    protected $fillable = ['name'];

    public function fabrics(): BelongsToMany
    {
        // Указываем полный путь к классу Fabric
        return $this->belongsToMany(Fabric::class, 'color_fabric');
    }
}
