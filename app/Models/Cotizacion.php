<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cotizacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'customer_id',
        'course_id',
        'author',
        'grup',
        'thour',
        'tpart',
        'vunit',
        'course_id',
        'add_course_id',
        'content',
        'costs'
    ];

    protected $casts = [
        'grup' => 'json',
        'thour' => 'json',
        'tpart' => 'json',
        'vunit' => 'json',
        'costs' => 'json',        
    ];

    public function customer(): BelongsTo
    {
        return $this->BelongsTo(Customer::class);
    }

    public function course(): BelongsTo
    {
        return $this->BelongsTo(Course::class);
    }
}
