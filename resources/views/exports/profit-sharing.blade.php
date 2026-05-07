<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Profit Sharing</title>
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
        $owners = \App\Models\Owner::query()->orderBy('id')->limit(2)->pluck('name')->all();
        $ownerAName = $owners[0] ?? 'Owner A';
        $ownerBName = $owners[1] ?? 'Owner B';
        $totalLaba = (int) $rows->sum('laba_bersih');
        $totalA = (int) $rows->sum('owner_a_nominal');
        $totalB = (int) $rows->sum('owner_b_nominal');
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
                    <div class="h-sub">Laporan Profit Sharing</div>
                </td>
                <td class="right" style="width: 240px;">
                    <div style="font-size: 10px; color: #475569;">Periode selesai</div>
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
                    <div class="kpi-label">Total Laba Bersih</div>
                    <div class="kpi-value">{{ $idr($totalLaba) }}</div>
                </div>
            </td>
            <td style="padding-right: 8px; width: 33%;">
                <div class="kpi">
                    <div class="kpi-label">Total {{ $ownerAName }}</div>
                    <div class="kpi-value">{{ $idr($totalA) }}</div>
                </div>
            </td>
            <td style="width: 33%;">
                <div class="kpi">
                    <div class="kpi-label">Total {{ $ownerBName }}</div>
                    <div class="kpi-value">{{ $idr($totalB) }}</div>
                </div>
            </td>
        </tr>
    </table>

    @if ($rows->count() === 0)
        <div class="empty">Belum ada data profit sharing pada periode ini.</div>
    @else
        <table class="report">
            <thead>
                <tr>
                    <th style="width: 86px;">Mulai</th>
                    <th style="width: 86px;">Selesai</th>
                    <th style="width: 104px;" class="right">Laba</th>
                    <th style="width: 140px;" class="right">{{ $ownerAName }}</th>
                    <th style="width: 140px;" class="right">{{ $ownerBName }}</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $r)
                    <tr>
                        <td>{{ $r->periode_mulai?->toDateString() }}</td>
                        <td>{{ $r->periode_selesai?->toDateString() }}</td>
                        <td class="right" style="font-weight: 800;">{{ $idr((int) $r->laba_bersih) }}</td>
                        <td class="right">{{ $idr((int) $r->owner_a_nominal) }} ({{ number_format((float) $r->owner_a_persen, 2, ',', '.') }}%)</td>
                        <td class="right">{{ $idr((int) $r->owner_b_nominal) }} ({{ number_format((float) $r->owner_b_persen, 2, ',', '.') }}%)</td>
                        <td>{{ $r->catatan }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="right" style="font-weight: 900; background: #F8FAFC;">Total</td>
                    <td class="right" style="font-weight: 900; background: #F8FAFC;">{{ $idr($totalLaba) }}</td>
                    <td class="right" style="font-weight: 900; background: #F8FAFC;">{{ $idr($totalA) }}</td>
                    <td class="right" style="font-weight: 900; background: #F8FAFC;">{{ $idr($totalB) }}</td>
                    <td style="background: #F8FAFC;"></td>
                </tr>
            </tbody>
        </table>
    @endif

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_text(28, 820, "Usaha Baraya · Laporan Profit Sharing", null, 9, array(71, 85, 105));
            $pdf->page_text(520, 820, "Halaman {PAGE_NUM}/{PAGE_COUNT}", null, 9, array(71, 85, 105));
        }
    </script>
</body>
</html>
