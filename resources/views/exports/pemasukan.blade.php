<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Pemasukan</title>
    <style>
        @page { margin: 28px 28px 42px 28px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #0F172A; }
        .topbar { height: 6px; background: #0F172A; border-radius: 8px; }
        .header { margin-top: 12px; padding-bottom: 10px; border-bottom: 1px solid #E5E7EB; }
        .brand { width: 100%; border-collapse: collapse; }
        .badge { width: 34px; height: 34px; background: #0F172A; color: #FACC15; text-align: center; font-weight: 900; font-size: 18px; border-radius: 10px; }
        .h-title { font-size: 16px; font-weight: 900; letter-spacing: -0.2px; }
        .h-sub { font-size: 10px; color: #475569; margin-top: 2px; }
        .meta { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .meta td { padding: 2px 0; font-size: 10px; color: #475569; }
        .kpi { margin-top: 12px; border: 1px solid #E5E7EB; border-radius: 12px; padding: 10px 12px; background: #F8FAFC; }
        .kpi-label { font-size: 10px; color: #475569; text-transform: uppercase; letter-spacing: 0.6px; }
        .kpi-value { margin-top: 4px; font-size: 14px; font-weight: 900; color: #1E3A8A; }
        table.report { width: 100%; border-collapse: collapse; margin-top: 12px; }
        table.report th { background: #0F172A; color: #FFFFFF; text-align: left; font-weight: 800; font-size: 10px; padding: 8px; border: 1px solid #0F172A; }
        table.report td { border: 1px solid #E5E7EB; padding: 7px 8px; vertical-align: top; }
        table.report tbody tr:nth-child(even) td { background: #F8FAFC; }
        .right { text-align: right; }
        .empty { margin-top: 12px; padding: 18px; border: 1px dashed #CBD5E1; border-radius: 12px; color: #64748B; text-align: center; }
    </style>
</head>
<body>
    @php
        $idr = fn (int $v) => 'Rp ' . number_format($v, 0, ',', '.');
        $total = (int) $rows->sum('nominal');
    @endphp

    <div class="topbar"></div>
    <div class="header">
        <table class="brand">
            <tr>
                <td style="width: 44px;">
                    <div class="badge">U</div>
                </td>
                <td>
                    <div class="h-title">{{ config('app.name', 'Usaha Baraya') }}</div>
                    <div class="h-sub">Laporan Pemasukan</div>
                </td>
                <td class="right" style="width: 220px;">
                    <div style="font-size: 10px; color: #475569;">Periode</div>
                    <div style="font-size: 12px; font-weight: 800; color: #0F172A;">{{ $start }} s/d {{ $end }}</div>
                </td>
            </tr>
        </table>

        <table class="meta">
            <tr>
                <td>Generated: {{ now()->format('d-m-Y H:i') }} WIB</td>
                <td class="right">Tema: Navy · Gold</td>
            </tr>
        </table>
    </div>

    <div class="kpi">
        <div class="kpi-label">Total Pemasukan</div>
        <div class="kpi-value">{{ $idr($total) }}</div>
    </div>

    @if ($rows->count() === 0)
        <div class="empty">Belum ada data pemasukan pada periode ini.</div>
    @else
        <table class="report">
            <thead>
                <tr>
                    <th style="width: 92px;">Tanggal</th>
                    <th>Nama</th>
                    <th style="width: 100px;">Metode</th>
                    <th style="width: 120px;" class="right">Nominal</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $r)
                    <tr>
                        <td>{{ $r->tanggal?->toDateString() }}</td>
                        <td>{{ $r->nama_pemasukan }}</td>
                        <td>{{ $r->metode_pembayaran }}</td>
                        <td class="right">{{ $idr((int) $r->nominal) }}</td>
                        <td>{{ $r->catatan }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="right" style="font-weight: 900; background: #F8FAFC;">Total</td>
                    <td class="right" style="font-weight: 900; background: #F8FAFC;">{{ $idr($total) }}</td>
                    <td style="background: #F8FAFC;"></td>
                </tr>
            </tbody>
        </table>
    @endif

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_text(28, 820, "Usaha Baraya · Laporan Pemasukan", null, 9, array(71, 85, 105));
            $pdf->page_text(520, 820, "Halaman {PAGE_NUM}/{PAGE_COUNT}", null, 9, array(71, 85, 105));
        }
    </script>
</body>
</html>
