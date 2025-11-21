<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

/**
 * @property array $changes Array adjuntado por el Observer con los campos modificados.
 */

class Followup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'author',
        'referent',
        'event_id',
        'cotizacion_id',
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
//add execution data
        'has_execution_data',
        'exec_cod_sence_course',
        'exec_name_course',
        'exec_id_sence',
        'exec_modalily',
        'exec_f_star',
        'exec_f_end',
        'exec_n_hours',
    ];

    protected $casts = [
        'week' => 'json',
    ];

    public function note(): HasMany
    {
        return $this->HasMany(Note::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
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

    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class);
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
