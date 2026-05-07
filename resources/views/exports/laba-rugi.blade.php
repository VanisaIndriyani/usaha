<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi</title>
    <style>
        @page { margin: 28px 28px 42px 28px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11.5px; color: #0F172A; }
        .topbar { height: 6px; background: #0F172A; border-radius: 8px; }
        .header { margin-top: 12px; padding-bottom: 10px; border-bottom: 1px solid #E5E7EB; }
        .brand { width: 100%; border-collapse: collapse; }
        .badge { width: 34px; height: 34px; background: #0F172A; color: #FACC15; text-align: center; font-weight: 900; font-size: 18px; border-radius: 10px; }
        .h-title { font-size: 16px; font-weight: 900; letter-spacing: -0.2px; }
        .h-sub { font-size: 10px; color: #475569; margin-top: 2px; }
        .meta { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .meta td { padding: 2px 0; font-size: 10px; color: #475569; }
        .card { margin-top: 12px; border: 1px solid #E5E7EB; border-radius: 14px; padding: 12px 14px; background: #FFFFFF; }
        .table { width: 100%; border-collapse: collapse; }
        .table td { padding: 8px 0; border-bottom: 1px solid #F1F5F9; }
        .table tr:last-child td { border-bottom: 0; }
        .label { color: #475569; }
        .value { font-weight: 900; text-align: right; }
        .accent { color: #1E3A8A; }
        .profit { font-size: 14px; }
        .muted { color: #64748B; font-size: 10px; margin-top: 10px; }
    </style>
</head>
<body>
    @php $idr = fn (int $v) => 'Rp ' . number_format($v, 0, ',', '.'); @endphp

    <div class="topbar"></div>
    <div class="header">
        <table class="brand">
            <tr>
                <td style="width: 44px;">
                    <div class="badge">U</div>
                </td>
                <td>
                    <div class="h-title">{{ config('app.name', 'Usaha Baraya') }}</div>
                    <div class="h-sub">Laporan Laba Rugi</div>
                </td>
                <td class="value" style="width: 240px;">
                    <div style="font-size: 10px; color: #475569; font-weight: 700;">Periode</div>
                    <div style="font-size: 12px; font-weight: 900; color: #0F172A;">{{ $start }} s/d {{ $end }}</div>
                </td>
            </tr>
        </table>

        <table class="meta">
            <tr>
                <td>Generated: {{ now()->format('d-m-Y H:i') }} WIB</td>
                <td class="value" style="font-size: 10px; font-weight: 700; color: #475569;">Tema: Navy · Gold</td>
            </tr>
        </table>
    </div>

    <div class="card">
        <table class="table">
            <tr>
                <td class="label">Pemasukan</td>
                <td class="value accent">{{ $idr($income) }}</td>
            </tr>
            <tr>
                <td class="label">Pengeluaran (manual)</td>
                <td class="value">{{ $idr($expenseManual) }}</td>
            </tr>
            <tr>
                <td class="label">Pengeluaran Barang</td>
                <td class="value">{{ $idr($expenseBarang) }}</td>
            </tr>
            <tr>
                <td class="label">Gaji Dibayar</td>
                <td class="value">{{ $idr($expenseGaji) }}</td>
            </tr>
            <tr>
                <td class="label" style="font-weight: 900; color: #0F172A;">Total Pengeluaran</td>
                <td class="value" style="font-weight: 900;">{{ $idr($totalExpense) }}</td>
            </tr>
            <tr>
                <td class="label profit" style="font-weight: 900; color: #0F172A;">Laba/Rugi</td>
                <td class="value profit" style="font-weight: 900; color: {{ $profit >= 0 ? '#1E3A8A' : '#B91C1C' }};">{{ $idr($profit) }}</td>
            </tr>
        </table>
        <div class="muted">Laba/Rugi dihitung dari Pemasukan dikurangi Total Pengeluaran pada periode yang dipilih.</div>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_text(28, 820, "Usaha Baraya · Laporan Laba Rugi", null, 9, array(71, 85, 105));
            $pdf->page_text(520, 820, "Halaman {PAGE_NUM}/{PAGE_COUNT}", null, 9, array(71, 85, 105));
        }
    </script>
</body>
</html>
