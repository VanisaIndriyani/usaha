<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Gaji</title>
    <style>
        @page { margin: 28px 28px 42px 28px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10.5px; color: #0F172A; }
        .topbar { height: 6px; background: #0F172A; border-radius: 8px; }
        .header { margin-top: 12px; padding-bottom: 10px; border-bottom: 1px solid #E5E7EB; }
        .brand { width: 100%; border-collapse: collapse; }
        .badge { width: 34px; height: 34px; background: #0F172A; color: #FACC15; text-align: center; font-weight: 900; font-size: 18px; border-radius: 10px; }
        .h-title { font-size: 16px; font-weight: 900; letter-spacing: -0.2px; }
        .h-sub { font-size: 10px; color: #475569; margin-top: 2px; }
        .meta { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .meta td { padding: 2px 0; font-size: 10px; color: #475569; }
        .kpi-wrap { margin-top: 12px; width: 100%; border-collapse: collapse; }
        .kpi { border: 1px solid #E5E7EB; border-radius: 12px; padding: 10px 12px; background: #F8FAFC; }
        .kpi-label { font-size: 10px; color: #475569; text-transform: uppercase; letter-spacing: 0.6px; }
        .kpi-value { margin-top: 4px; font-size: 13px; font-weight: 900; color: #1E3A8A; }
        table.report { width: 100%; border-collapse: collapse; margin-top: 12px; }
        table.report th { background: #0F172A; color: #FFFFFF; text-align: left; font-weight: 800; font-size: 9.5px; padding: 8px; border: 1px solid #0F172A; }
        table.report td { border: 1px solid #E5E7EB; padding: 7px 8px; vertical-align: top; }
        table.report tbody tr:nth-child(even) td { background: #F8FAFC; }
        .right { text-align: right; }
        .empty { margin-top: 12px; padding: 18px; border: 1px dashed #CBD5E1; border-radius: 12px; color: #64748B; text-align: center; }
    </style>
</head>
<body>
    @php
        $idr = fn (int $v) => 'Rp ' . number_format($v, 0, ',', '.');
        $totalGaji = (int) $rows->sum('nominal');
        $totalBonus = (int) $rows->sum('bonus');
        $totalHari = (int) $rows->sum('hari_kerja');
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
                    <div class="h-sub">Laporan Gaji</div>
                </td>
                <td class="right" style="width: 240px;">
                    <div style="font-size: 10px; color: #475569;">Rentang input</div>
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

    <table class="kpi-wrap">
        <tr>
            <td style="padding-right: 8px; width: 34%;">
                <div class="kpi">
                    <div class="kpi-label">Total Gaji</div>
                    <div class="kpi-value">{{ $idr($totalGaji) }}</div>
                </div>
            </td>
            <td style="padding-right: 8px; width: 33%;">
                <div class="kpi">
                    <div class="kpi-label">Total Bonus</div>
                    <div class="kpi-value">{{ $idr($totalBonus) }}</div>
                </div>
            </td>
            <td style="width: 33%;">
                <div class="kpi">
                    <div class="kpi-label">Total Hari Kerja</div>
                    <div class="kpi-value">{{ number_format($totalHari, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    @if ($rows->count() === 0)
        <div class="empty">Belum ada data gaji pada periode ini.</div>
    @else
        <table class="report">
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th style="width: 70px;">Periode</th>
                    <th style="width: 92px;" class="right">Gaji Harian</th>
                    <th style="width: 46px;" class="right">Hari</th>
                    <th style="width: 90px;" class="right">Bonus</th>
                    <th style="width: 104px;" class="right">Total</th>
                    <th style="width: 88px;">Status</th>
                    <th style="width: 82px;">Tgl Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $r)
                    <tr>
                        <td>{{ $r->karyawan?->nama }}</td>
                        <td>{{ str_pad((string) $r->bulan, 2, '0', STR_PAD_LEFT) }}/{{ $r->tahun }}</td>
                        <td class="right">{{ $idr((int) $r->gaji_harian) }}</td>
                        <td class="right">{{ $r->hari_kerja }}</td>
                        <td class="right">{{ $idr((int) $r->bonus) }}</td>
                        <td class="right" style="font-weight: 800;">{{ $idr((int) $r->nominal) }}</td>
                        <td>{{ $r->status }}</td>
                        <td>{{ $r->tanggal_bayar?->toDateString() }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" class="right" style="font-weight: 900; background: #F8FAFC;">Total</td>
                    <td class="right" style="font-weight: 900; background: #F8FAFC;">{{ $idr($totalGaji) }}</td>
                    <td colspan="2" style="background: #F8FAFC;"></td>
                </tr>
            </tbody>
        </table>
    @endif

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_text(28, 820, "Usaha Baraya · Laporan Gaji", null, 9, array(71, 85, 105));
            $pdf->page_text(520, 820, "Halaman {PAGE_NUM}/{PAGE_COUNT}", null, 9, array(71, 85, 105));
        }
    </script>
</body>
</html>
