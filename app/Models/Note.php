<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'note',
        'author',
        'folloup_id',
        'calendar_id',

    ];

    public function followup(): HasMany
    {
        return $this->hasMany(Followup::class);
    }
}
