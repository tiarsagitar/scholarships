<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarshipBudget extends Model
{
    protected $fillable = [
        'scholarship_id',
        'cost_category_id', 
        'planned_amount',
        'commited_amount'
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function costCategory()
    {
        return $this->belongsTo(CostCategory::class);
    }
}
