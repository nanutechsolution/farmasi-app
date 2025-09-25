<!DOCTYPE html>
<html>

<head>
    <title>Struk Transaksi - {{ $transaction->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
        }

        .container {
            width: 300px;
            margin: auto;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 0;
        }

        .content table {
            width: 100%;
            border-collapse: collapse;
        }

        .content th,
        .content td {
            padding: 5px;
        }

        .content .item-row td {
            border-bottom: 1px dashed #ccc;
        }

        .footer {
            text-align: right;
            margin-top: 10px;
        }

        .footer .total {
            font-weight: bold;
        }

        .thank-you {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Apotek Sehat Selalu</h1>
            <p>Jl. Kesehatan No. 123, Kota Bahagia</p>
            <p>Telp: (021) 555-1234</p>
        </div>
        <hr>
        <div>
            <p>No. Invoice: {{ $transaction->invoice_number }}</p>
            <p>Tanggal: {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
            <p>Kasir: {{ $transaction->user->name }}</p>
        </div>
        <hr>
        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="text-align:center;">Jml</th>
                        <th style="text-align:right;">Harga</th>
                        <th style="text-align:right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->details as $detail)
                        <tr class="item-row">
                            <td>{{ $detail->medicine->name }}</td>
                            <td style="text-align:center;">{{ $detail->quantity }}</td>
                            <td style="text-align:right;">{{ number_format($detail->price) }}</td>
                            <td style="text-align:right;">{{ number_format($detail->price * $detail->quantity) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr>
        <div class="footer">
            <table>
                <tr>
                    <td style="text-align:right;">Total Belanja:</td>
                    <td style="text-align:right;" class="total">Rp {{ number_format($transaction->total_amount) }}</td>
                </tr>
                <tr>
                    <td style="text-align:right;">Dibayar:</td>
                    <td style="text-align:right;">Rp {{ number_format($transaction->paid_amount) }}</td>
                </tr>
                <tr>
                    <td style="text-align:right;">Kembalian:</td>
                    <td style="text-align:right;">Rp
                        {{ number_format($transaction->paid_amount - $transaction->total_amount) }}</td>
                </tr>
            </table>
        </div>
        <hr>
        <div class="thank-you">
            <p>Terima kasih telah berbelanja!</p>
        </div>
    </div>
</body>

</html>
