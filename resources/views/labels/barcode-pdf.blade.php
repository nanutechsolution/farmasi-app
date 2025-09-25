<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Barcode Labels</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        .label {
            display: inline-block;
            width: 200px;
            /* atur sesuai ukuran kertas label */
            height: 60px;
            border: 1px dashed #ccc;
            margin: 5px;
            text-align: center;
            padding-top: 5px;
        }

        .label img {
            max-width: 180px;
            height: 40px;
        }

        .label p {
            margin: 2px 0 0 0;
            font-size: 9pt;
        }

    </style>
</head>
<body>
    @foreach ($medicines as $medicine)
    <div class="label">
        @if(isset($barcodes[$medicine->id]))
        <img src="data:image/png;base64,{{ $barcodes[$medicine->id] }}" alt="{{ $medicine->name }}">
        @else
        <p>[No Barcode]</p>
        @endif
        <p>{{ $medicine->name }}</p>
    </div>
    @endforeach
</body>
</html>
