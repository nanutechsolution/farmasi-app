<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseDetail extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_id', 'medicine_id', 'quantity', 'purchase_price'];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}