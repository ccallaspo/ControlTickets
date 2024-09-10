<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'rut',
        'name',
        'represent',
        'address',
        'phone',
        'email',
        'author',
      ];

      public function cotizacion(): HasMany
      {
          return $this->hasMany(Cotizacion::class);
      }

      public function followup(): HasMany
      {
          return $this->hasMany(Followup::class);
      }
}
