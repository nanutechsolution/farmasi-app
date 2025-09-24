<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medicine extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'barcode',
        'category_id',
        'stock',
        'price',
        'cost_price',
        'unit',
        'expired_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expired_date' => 'date',
        'price' => 'decimal:2',
    ];

    /**
     * Mendefinisikan relasi "milik" ke model Category.
     * Satu Obat hanya memiliki satu Kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    // ---------------------------------------------
}