<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Medicine extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'barcode',
        'category_id',
        'price',
        'cost_price',
        'margin',
        'unit'
    ];

    /**
     * Mendefinisikan relasi "milik" ke model Category.
     * Satu Obat hanya memiliki satu Kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Hanya catat perubahan pada kolom-kolom ini
            ->logOnly(['name', 'stock', 'price', 'cost_price'])
            // Tampilkan deskripsi yang mudah dibaca
            ->setDescriptionForEvent(fn(string $eventName) => "Data obat ini telah di-{$eventName}")
            // Kelompokkan log ini dengan nama 'Medicine'
            ->useLogName('Medicine')
            // Hanya catat log jika ada data yang benar-benar berubah
            ->logOnlyDirty();
    }

    public function batches(): HasMany
    {
        return $this->hasMany(MedicineBatch::class);
    }

    public function getTotalStockAttribute(): int
    {
        return $this->batches()->sum('quantity');
    }

    public function getNextExpiryDateAttribute()
    {
        return $this->batches()
            ->where('quantity', '>', 0)
            ->min('expired_date');
    }
}
