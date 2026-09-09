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
            text-align: center;
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
    </style>
</head>
<body>

    <div class="header">
        <h1>SMP SCIENCE MUTIARA INSANI</h1>
        <p>Laporan Riwayat Pembayaran Ekstrakurikuler Siswa</p>
    </div>

    <div class="filter-info">
        <p><strong>Tanggal Dicetak:</strong> {{ $printDate }}</p>
        @if($monthFilter || $yearFilter)
            <p><strong>Periode:</strong> {{ $monthFilter ?? 'Semua Bulan' }} {{ $yearFilter ?? '' }}</p>
        @endif
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
                <th width="5%">No</th>
                <th width="18%">Nama</th>
                <th width="12%">NISN</th>
                <th width="15%">Ekstrakurikuler</th>
                <th width="14%">Bulan & Tahun</th>
                <th width="11%">Status</th>
                <th width="12%">Nominal</th>
                <th width="13%">Diverifikasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $index => $payment)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $payment->student->studentProfile->full_name ?? $payment->student->email ?? '-' }}</td>
                    <td>{{ $payment->student->studentProfile->nisn ?? '-' }}</td>
                    <td>{{ $payment->extracurricular->name ?? '-' }}</td>
                    <td>{{ $payment->month }} {{ $payment->year }}</td>
                    <td class="text-center">
                        @if($payment->payment_status === 'verified')
                            LUNAS
                        @elseif($payment->payment_status === 'pending')
                            VERIFIKASI
                        @elseif($payment->payment_status === 'unpaid')
                            BELUM LUNAS
                        @elseif($payment->payment_status === 'rejected')
                            DITOLAK
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
                    <td colspan="8" class="text-center">Tidak ada data pembayaran yang ditemukan.</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="6" class="text-right">Total Pemasukan (Status Lunas):</td>
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
