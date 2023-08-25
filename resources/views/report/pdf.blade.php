<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print</title>
    <style>
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2 class="text-center" style="margin-bottom: 0">Data Laporan Stock Opname AA Frozen ({{ $report->name }})</h2>
    <h4 class="text-center" style="margin-top: 10px">{{ $report->period_start }} sampai {{ $report->period_end }}</h4>

    <table style="width: 100%">
        <thead>
            <tr>
                <th class="text-left">Nama Barang</th>
                <th class="text-center">Qty/Pcs</th>
                <th class="text-right">Harga per Pc</th>
                <th class="text-right">Jumlah Harga</th>
            </tr>
        </thead>
        <tbody>
            @php($total = 0)
            @forelse ($report->reportItems as $item)
                @php($total += data_get($item, 'total', 0))
                <tr>
                    <td class="text-left">{{ data_get($item, 'produk.nama_produk', '-') }}</td>
                    <td class="text-center">{{ data_get($item, 'qty', '-') }}</td>
                    <td class="text-right">{{ data_get($item, 'price', '-') }}</td>
                    <td class="text-right">{{ data_get($item, 'total', '-') }}</td>
                </tr>

                @if ($loop->last)
                    <tr>
                        <th class="text-right" colspan="3">Total</th>
                        <th class="text-right">{{ $total }}</th>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="4">Data barang kosong</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
