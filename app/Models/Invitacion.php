<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invitacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'follow_id',
        'n_cotizacion',
        'link_clases',
        'link_moodle',
        'password',
        'status',
        'author',
        'date_execution'
    ];

    public function logInvitacion(): HasMany
    {
        return $this->hasMany(LogInvitacion::class);
    }
    
    public function followup(): BelongsTo
    {
        return $this->belongsTo(Followup::class, 'follow_id');
    }
}
