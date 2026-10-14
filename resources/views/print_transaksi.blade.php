<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekap Transaksi - Tarida Motor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111;
            background-color: #f8fafc;
            padding: 20px;
        }
        .sheet {
            background: white;
            max-width: 950px;
            margin: auto;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-radius: 8px;
        }
        .header-kop {
            border-bottom: 3px double #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        table {
            font-size: 0.88rem;
        }
        th {
            background-color: #f1f5f9 !important;
        }
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .sheet {
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="text-center mb-4 no-print">
        <button onclick="window.print()" class="btn btn-primary px-4 py-2 fw-bold">
            🖨️ Cetak Laporan Rekap Penjualan
        </button>
        <button onclick="window.close()" class="btn btn-secondary px-4 py-2">
            Tutup
        </button>
    </div>

    <div class="sheet">
        <!-- KOP SURAT BENGKEL -->
        <div class="header-kop d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold mb-0 text-dark">TARIDA MOTOR</h2>
                <div class="fw-semibold">Bengkel Servis Kendaraan Bermotor & Toko Sparepart</div>
                <small class="text-muted">Jl. Raya Duta Pelni No.50, Tugu, Kec. Cimanggis, Kota Depok, Jawa Barat 16951 &bull; Telp: 081214287931 &bull; Email: bengkel@taridamotor.com</small>
            </div>
            <div class="text-end">
                <div class="badge bg-danger fs-6 px-3 py-2">REKAP PENJUALAN</div>
                <div class="small mt-1 text-muted">{{ Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</div>
            </div>
        </div>

        <div class="text-center my-3">
            <h4 class="fw-bold mb-1 text-uppercase">LAPORAN REKAPITULASI TRANSAKSI PENJUALAN</h4>
            <p class="text-muted small">
                Periode: 
                {{ $request->tanggal_mulai ? Carbon\Carbon::parse($request->tanggal_mulai)->format('d/m/Y') : 'Semua' }} 
                s/d 
                {{ $request->tanggal_selesai ? Carbon\Carbon::parse($request->tanggal_selesai)->format('d/m/Y') : 'Sekarang' }}
                @if($request->metode)
                    &bull; Metode: {{ $request->metode }}
                @endif
            </p>
        </div>

        <table class="table table-bordered table-striped align-middle mt-3">
            <thead>
                <tr class="text-center align-middle">
                    <th style="width: 40px;">No</th>
                    <th>No. Invoice</th>
                    <th>Waktu</th>
                    <th>Pelanggan</th>
                    <th>Plat Motor</th>
                    <th>Metode Pembayaran</th>
                    <th class="text-end">Total Belanja (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $index => $t)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-monospace fw-bold">{{ $t->kode_transaksi }}</td>
                    <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $t->nama_pelanggan }}</td>
                    <td class="text-center font-monospace">{{ $t->nomor_polisi ?: '-' }}</td>
                    <td class="text-center fw-semibold">{{ $t->metode_pembayaran }}</td>
                    <td class="text-end fw-bold">{{ number_format($t->total_harga, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-3 text-muted">Tidak ada transaksi ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="fw-bold bg-light">
                    <td colspan="6" class="text-end">TOTAL OMSET KESELURUHAN:</td>
                    <td class="text-end text-danger fs-6">Rp {{ number_format($totalOmset, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- TANDA TANGAN -->
        <div class="row mt-5 pt-4">
            <div class="col-6 text-center">
                <p class="mb-5">Kasir Bengkel,</p>
                <strong><u>{{ auth()->user()->name }}</u></strong>
                <p class="text-muted small">Admin Tarida Motor</p>
            </div>
            <div class="col-6 text-center">
                <p class="mb-5">Pimpinan Bengkel,</p>
                <strong><u>Pimpinan Tarida Motor</u></strong>
                <p class="text-muted small">Kepala Operasional</p>
            </div>
        </div>
    </div>

</body>
</html>
