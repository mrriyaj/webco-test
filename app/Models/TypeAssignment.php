<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductType;

class TypeAssignment extends Model
{
  protected $fillable = [
    'type_assignments_type',
    'type_assignments_id',
    'my_bonus_field',
    'type_id',
  ];

  public function type()
  {
    return $this->belongsTo(ProductType::class, 'type_id');
  }

  public function type_assignments()
  {
    return $this->morphTo();
  }
}
