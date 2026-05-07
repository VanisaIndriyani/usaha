<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Slip Gaji</title>
    <style>
        @page { margin: 28px 28px 42px 28px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #0F172A; }
        .topbar { height: 6px; background: #0F172A; border-radius: 8px; }
        .header { margin-top: 12px; padding-bottom: 10px; border-bottom: 1px solid #E5E7EB; }
        .brand { width: 100%; border-collapse: collapse; }
        .badge { width: 36px; height: 36px; background: #0F172A; color: #FACC15; text-align: center; font-weight: 900; font-size: 20px; border-radius: 10px; }
        .h-title { font-size: 16px; font-weight: 900; letter-spacing: -0.2px; }
        .h-sub { font-size: 10px; color: #475569; margin-top: 2px; }
        .meta { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .meta td { padding: 2px 0; font-size: 10px; color: #475569; }
        .card { margin-top: 12px; border: 1px solid #E5E7EB; border-radius: 14px; padding: 14px 16px; }
        .section-title { margin-top: 10px; margin-bottom: 6px; font-weight: 900; font-size: 12px; color: #0F172A; }
        .grid { width: 100%; border-collapse: collapse; }
        .grid td { padding: 6px 0; border-bottom: 1px solid #F1F5F9; vertical-align: top; }
        .grid tr:last-child td { border-bottom: 0; }
        .label { color: #475569; width: 42%; }
        .value { font-weight: 800; text-align: right; }
        .total { margin-top: 10px; padding-top: 10px; border-top: 1px solid #E5E7EB; }
        .total .value { font-size: 14px; color: #1E3A8A; }
        .foot { margin-top: 12px; font-size: 10px; color: #64748B; }
        .sign { margin-top: 14px; width: 100%; border-collapse: collapse; }
        .sign td { width: 50%; padding-top: 18px; font-size: 10px; color: #475569; }
        .line { margin-top: 42px; border-top: 1px solid #CBD5E1; }
    </style>
</head>
<body>
    @php
        $idr = fn (int $v) => 'Rp ' . number_format($v, 0, ',', '.');
    @endphp
    <div class="topbar"></div>
    <div class="header">
        <table class="brand">
            <tr>
                <td style="width: 46px;">
                    <div class="badge">U</div>
                </td>
                <td>
                    <div class="h-title">{{ config('app.name', 'Usaha Baraya') }}</div>
                    <div class="h-sub">Slip Gaji Karyawan</div>
                </td>
                <td class="value" style="width: 220px;">
                    <div style="font-size: 10px; color: #475569; font-weight: 700;">Periode</div>
                    <div style="font-size: 12px; font-weight: 900; color: #0F172A;">{{ str_pad((string) $gaji->bulan, 2, '0', STR_PAD_LEFT) }}/{{ $gaji->tahun }}</div>
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
        <div class="section-title">Informasi Karyawan</div>
        <table class="grid">
            <tr>
                <td class="label">Nama</td>
                <td class="value">{{ $gaji->karyawan->nama }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td class="value">{{ $gaji->karyawan->jabatan }}</td>
            </tr>
            <tr>
                <td class="label">Status Pembayaran</td>
                <td class="value">{{ $gaji->status }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Bayar</td>
                <td class="value">{{ $gaji->tanggal_bayar?->format('d-m-Y') }}</td>
            </tr>
        </table>

        <div class="section-title">Rincian Gaji</div>
        <table class="grid">
            <tr>
                <td class="label">Gaji Harian</td>
                <td class="value">{{ $idr((int) $gaji->gaji_harian) }}</td>
            </tr>
            <tr>
                <td class="label">Jumlah Hari Kerja</td>
                <td class="value">{{ number_format((int) $gaji->hari_kerja, 0, ',', '.') }} Hari</td>
            </tr>
            <tr>
                <td class="label">Gaji Pokok</td>
                <td class="value">{{ $idr((int) $gaji->gaji_pokok) }}</td>
            </tr>
            <tr>
                <td class="label">Bonus</td>
                <td class="value">{{ $idr((int) $gaji->bonus) }}</td>
            </tr>
        </table>

        <div class="total">
            <table class="grid">
                <tr>
                    <td class="label" style="font-weight: 900; color: #0F172A;">Total Gaji</td>
                    <td class="value">{{ $idr((int) $gaji->nominal) }}</td>
                </tr>
            </table>
        </div>

        <table class="sign">
            <tr>
                <td>
                    Diterima oleh,
                    <div class="line"></div>
                    {{ $gaji->karyawan->nama }}
                </td>
                <td style="text-align: right;">
                    Disetujui oleh,
                    <div class="line"></div>
                    {{ config('app.name', 'Usaha Baraya') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="foot">
        Dokumen ini di-generate otomatis oleh sistem dan berlaku sebagai bukti pembayaran gaji.
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_text(28, 820, "Usaha Baraya · Slip Gaji", null, 9, array(71, 85, 105));
            $pdf->page_text(520, 820, "Halaman {PAGE_NUM}/{PAGE_COUNT}", null, 9, array(71, 85, 105));
        }
    </script>
</body>
</html>
