<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
  protected $fillable = [
    'name',
    'api_unique_number',
  ];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      if (empty($model->api_unique_number)) {
        $model->api_unique_number = 'PT-' . strtoupper(uniqid());
      }
    });
  }
}
