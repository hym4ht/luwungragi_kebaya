<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Sewa – {{ $rental->invoice_number }}</title>
    <style>
        @page {
            margin: 12mm 10mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9pt;
            color: #2c2c2c;
            background: #fff;
            line-height: 1.4;
        }

        /* ── Header ─────────────────────────────── */
        .header {
            text-align: center;
            border-bottom: 2px solid #580d21;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .header .brand {
            font-size: 20pt;
            font-weight: bold;
            color: #580d21;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .header .tagline {
            font-size: 7.5pt;
            color: #79665e;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 3px;
        }
        .header .invoice-no {
            margin-top: 8px;
            font-size: 9pt;
            font-weight: bold;
            color: #580d21;
            letter-spacing: 1px;
        }

        /* ── Section title ──────────────────────── */
        .section-title {
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #79665e;
            border-bottom: 1px solid #e8ddd5;
            padding-bottom: 4px;
            margin-bottom: 8px;
            margin-top: 12px;
        }

        /* ── Info grid (using table for compatibility) ── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .info-table .lbl {
            width: 35%;
            font-size: 7.5pt;
            color: #79665e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-table .val {
            width: 65%;
            font-size: 8.5pt;
            font-weight: bold;
            color: #2c2c2c;
        }

        /* ── Status badge ───────────────────────── */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 2px;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-pending    { background-color: #fef3c7; color: #92400e; }
        .badge-settlement { background-color: #d1fae5; color: #065f46; }
        .badge-active     { background-color: #dbeafe; color: #1e40af; }
        .badge-completed  { background-color: #f3f4f6; color: #374151; }
        .badge-cancelled  { background-color: #fee2e2; color: #991b1b; }

        /* ── Items table ────────────────────────── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 12px;
        }
        .items-table th {
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #79665e;
            border-bottom: 1.5px solid #580d21;
            padding: 5px 0;
        }
        .items-table td {
            font-size: 8.5pt;
            padding: 6px 0;
            border-bottom: 1px dashed #e8ddd5;
            vertical-align: middle;
        }
        .items-table tfoot td {
            font-size: 10pt;
            font-weight: bold;
            padding: 8px 0;
            border-top: 1.5px solid #580d21;
            border-bottom: none;
            color: #580d21;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* ── Session info box ───────────────────── */
        .session-box {
            background: #faf6f0;
            border: 1px solid #e8ddd5;
            padding: 8px 12px;
            margin-top: 6px;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        .session-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .session-box td {
            padding: 3px 0;
            font-size: 8pt;
        }
        .session-box .session-lbl {
            color: #79665e;
        }
        .session-box .session-val {
            font-weight: bold;
            color: #2c2c2c;
        }
        .session-box .session-val-highlight {
            font-weight: bold;
            color: #580d21;
        }

        /* ── Catatan Denda ──────────────────────── */
        .alert-box {
            margin-top: 12px;
            font-size: 7.5pt;
            color: #580d21;
            background-color: #faf0f2;
            border: 1px dashed #580d21;
            padding: 8px 12px;
            border-radius: 3px;
            line-height: 1.4;
        }

        /* ── Footer ─────────────────────────────── */
        .footer {
            margin-top: 25px;
            border-top: 1px dashed #e8ddd5;
            padding-top: 12px;
            text-align: center;
            font-size: 7.5pt;
            color: #79665e;
            line-height: 1.5;
        }
        .footer .thank-you {
            font-size: 10pt;
            font-weight: bold;
            color: #580d21;
            margin-bottom: 4px;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

{{-- ── Header ── --}}
<div class="header">
    <div class="brand">Luwungragi</div>
    <div class="tagline">Heritage Costume Rental</div>
    <div class="invoice-no">{{ $rental->invoice_number }}</div>
</div>

{{-- ── Informasi Penyewa ── --}}
<div class="section-title">Informasi Penyewa</div>
<table class="info-table">
    <tr>
        <td class="lbl">Nama Penyewa</td>
        <td class="val">{{ $rental->user->name }}</td>
    </tr>
    <tr>
        <td class="lbl">Email</td>
        <td class="val">{{ $rental->user->email }}</td>
    </tr>
    <tr>
        <td class="lbl">Tanggal Pemesanan</td>
        <td class="val">{{ $rental->booking_start_date->format('d M Y') }}</td>
    </tr>
</table>

{{-- ── Jadwal Sewa ── --}}
<div class="section-title">Jadwal Penyewaan ({{ $rental->sessions_count }} Sesi = {{ $rental->rental_duration_days }} Hari)</div>
<div class="session-box">
    <table>
        <tr>
            <td class="session-lbl">Tanggal Ambil (Offline)</td>
            <td class="session-val text-right">{{ $rental->pickup_date->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="session-lbl">Mulai Pakai (Event)</td>
            <td class="session-val text-right">{{ $rental->usage_date->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="session-lbl">Selesai Pakai</td>
            <td class="session-val text-right">{{ $rental->usage_end_date->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="session-lbl">Batas Pengembalian</td>
            <td class="session-val-highlight text-right">{{ $rental->return_due_date->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="session-lbl">Batas Pelunasan</td>
            <td class="session-val text-right">{{ $rental->payment_due_date->format('d M Y') }}</td>
        </tr>
    </table>
</div>

{{-- ── Detail Busana ── --}}
<div class="section-title">Detail Busana</div>
<table class="items-table">
    <thead>
        <tr>
            <th style="text-align: left; width: 45%;">Nama Busana</th>
            <th class="text-center" style="width: 10%;">Qty</th>
            <th class="text-right" style="width: 20%;">Harga/Sesi</th>
            <th class="text-right" style="width: 25%;">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rental->details as $detail)
        <tr>
            <td>{{ $detail->costume->name }}</td>
            <td class="text-center">{{ $detail->quantity }}</td>
            <td class="text-right">Rp{{ number_format((float) $detail->unit_price, 0, ',', '.') }}</td>
            <td class="text-right">Rp{{ number_format((float) $detail->unit_price * $detail->quantity * $rental->sessions_count, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right" style="font-size: 8.5pt; color: #79665e;">Total Bayar ({{ $rental->sessions_count }} Sesi):</td>
            <td class="text-right">Rp{{ number_format((float) $rental->total_price, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>

{{-- ── Status & Pembayaran ── --}}
<div class="section-title">Status Transaksi</div>
<table class="info-table">
    <tr>
        <td class="lbl">Status Sewa</td>
        <td class="val">
            <span class="badge badge-{{ strtolower($rental->status->value) }}">{{ $rental->status->label() }}</span>
        </td>
    </tr>
    @if($rental->payment)
    <tr>
        <td class="lbl">Status Pembayaran</td>
        <td class="val">
            <span class="badge badge-{{ $rental->payment->status->value }}">{{ $rental->payment->status->label() }}</span>
        </td>
    </tr>
    @if($rental->payment->paid_at)
    <tr>
        <td class="lbl">Tanggal Lunas</td>
        <td class="val">{{ $rental->payment->paid_at->format('d M Y, H:i') }} WIB</td>
    </tr>
    @endif
    <tr>
        <td class="lbl">Metode Pembayaran</td>
        <td class="val">{{ $rental->payment->payment_type ?? 'Midtrans' }}</td>
    </tr>
    @endif
</table>

{{-- ── Pengembalian (jika ada) ── --}}
@if($rental->returnRecord)
<div class="section-title">Informasi Pengembalian</div>
<table class="info-table">
    <tr>
        <td class="lbl">Tanggal Kembali</td>
        <td class="val">{{ $rental->returnRecord->returned_date->format('d M Y') }}</td>
    </tr>
    <tr>
        <td class="lbl">Status Pengembalian</td>
        <td class="val">{{ $rental->returnRecord->return_status }}</td>
    </tr>
    @if((float)$rental->returnRecord->fine_amount > 0)
    <tr>
        <td class="lbl">Denda Keterlambatan</td>
        <td class="val" style="color: #991b1b; font-weight: bold;">Rp{{ number_format((float)$rental->returnRecord->fine_amount, 0, ',', '.') }}</td>
    </tr>
    @endif
</table>
@endif

{{-- ── Catatan Denda ── --}}
<div class="alert-box">
    <strong>PENTING:</strong> Keterlambatan pengembalian dikenakan denda sebesar <strong>Rp{{ number_format(\App\Models\Rental::LATE_FEE_PER_DAY, 0, ',', '.') }}/hari</strong> dihitung sejak batas pengembalian terlampaui. Mohon pastikan busana dikembalikan dalam kondisi baik.
</div>

{{-- ── Footer ── --}}
<div class="footer">
    <div class="thank-you">Terima Kasih Atas Kepercayaan Anda</div>
    <div>Luwungragi Heritage Costume Rental &middot; luwungragi.id</div>
    <div style="margin-top: 6px; font-size: 7pt; color: #a09088;">
        Dicetak secara otomatis pada {{ now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB
    </div>
</div>

</body>
</html>
