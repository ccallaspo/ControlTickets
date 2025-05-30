<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Typedocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active'
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
} 