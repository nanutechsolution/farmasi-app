<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Medicine;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MedicinesImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        // Jangan proses baris jika tidak ada nama obat atau kategori
        if (empty($row['nama_obat']) || empty($row['nama_kategori'])) {
            return null;
        }

        $inputCategoryName = trim($row['nama_kategori']);
        $existingCategories = Category::all()->pluck('name');
        $matchedCategory = null;
        $highestSimilarity = 0;

        // 1. Cari kategori yang paling mirip
        foreach ($existingCategories as $existingCategory) {
            similar_text(strtolower($inputCategoryName), strtolower($existingCategory), $percent);
            if ($percent > $highestSimilarity) {
                $highestSimilarity = $percent;
                $matchedCategory = $existingCategory;
            }
        }

        // 2. Tentukan kategori yang akan digunakan
        $finalCategory = null;
        // Jika kemiripan di atas 80%, gunakan kategori yang sudah ada
        if ($highestSimilarity >= 80) {
            $finalCategory = Category::where('name', $matchedCategory)->first();
        } else {
            // Jika tidak ada yang mirip, buat kategori baru
            $finalCategory = Category::firstOrCreate(['name' => ucwords(strtolower($inputCategoryName))]);
        }

        // 3. Lanjutkan proses impor dengan kategori yang benar
        return Medicine::updateOrCreate(
            ['barcode' => $row['barcode']],
            [
                'name'              => $row['nama_obat'],
                'category_id'       => $finalCategory->id, // Gunakan ID dari kategori final
                'stock'             => $row['stok'],
                'price'             => $row['harga_jual'],
                'cost_price'        => $row['harga_beli_modal'],
                'unit'              => $row['satuan'],
                'expired_date'      => Carbon::parse($row['tanggal_kadaluarsa'])->toDateString(),
            ]
        );
    }
}
