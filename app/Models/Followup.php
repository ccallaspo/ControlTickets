<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Followup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'author',
        'referent',
        'event_id',
        'task_id',
        'customer_id',
        'active',
        'doc_participant',
        'cod_sence_course',
        'name_course',
        'id_sence',
        'modalily',
        'week',
        'h_star',
        'h_end',
        'f_star',
        'f_end',
    ];

    protected $casts = [
        'week' => 'array',
    ];

    public function note(): HasMany
    {
        return $this->HasMany(Note::class);
    }

    public function Task(): BelongsTo
    {
        return $this->BelongsTo(Task::class);
    }

    public function Event(): BelongsTo
    {
        return $this->BelongsTo(Event::class);
    }

    public function customer(): BelongsTo
    {
        return $this->BelongsTo(Customer::class);
    }
}
