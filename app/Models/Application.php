<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'scholarship_id',
        'status',
        'personal_statement',
        'submitted_at',
        'reviewed_at',
        'reviewer_id',
        'reviewer_comments',
    ];

    protected $casts = [
        'additional_data' => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function applicationDocuments()
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isUnderReview()
    {
        return $this->status === 'under_review';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
