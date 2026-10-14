@extends('layouts.app')

@section('content')
<div class="dashboard-container">

    <!-- WELCOME BANNER WITH MOTORCYCLE WORKSHOP THEME -->
    <div class="card border-0 mb-4 text-white position-relative overflow-hidden shadow-sm" 
         style="background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 60%, #b91c1c 100%); border-radius: 20px;">
        <div class="card-body p-4 p-md-5 position-relative" style="z-index: 2;">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-danger px-3 py-2 rounded-pill fw-semibold mb-2">
                        <i class="fa-solid fa-wrench me-1"></i> BENGKEL TARIDA MOTOR AKTIF
                    </span>
                    <h2 class="fw-bold mb-2 text-white">Selamat Datang, {{ auth()->user()->name }}! 🏍️</h2>
                    <p class="text-white-50 mb-4 fs-6" style="max-width: 650px;">
                        Kelola seluruh aktivitas bengkel secara terpadu: stok sparepart, oli, perlengkapan umum bengkel, transaksi kasir, serta cetak struk pembayaran QRIS, Tunai, dan Bank BRI.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('kasir.index') }}" class="btn btn-danger px-4 py-2 rounded-pill fw-bold d-flex align-items-center gap-2 shadow-sm">
                            <i class="fa-solid fa-cash-register"></i> Buka Kasir & POS
                        </a>
                        <a href="{{ route('jasa.index') }}" class="btn btn-outline-light px-4 py-2 rounded-pill fw-semibold d-flex align-items-center gap-2">
                            <i class="fa-solid fa-wrench"></i> Tarif Jasa & Pasang ({{ $totalJasa }})
                        </a>
                        <a href="{{ route('kategori.index') }}" class="btn btn-outline-light px-4 py-2 rounded-pill fw-semibold d-flex align-items-center gap-2">
                            <i class="fa-solid fa-tags"></i> Kelola Kategori
                        </a>
                        <a href="{{ route('produk.index') }}" class="btn btn-outline-light px-4 py-2 rounded-pill fw-semibold d-flex align-items-center gap-2">
                            <i class="fa-solid fa-boxes-stacked"></i> Master Produk
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 text-center d-none d-lg-block">
                    <div class="p-3">
                        <i class="fa-solid fa-motorcycle text-white-50" style="font-size: 7rem; opacity: 0.35;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ALERT STOK MENIPIS (JIKA ADA) -->
    @if($jumlahStokMenipis > 0)
    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center justify-content-between rounded-4 p-3 mb-4 animate-pulse">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                <i class="fa-solid fa-triangle-exclamation fs-5"></i>
            </div>
            <div>
                <strong class="d-block text-dark">Peringatan: Terdapat {{ $jumlahStokMenipis }} Barang dengan Stok Menipis! (&lt; 5 unit)</strong>
                <small class="text-secondary">Segera lakukan restok agar aktivitas servis bengkel tidak terganggu.</small>
            </div>
        </div>
        <a href="{{ route('produk.index', ['filter_stok' => 'menipis']) }}" class="btn btn-sm btn-dark rounded-pill px-3 fw-semibold">
            Lihat Barang Menipis
        </a>
    </div>
    @endif

    <!-- ANIMATED STAT CARDS ROW (JUMLAH KATEGORI, JUMLAH BARANG, TRANSAKSI TERAKHIR) -->
    <div class="row g-3 mb-4">
        <!-- 1. TOTAL KATEGORI -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card bg-white h-100 p-3 card-custom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Jumlah Kategori</span>
                        <h2 class="fw-extrabold text-dark mt-2 mb-0 animated-counter" data-target="{{ $totalKategori }}">0</h2>
                        <small class="text-primary fw-medium"><i class="fa-solid fa-layer-group me-1"></i> Kategori Produk Aktif</small>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. JUMLAH BARANG / PRODUK -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card bg-white h-100 p-3 card-custom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Jumlah Jenis Barang</span>
                        <h2 class="fw-extrabold text-dark mt-2 mb-0 animated-counter" data-target="{{ $totalBarang }}">0</h2>
                        <small class="text-success fw-medium"><i class="fa-solid fa-box-open me-1"></i> Total Stok: {{ number_format($totalStok, 0, ',', '.') }} unit</small>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. TOTAL TRANSAKSI TERAKHIR -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card bg-white h-100 p-3 card-custom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Total Transaksi</span>
                        <h2 class="fw-extrabold text-dark mt-2 mb-0 animated-counter" data-target="{{ $totalTransaksi }}">0</h2>
                        <small class="text-info fw-medium"><i class="fa-solid fa-calendar-check me-1"></i> {{ $transaksiHariIni }} Transaksi Hari Ini</small>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. TOTAL PENDAPATAN / OMSET -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card bg-white h-100 p-3 card-custom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Total Omset Kasir</span>
                        <h3 class="fw-extrabold text-dark mt-2 mb-0 text-truncate" style="font-size: 1.55rem;">
                            Rp <span class="animated-rupiah" data-target="{{ $totalPendapatan }}">0</span>
                        </h3>
                        <small class="text-danger fw-medium"><i class="fa-solid fa-money-bill-wave me-1"></i> Transaksi Berhasil</small>
                    </div>
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                        <i class="fa-solid fa-rupiah-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TRANSAKSI TERAKHIR & METODE PEMBAYARAN -->
    <div class="row g-4 mb-4">
        <!-- TABEL TRANSAKSI TERAKHIR -->
        <div class="col-xl-8">
            <div class="card card-custom h-100">
                <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-clock-rotate-left text-danger me-2"></i> Transaksi Terakhir Bengkel</h5>
                        <small class="text-muted">Aktivitas pembayaran kasir terkini dengan cetak struk instan</small>
                    </div>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold">
                        Semua Transaksi <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary" style="font-size: 0.8rem; text-transform: uppercase;">
                                <tr>
                                    <th class="ps-4">No. Invoice</th>
                                    <th>Pelanggan / Plat</th>
                                    <th>Waktu</th>
                                    <th>Metode Bayar</th>
                                    <th class="text-end">Total Belanja</th>
                                    <th class="text-center pe-4">Struk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksiTerakhir as $trx)
                                <tr class="table-row-animate">
                                    <td class="ps-4">
                                        <span class="fw-bold text-dark font-monospace">{{ $trx->kode_transaksi }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $trx->nama_pelanggan }}</div>
                                        <span class="badge bg-light text-dark border font-monospace px-2 py-0" style="font-size: 0.72rem;">
                                            <i class="fa-solid fa-id-card me-1 text-muted"></i>{{ $trx->nomor_polisi ?: '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted d-block">{{ $trx->created_at->format('d/m/Y') }}</small>
                                        <small class="text-secondary fw-semibold">{{ $trx->created_at->format('H:i') }} WIB</small>
                                    </td>
                                    <td>
                                        @if($trx->metode_pembayaran === 'Cash')
                                            <span class="badge badge-payment-cash px-3 py-1 rounded-pill">
                                                <i class="fa-solid fa-money-bill-1-wave me-1"></i> Cash
                                            </span>
                                        @elseif($trx->metode_pembayaran === 'QRIS')
                                            <span class="badge badge-payment-qris px-3 py-1 rounded-pill">
                                                <i class="fa-solid fa-qrcode me-1"></i> QRIS
                                            </span>
                                        @elseif($trx->metode_pembayaran === 'Transfer Bank BRI')
                                            <span class="badge badge-payment-bri px-3 py-1 rounded-pill">
                                                <i class="fa-solid fa-building-columns me-1"></i> Bank BRI
                                            </span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-1 rounded-pill">{{ $trx->metode_pembayaran }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-4">
                                        <a href="{{ route('kasir.receipt', $trx->id) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-2 py-1" title="Cetak Struk Transaksi Ini">
                                            <i class="fa-solid fa-print"></i> Struk
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-inbox fs-3 mb-2 d-block"></i> Belum ada transaksi yang tercatat.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- RINGKASAN METODE PEMBAYARAN KASIR -->
        <div class="col-xl-4">
            <div class="card card-custom h-100">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-wallet text-success me-2"></i> Metode Pembayaran</h5>
                    <small class="text-muted">Distribusi transaksi (QRIS, Tunai & Transfer BRI)</small>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <!-- Cash -->
                        @php
                            $cashTrx = $metodeStats->firstWhere('metode_pembayaran', 'Cash');
                            $cashCount = $cashTrx ? $cashTrx->count : 0;
                            $cashTotal = $cashTrx ? $cashTrx->total : 0;
                        @endphp
                        <div class="p-3 mb-3 rounded-3 border bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-success"><i class="fa-solid fa-money-bill-wave me-1"></i> Cash / Uang Tunai</span>
                                <span class="badge bg-success rounded-pill">{{ $cashCount }} Trx</span>
                            </div>
                            <h5 class="fw-bold text-dark mb-0">Rp {{ number_format($cashTotal, 0, ',', '.') }}</h5>
                        </div>

                        <!-- QRIS -->
                        @php
                            $qrisTrx = $metodeStats->firstWhere('metode_pembayaran', 'QRIS');
                            $qrisCount = $qrisTrx ? $qrisTrx->count : 0;
                            $qrisTotal = $qrisTrx ? $qrisTrx->total : 0;
                        @endphp
                        <div class="p-3 mb-3 rounded-3 border bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-info"><i class="fa-solid fa-qrcode me-1"></i> QRIS Tarida Motor</span>
                                <span class="badge bg-info text-white rounded-pill">{{ $qrisCount }} Trx</span>
                            </div>
                            <h5 class="fw-bold text-dark mb-0">Rp {{ number_format($qrisTotal, 0, ',', '.') }}</h5>
                        </div>

                        <!-- BRI -->
                        @php
                            $briTrx = $metodeStats->firstWhere('metode_pembayaran', 'Transfer Bank BRI');
                            $briCount = $briTrx ? $briTrx->count : 0;
                            $briTotal = $briTrx ? $briTrx->total : 0;
                        @endphp
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-primary"><i class="fa-solid fa-building-columns me-1"></i> Transfer Bank BRI</span>
                                <span class="badge bg-primary rounded-pill">{{ $briCount }} Trx</span>
                            </div>
                            <h5 class="fw-bold text-dark mb-0">Rp {{ number_format($briTotal, 0, ',', '.') }}</h5>
                            <small class="text-muted" style="font-size: 0.75rem;">Rek BRI: 0123-01-098765-53-1 (TARIDA MOTOR)</small>
                        </div>
                    </div>

                    <div class="mt-4 pt-2 border-top text-center">
                        <a href="{{ route('kasir.index') }}" class="btn btn-danger w-100 rounded-pill py-2 fw-bold">
                            <i class="fa-solid fa-plus-circle me-1"></i> Buat Transaksi Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AKSES CEPAT KATEGORI PRODUK BENGKEL (DENGAN ANIMASI HOVER) -->
    <div class="card card-custom mb-4">
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
            <div>
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-layer-group text-primary me-2"></i> Kategori Produk Bengkel Tarida Motor</h5>
                <small class="text-muted">Klik kategori untuk melihat data, melakukan CRUD, cetak laporan, dan cari barang</small>
            </div>
            <a href="{{ route('kategori.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                <i class="fa-solid fa-gear me-1"></i> Kelola Kategori
            </a>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                @foreach($kategoriList as $cat)
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <a href="{{ route('produk.byCategory', $cat->id) }}" class="text-decoration-none">
                        <div class="p-3 rounded-4 border bg-white h-100 category-card-hover d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="category-icon-box text-danger bg-danger bg-opacity-10 rounded-3 p-3 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid {{ $cat->icon ?: 'fa-box' }} fs-4"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold text-dark text-truncate mb-1">{{ $cat->nama_kategori }}</h6>
                                    <small class="text-muted text-truncate d-block" style="font-size: 0.78rem;">
                                        {{ $cat->deskripsi ?: 'Kategori sparepart/servis' }}
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <span class="badge bg-light text-dark border rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                    {{ $cat->products_count }} Jenis Barang
                                </span>
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                    Stok: {{ $cat->products_sum_stok ?: 0 }}
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    /* CSS Animations for CountUp and Dashboard */
    .fw-extrabold {
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .category-card-hover {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .category-card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(220, 38, 38, 0.12);
        border-color: #ef4444 !important;
    }

    .category-icon-box {
        width: 50px;
        height: 50px;
        flex-shrink: 0;
        transition: transform 0.25s ease;
    }
    .category-card-hover:hover .category-icon-box {
        transform: scale(1.1) rotate(5deg);
    }

    .table-row-animate {
        transition: background-color 0.2s ease;
    }
    .table-row-animate:hover {
        background-color: #f1f5f9 !important;
    }

    /* Pulse animation for alert */
    @keyframes pulse-subtle {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.95; transform: scale(1.005); }
    }
    .animate-pulse {
        animation: pulse-subtle 3s infinite ease-in-out;
    }
</style>
@endpush

@push('scripts')
<script>
    // SCRIPT ANIMASI COUNT-UP UNTUK STATISTIK ANGKA DASHBOARD
    document.addEventListener('DOMContentLoaded', () => {
        // Animasi untuk angka biasa (Kategori, Barang, Transaksi)
        const counters = document.querySelectorAll('.animated-counter');
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const duration = 1200; // 1.2 detik
            const frameRate = 30;
            const totalFrames = Math.round(duration / (1000 / frameRate));
            let frame = 0;

            const timer = setInterval(() => {
                frame++;
                const progress = frame / totalFrames;
                // Easing out cubic
                const current = Math.round(target * (1 - Math.pow(1 - progress, 3)));
                counter.innerText = current.toLocaleString('id-ID');

                if (frame === totalFrames) {
                    counter.innerText = target.toLocaleString('id-ID');
                    clearInterval(timer);
                }
            }, 1000 / frameRate);
        });

        // Animasi untuk angka Rupiah
        const rupiahCounters = document.querySelectorAll('.animated-rupiah');
        rupiahCounters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const duration = 1500; // 1.5 detik
            const frameRate = 30;
            const totalFrames = Math.round(duration / (1000 / frameRate));
            let frame = 0;

            const timer = setInterval(() => {
                frame++;
                const progress = frame / totalFrames;
                const current = Math.round(target * (1 - Math.pow(1 - progress, 3)));
                counter.innerText = current.toLocaleString('id-ID');

                if (frame === totalFrames) {
                    counter.innerText = target.toLocaleString('id-ID');
                    clearInterval(timer);
                }
            }, 1000 / frameRate);
        });
    });
</script>
@endpush