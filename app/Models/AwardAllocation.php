<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AwardAllocation extends Model
{
    protected $fillable = [
        'application_id',
        'cost_category_id',
        'award_id',
        'allocated_amount',
        'disbursed_amount',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function costCategory()
    {
        return $this->belongsTo(CostCategory::class);
    }

    public function award()
    {
        return $this->belongsTo(Award::class);
    }

    public function disbursementSchedules()
    {
        return $this->hasMany(DisbursementSchedule::class);
    }
}
