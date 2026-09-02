<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran Ekstrakurikuler</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24pt;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14pt;
        }
        .filter-info {
            margin-bottom: 15px;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row {
            font-weight: bold;
            background-color: #e5e7eb;
        }
        .signature {
            float: right;
            width: 250px;
            text-align: center;
            margin-top: 30px;
        }
        .signature-space {
            height: 80px;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .badge-verified { background-color: #dcfce7; color: #166534; }
        .badge-pending { background-color: #fef08a; color: #854d0e; }
        .badge-unpaid { background-color: #fee2e2; color: #991b1b; }
        .badge-rejected { background-color: #f1f5f9; color: #475569; }
    </style>
</head>
<body>

    <div class="header">
        <h1>SMP SCIENCE MUTIARA INSANI</h1>
        <p>Laporan Riwayat Pembayaran Ekstrakurikuler Siswa</p>
    </div>

    <div class="filter-info">
        <p><strong>Tanggal Dicetak:</strong> {{ $printDate }}</p>
        @if($statusFilter)
            <p><strong>Filter Status:</strong> 
                @if($statusFilter == 'lunas') Lunas / Diverifikasi
                @elseif($statusFilter == 'belum_lunas') Belum Lunas
                @elseif($statusFilter == 'verifikasi') Menunggu Verifikasi
                @elseif($statusFilter == 'ditolak') Ditolak
                @endif
            </p>
        @endif
        @if($search)
            <p><strong>Pencarian:</strong> {{ $search }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">Siswa</th>
                <th width="20%">Ekstrakurikuler</th>
                <th width="15%">Bulan & Tahun</th>
                <th width="15%" class="text-center">Status</th>
                <th width="15%" class="text-right">Nominal</th>
                <th width="15%">Diverifikasi Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $index => $payment)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ $payment->student->studentProfile->full_name ?? $payment->student->email ?? '-' }}<br>
                        <small>{{ $payment->student->studentProfile->nisn ?? '-' }}</small>
                    </td>
                    <td>{{ $payment->extracurricular->name ?? '-' }}</td>
                    <td>{{ $payment->month }} {{ $payment->year }}</td>
                    <td class="text-center">
                        @if($payment->payment_status === 'verified')
                            <span class="badge badge-verified">LUNAS</span>
                        @elseif($payment->payment_status === 'pending')
                            <span class="badge badge-pending">VERIFIKASI</span>
                        @elseif($payment->payment_status === 'unpaid')
                            <span class="badge badge-unpaid">BELUM LUNAS</span>
                        @elseif($payment->payment_status === 'rejected')
                            <span class="badge badge-rejected">DITOLAK</span>
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</td>
                    <td>
                        @if($payment->payment_status === 'verified' && $payment->verifier)
                            {{ $payment->verifier->teacherProfile->full_name ?? 'Admin' }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data pembayaran yang ditemukan.</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="5" class="text-right">Total Pemasukan (Status Lunas):</td>
                <td class="text-right">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="signature">
        <p>Mengetahui,</p>
        <p><strong>Kepala Sekolah</strong></p>
        <div class="signature-space"></div>
        <p>_______________________</p>
    </div>

</body>
</html>
