<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        @page {
            margin: 100px 40px 60px 40px;
            /* top right bottom left */
        }

        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            color: #333;
        }

        /* ===== HEADER & FOOTER ===== */
        header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 60px;
            text-align: center;
            border-bottom: 2px solid #4CAF50;
        }

        header img {
            float: left;
            height: 50px;
        }

        header h1 {
            font-size: 18px;
            margin: 0;
            line-height: 60px;
            color: #4CAF50;
            font-weight: bold;
        }

        footer {
            position: fixed;
            bottom: -40px;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        h2 {
            font-size: 16px;
            margin-top: 30px;
            margin-bottom: 10px;
            color: #4CAF50;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        /* ===== TABLES ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background-color: #f9f9f9;
            font-weight: bold;
            color: #333;
        }

        .summary-table td {
            font-size: 13px;
        }

        .summary-table .label {
            font-weight: bold;
        }

        .summary-table .value {
            text-align: right;
        }

        .profit {
            background-color: #e8f5e9;
            font-weight: bold;
            font-size: 14px !important;
            color: #2e7d32;
        }

        .chart {
            text-align: center;
            margin: 20px 0;
        }

    </style>
</head>
<body>
    <!-- HEADER -->
    <header>
        {{-- Logo opsional --}}
        {{-- <img src="{{ public_path('images/logo.png') }}" alt="Logo"> --}}
        <h1>PT Farmasi Medika — Laporan Keuangan</h1>
    </header>

    <!-- FOOTER -->
    <footer>
        Laporan ini dihasilkan secara otomatis pada {{ now()->format('d M Y H:i') }}
    </footer>

    <!-- CONTENT -->
    <div class="container">
        <h2>Periode Laporan</h2>
        <p>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>

        <h2>Ringkasan Keuangan</h2>
        <table class="summary-table">
            <tr>
                <td class="label">Total Omzet (Pendapatan)</td>
                <td class="value">Rp {{ number_format($totalRevenue) }}</td>
            </tr>
            <tr>
                <td class="label">Total Modal (HPP)</td>
                <td class="value">Rp {{ number_format($totalCogs) }}</td>
            </tr>
            <tr>
                <td class="label">Laba Kotor</td>
                <td class="value">Rp {{ number_format($grossProfit) }}</td>
            </tr>
            <tr>
                <td class="label">Total Biaya Operasional</td>
                <td class="value">Rp {{ number_format($totalExpenses) }}</td>
            </tr>
            <tr class="profit">
                <td class="label">LABA BERSIH</td>
                <td class="value">Rp {{ number_format($netProfit) }}</td>
            </tr>
        </table>

        <h2>Visualisasi Biaya Operasional</h2>
        <div class="chart">
            @if(!empty($expenseChartUrl))
            <img src="{{ $expenseChartUrl }}" alt="Grafik Biaya" style="max-width: 50%; height: auto; border: 1px solid #ddd; padding: 6px; border-radius: 6px;">
            @else
            <p><em>Tidak ada data biaya untuk ditampilkan.</em></p>
            @endif
        </div>


        <h2>Top 5 Produk Terlaris (Berdasarkan Omzet)</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah Terjual</th>
                    <th>Total Omzet</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topProducts as $product)
                <tr>
                    <td>{{ $product->medicine->name }}</td>
                    <td>{{ $product->total_quantity }}</td>
                    <td>Rp {{ number_format($product->total_revenue) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3"><em>Tidak ada produk yang terjual pada periode ini.</em></td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <h2>Rincian Biaya Operasional</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                <tr>
                    <td>{{ $expense->expense_date->format('d M Y') }}</td>
                    <td>{{ $expense->category }}</td>
                    <td>Rp {{ number_format($expense->amount) }}</td>
                    <td>{{ $expense->description }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4"><em>Tidak ada biaya operasional pada periode ini.</em></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
