<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Tarida Motor</title>
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

    <!-- ACTION BUTTONS -->
    <div class="text-center mb-4 no-print">
        <button onclick="window.print()" class="btn btn-primary px-4 py-2 fw-bold">
            🖨️ Cetak Dokumen Sekarang
        </button>
        <button onclick="window.close()" class="btn btn-secondary px-4 py-2">
            Tutup Halaman
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
                <div class="badge bg-dark fs-6 px-3 py-2">LAPORAN INVENTORI</div>
                <div class="small mt-1 text-muted">{{ Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</div>
            </div>
        </div>

        <!-- JUDUL LAPORAN -->
        <div class="text-center my-3">
            <h4 class="fw-bold mb-1 text-uppercase">{{ $title }}</h4>
            <p class="text-muted small">Status per tanggal {{ Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        </div>

        <!-- TABEL DATA PRODUK -->
        <table class="table table-bordered table-striped align-middle mt-3">
            <thead>
                <tr class="text-center align-middle">
                    <th style="width: 40px;">No</th>
                    <th>Kode SKU</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Peruntukan Motor</th>
                    <th>Satuan</th>
                    <th>Sisa Stok</th>
                    <th>Harga Jual (Rp)</th>
                    <th>Total Nilai (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totalStok = 0; 
                    $totalNilaiJual = 0; 
                @endphp
                @forelse($products as $index => $p)
                @php
                    $totalStok += $p->stok;
                    $subtotalNilai = $p->stok * $p->harga_jual;
                    $totalNilaiJual += $subtotalNilai;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-monospace fw-bold">{{ $p->kode_barang }}</td>
                    <td>
                        <strong>{{ $p->nama_barang }}</strong>
                        @if($p->keterangan)
                            <br><small class="text-muted">{{ $p->keterangan }}</small>
                        @endif
                    </td>
                    <td>{{ $p->category->nama_kategori ?? '-' }}</td>
                    <td>{{ $p->tipe_kendaraan ?: 'Universal' }}</td>
                    <td class="text-center">{{ $p->satuan }}</td>
                    <td class="text-center fw-bold">{{ $p->stok }}</td>
                    <td class="text-end">{{ number_format($p->harga_jual, 0, ',', '.') }}</td>
                    <td class="text-end fw-semibold">{{ number_format($subtotalNilai, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-3 text-muted">Tidak ada data produk.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="fw-bold bg-light">
                    <td colspan="6" class="text-end">TOTAL KESELURUHAN:</td>
                    <td class="text-center">{{ number_format($totalStok, 0, ',', '.') }} Unit</td>
                    <td></td>
                    <td class="text-end">Rp {{ number_format($totalNilaiJual, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- TANDA TANGAN -->
        <div class="row mt-5 pt-4">
            <div class="col-6 text-center">
                <p class="mb-5">Petugas Gudang / Kasir,</p>
                <strong><u>{{ auth()->user()->name }}</u></strong>
                <p class="text-muted small">Admin Tarida Motor</p>
            </div>
            <div class="col-6 text-center">
                <p class="mb-5">Penanggung Jawab Bengkel,</p>
                <strong><u>Pimpinan Tarida Motor</u></strong>
                <p class="text-muted small">Kepala Operasional</p>
            </div>
        </div>
    </div>

</body>
</html>
