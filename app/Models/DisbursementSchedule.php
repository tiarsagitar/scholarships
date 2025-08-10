<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisbursementSchedule extends Model
{
    protected $fillable = [
        'award_allocation_id',
        'cost_category_id',
        'scheduled_amount',
        'scheduled_date',
        'paid_amount',
        'description'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_amount' => 'decimal:2',
    ];

    public function awardAllocation()
    {
        return $this->belongsTo(AwardAllocation::class);
    }

    public function costCategory()
    {
        return $this->belongsTo(CostCategory::class);
    }

    public function disbursements()
    {
        return $this->hasMany(Disbursement::class);
    }
}