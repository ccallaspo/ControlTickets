<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

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
        'n_hours',
        'doc_oc',
    ];

    protected $casts = [
        'week' => 'json',    
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

    public function scopeRestrictedForSupportUser($query)
{
    if (Auth::check() && Auth::user()->email === 'soporte@otecproyecta.cl') {
        return $query->whereHas('event', function ($q) {
            $q->whereIn('name', [
                'Curso agendado',
                'Curso matriculado',
                'Curso finalizado'
            ]);
        });
    }

    return $query;
}
}
