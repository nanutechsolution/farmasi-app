<?php

namespace App\Exports;

use App\Models\Medicine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MedicinesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Pilih kolom yang ingin diekspor
        return Medicine::select('id', 'name', 'barcode', 'category_id', 'stock', 'price', 'cost_price', 'unit', 'expired_date')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Definisikan nama header kolom di file Excel
        return [
            'ID',
            'Nama Obat',
            'Barcode',
            'Nama Kategori',
            'Stok',
            'Harga Jual',
            'Harga Beli (Modal)',
            'Satuan',
            'Tanggal Kadaluarsa',
        ];
    }


    /**
     * @var Medicine $medicine
     * @return array
     */
    public function map($medicine): array
    {
        // 4. Definisikan data untuk setiap baris di sini
        return [
            $medicine->id,
            $medicine->name,
            $medicine->barcode,
            $medicine->category->name ?? 'N/A', // <-- Ambil nama kategori
            $medicine->stock,
            $medicine->price,
            $medicine->cost_price,
            $medicine->unit,
            $medicine->expired_date->format('Y-m-d'), // <-- Format tanggal di sini
        ];
    }
}
