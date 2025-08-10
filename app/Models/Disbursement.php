<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{
    protected $fillable = [
        'disbursement_schedule_id',
        'award_id',
        'cost_category_id',
        'amount',
        'disbursed_at',
        'status',
        'idempotency',
        'notes',
    ];

    protected $casts = [
        'disbursed_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function disbursementSchedule()
    {
        return $this->belongsTo(DisbursementSchedule::class);
    }

    public function award()
    {
        return $this->belongsTo(Award::class);
    }

    public function costCategory()
    {
        return $this->belongsTo(CostCategory::class);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('disbursed_at', [$startDate, $endDate]);
    }
}