<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'comment',
        'status',
        'participants',
        'date_star',
        'date_end',
        'author'
    ];

    protected $casts = [
        'participants' => 'array',
    ];

    public function user(): HasMany
    {
        return $this->HasMany(User::class);
    }
}
