<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'followup_id',
        'typedocument_id',
        'document_archive'
    ];

    public function followup()
    {
        return $this->belongsTo(Followup::class);
    }

    public function typedocument()
    {
        return $this->belongsTo(Typedocument::class);
    }
} 