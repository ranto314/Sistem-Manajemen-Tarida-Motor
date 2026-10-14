<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tarif Layanan & Jasa Servis - Tarida Motor</title>
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
            🖨️ Cetak Daftar Tarif Jasa
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
                <div class="badge bg-dark fs-6 px-3 py-2">TARIF RESMI BENGKEL</div>
                <div class="small mt-1 text-muted">{{ Carbon\Carbon::now()->format('d F Y') }}</div>
            </div>
        </div>

        <div class="text-center my-3">
            <h4 class="fw-bold mb-1 text-uppercase">DAFTAR TARIF ONGKOS LAYANAN & JASA SERVIS</h4>
            <p class="text-muted small">Tarif standar pengerjaan teknisi dan pemasangan sparepart di tempat</p>
        </div>

        <table class="table table-bordered table-striped align-middle mt-3">
            <thead>
                <tr class="text-center align-middle">
                    <th style="width: 40px;">No</th>
                    <th>Kode Jasa</th>
                    <th>Nama Layanan / Jasa Pemasangan</th>
                    <th>Kategori Pekerjaan</th>
                    <th>Keterangan / Detail Servis</th>
                    <th class="text-end">Tarif Biaya (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $index => $s)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-monospace fw-bold">{{ $s->kode_jasa ?: 'JS-'.$s->id }}</td>
                    <td><strong>{{ $s->nama_jasa }}</strong></td>
                    <td>{{ $s->kategori_jasa ?: 'Umum' }}</td>
                    <td>{{ $s->keterangan ?: '-' }}</td>
                    <td class="text-end fw-bold">Rp {{ number_format($s->harga_jasa, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-3 text-muted">Tidak ada data layanan jasa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- TANDA TANGAN -->
        <div class="row mt-5 pt-4">
            <div class="col-6 text-center">
                <p class="mb-5">Kepala Mekanik,</p>
                <strong><u>Mekanik Senior Tarida Motor</u></strong>
                <p class="text-muted small">Teknisi Bengkel</p>
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
