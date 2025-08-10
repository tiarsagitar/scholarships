<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    protected $fillable = [
        'title',
        'description',
        'amount',
        'deadline',
        'max_awards',
        'status'
    ];

    protected $casts = [
        'deadline' => 'date',
        'amount' => 'decimal:2'
    ];

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isDeadlinePassed(): bool
    {
        return $this->deadline->isPast();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeWithinDeadline($query)
    {
        return $query->where('deadline', '>=', now());
    }
}
