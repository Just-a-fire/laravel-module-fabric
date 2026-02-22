<?php

namespace Modules\Fabric\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Catalog extends Model
{
    protected $fillable = ['name'];

    public function fabrics(): HasMany
    {
        return $this->hasMany(Fabric::class);
    }
}
