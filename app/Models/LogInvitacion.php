<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LogInvitacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'invitacion_id',
        'type',
        'count',
        'emails',
        'status',
    ];

    public function invitacion(): BelongsTo
    {
        return $this->belongsTo(Invitacion::class);
    }
}
