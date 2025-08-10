<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationDocument extends Model
{
    protected $fillable = [
        'application_id',
        'name',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
