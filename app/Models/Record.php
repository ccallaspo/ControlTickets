<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'author',
        'referent',
        'event_id',
        'task_id',
        'client',
        'active',
    ];

    // public function Task(): HasMany
    // {
    //     return $this->hasMany(Task::class);
    // }

    public function Event(): BelongsTo
    {
        return $this->BelongsTo(Event::class);
    }

    public function Task(): BelongsTo
    {
        return $this->BelongsTo(Task::class);
    }
}
