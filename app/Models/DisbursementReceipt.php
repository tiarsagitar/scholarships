<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisbursementReceipt extends Model
{
    protected $fillable = [
        'disbursement_id',
        'file_path',
        'original_name',
        'file_size',
        'mime_type',
        'description',
        'uploaded_at',
        'status',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    /**
     * Get the disbursement this receipt belongs to.
     */
    public function disbursement(): BelongsTo
    {
        return $this->belongsTo(Disbursement::class);
    }
}
