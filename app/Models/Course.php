<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'cod_sence',
        'name',
        'description',
        'modality',
        'category',
        'hour',
        'unit_value',
        'type',
        'modalidad_id',
    ];

    public function AddCourse()
    {
        return $this->hasMany(AddCourse::class);
    }

    public function cotizacion(): HasMany
    {
        return $this->hasMany(Cotizacion::class);
    }

    public function modalidad()
{
    return $this->belongsTo(Modalidades::class); 
}

}
