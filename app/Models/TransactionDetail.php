<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'medicine_id', 'quantity', 'price'];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}