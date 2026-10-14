<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran #{{ $transaction->kode_transaksi }} - Tarida Motor</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            background-color: #f1f5f9;
            color: #000;
            padding: 20px;
        }
        .receipt-container {
            width: 320px;
            margin: auto;
            background: #fff;
            padding: 20px 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            font-size: 12px;
            line-height: 1.4;
        }
        .divider {
            border-top: 1px dashed #444;
            margin: 8px 0;
        }
        .double-divider {
            border-top: 2px solid #000;
            margin: 8px 0;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: 700; }
        .d-flex { display: flex; }
        .justify-content-between { justify-content: space-between; }

        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .receipt-container {
                box-shadow: none !important;
                padding: 5px 0 !important;
                width: 100% !important;
                max-width: 58mm; /* Cocok untuk printer thermal 58mm atau 80mm */
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Cetak Manual -->
    <div class="text-center mb-3 no-print">
        <button onclick="window.print()" class="btn btn-dark btn-sm px-4 py-2 fw-bold">
            🖨️ Cetak Struk (Print)
        </button>
        <button onclick="window.close()" class="btn btn-outline-secondary btn-sm px-3 py-2">
            Tutup
        </button>
    </div>

    <div class="receipt-container">
        <!-- HEADER BENGKEL -->
        <div class="text-center">
            <h4 class="fw-bold mb-1" style="font-size: 16px; letter-spacing: 1px;">TARIDA MOTOR</h4>
            <div style="font-size: 11px;">BENGKEL & TOKO SPAREPART</div>
            <div style="font-size: 10px;">Jl. Raya Duta Pelni No.50, Tugu, Kec. Cimanggis, Kota Depok, Jawa Barat 16951</div>
            <div style="font-size: 10px;">Telp / WA: 081214287931</div>
        </div>

        <div class="double-divider"></div>

        <!-- METADATA TRANSAKSI -->
        <div style="font-size: 11px;">
            <div class="d-flex justify-content-between">
                <span>No. Struk</span>
                <span class="fw-bold">{{ $transaction->kode_transaksi }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Waktu</span>
                <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Kasir</span>
                <span>{{ $transaction->user->name ?? 'Admin' }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Pelanggan</span>
                <span>{{ $transaction->nama_pelanggan ?: 'Umum' }}</span>
            </div>
            @if($transaction->nomor_polisi && $transaction->nomor_polisi !== '-')
            <div class="d-flex justify-content-between">
                <span>No. Plat</span>
                <span class="fw-bold">{{ $transaction->nomor_polisi }}</span>
            </div>
            @endif
        </div>

        <div class="divider"></div>

        <!-- DAFTAR ITEM BELANJA -->
        <div class="mb-1 fw-bold" style="font-size: 11px;">
            <div class="d-flex justify-content-between">
                <span>ITEM</span>
                <span>TOTAL</span>
            </div>
        </div>
        <div class="divider"></div>

        @foreach($transaction->details as $d)
        <div class="mb-2" style="font-size: 11px;">
            <div class="fw-bold text-truncate">
                @if($d->service_id || $d->satuan === 'Jasa')
                    <span style="border: 1px solid #000; padding: 0 3px; font-size: 9px; border-radius: 2px; margin-right: 3px; font-weight: bold;">[JASA]</span>
                @endif
                {{ $d->nama_item }}
            </div>
            <div class="d-flex justify-content-between text-muted" style="font-size: 10px;">
                <span>{{ $d->jumlah }} {{ $d->satuan }} x {{ number_format($d->harga_satuan, 0, ',', '.') }}</span>
                <span class="text-dark fw-bold">{{ number_format($d->subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
        @endforeach

        <div class="divider"></div>

        <!-- TOTAL & PEMBAYARAN -->
        <div style="font-size: 11px;">
            <div class="d-flex justify-content-between fw-bold py-1" style="font-size: 13px;">
                <span>TOTAL</span>
                <span>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</span>
            </div>

            <div class="d-flex justify-content-between py-1">
                <span>Metode Bayar</span>
                <span class="fw-bold">{{ $transaction->metode_pembayaran }}</span>
            </div>

            <div class="d-flex justify-content-between py-1">
                <span>Bayar</span>
                <span>Rp {{ number_format($transaction->bayar, 0, ',', '.') }}</span>
            </div>

            <div class="d-flex justify-content-between py-1 fw-bold">
                <span>Kembalian</span>
                <span>Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="double-divider"></div>

        <!-- FOOTER PESAN STRUK -->
        <div class="text-center" style="font-size: 10px;">
            <div class="fw-bold mb-1">TERIMA KASIH ATAS KUNJUNGAN ANDA!</div>
            <div>Tarida Motor Siap Melayani Kendaraan Anda</div>
            <div style="font-size: 9px; margin-top: 4px;" class="text-muted">
                Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.
            </div>
            <div style="margin-top: 6px; font-size: 8px;">
                *** SIMPAN STRUK INI SEBAGAI BUKTI PEMBAYARAN ***
            </div>
        </div>
    </div>

    <script>
        // Otomatis memicu dialog print jika dibuka
        window.addEventListener('load', () => {
            // Uncomment line below if automatic print dialog is preferred
            // window.print();
        });
    </script>
</body>
</html>
