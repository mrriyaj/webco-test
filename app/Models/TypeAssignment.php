<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeAssignment extends Model
{
    protected $fillable = [
        'type_assignments',
        'my_bonus_field',
        'type_id',
    ];

    public function type()
    {
        return $this->belongsTo(ProductType::class, 'type_id');
    }
}
