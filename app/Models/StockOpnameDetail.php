<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOpnameDetail extends Model
{
    use HasFactory;

    protected $fillable = ['stock_opname_id', 'medicine_id', 'system_stock', 'physical_stock', 'difference'];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}
