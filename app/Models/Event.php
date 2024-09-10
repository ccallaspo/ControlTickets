<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
        'description',
        'icono',
        'proce_id',

    ];

    public function task(): BelongsTo
    {
        return $this->BelongsTo(Task::class);
    }

    public function record(): BelongsTo
    {
        return $this->BelongsTo(Record::class);
    }

    public function followup(): BelongsTo
    {
        return $this->BelongsTo(Followup::class);
    }
}
