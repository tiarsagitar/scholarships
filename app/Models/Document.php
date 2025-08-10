<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'name',
        'file_path',
        'file_type',
        'size',
        'user_id',
    ];

    protected $appends = [
        'full_url',
    ];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullUrlAttribute()
    {
        return url($this->file_path);
    }
}
