<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'proce_id',

    ];

    public function Events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function Record(): BelongsTo
    {
        return $this->BelongsTo(Record::class);
    }

    public function followup(): BelongsTo
    {
        return $this->BelongsTo(Followup::class);
    }
}
