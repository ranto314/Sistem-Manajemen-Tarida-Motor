@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">

    <!-- HEADER & ACTION -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 no-print">
        <div>
            <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-receipt text-danger me-2"></i> Riwayat Transaksi Kasir</h4>
            <p class="text-muted mb-0">Semua riwayat transaksi pembayaran dan cetak ulang struk pelanggan Bengkel Tarida Motor</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('transaksi.report', request()->query()) }}" target="_blank" class="btn btn-outline-dark rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak Laporan Rekap Penjualan
            </a>
            <a href="{{ route('kasir.index') }}" class="btn btn-danger rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 shadow-sm">
                <i class="fa-solid fa-plus-circle"></i> Buka Kasir Baru
            </a>
        </div>
    </div>

    <!-- STATS BANNER -->
    <div class="row g-3 mb-4 no-print">
        <div class="col-sm-6 col-md-4">
            <div class="card card-custom p-3 bg-white">
                <small class="text-muted fw-semibold">Total Transaksi Ditemukan</small>
                <h3 class="fw-bold text-dark mb-0">{{ $totalCount }} Transaksi</h3>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card card-custom p-3 bg-white">
                <small class="text-muted fw-semibold">Total Nilai Transaksi / Omset</small>
                <h3 class="fw-bold text-danger mb-0">Rp {{ number_format($totalOmset, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            <div class="card card-custom p-3 bg-white">
                <small class="text-muted fw-semibold">Metode Pembayaran Resmi</small>
                <div class="d-flex gap-2 mt-2">
                    <span class="badge badge-payment-cash px-2 py-1 rounded-pill">Cash</span>
                    <span class="badge badge-payment-qris px-2 py-1 rounded-pill">QRIS</span>
                    <span class="badge badge-payment-bri px-2 py-1 rounded-pill">Bank BRI</span>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="card card-custom mb-4 no-print">
        <div class="card-body p-3">
            <form action="{{ route('transaksi.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <input type="text" name="cari" class="form-control bg-light" placeholder="Cari invoice, pelanggan, plat..." value="{{ request('cari') }}">
                </div>
                <div class="col-md-2">
                    <select name="metode" class="form-select bg-light" onchange="this.form.submit()">
                        <option value="">-- Semua Metode --</option>
                        <option value="Cash" {{ request('metode') == 'Cash' ? 'selected' : '' }}>Cash (Tunai)</option>
                        <option value="QRIS" {{ request('metode') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                        <option value="Transfer Bank BRI" {{ request('metode') == 'Transfer Bank BRI' ? 'selected' : '' }}>Transfer Bank BRI</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="tanggal_mulai" class="form-control bg-light" value="{{ request('tanggal_mulai') }}" title="Tanggal Mulai">
                </div>
                <div class="col-md-2">
                    <input type="date" name="tanggal_selesai" class="form-control bg-light" value="{{ request('tanggal_selesai') }}" title="Tanggal Selesai">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-100 rounded-pill fw-semibold">Filter</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary rounded-pill" title="Reset Filter"><i class="fa-solid fa-rotate-right"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- TRANSACTIONS TABLE -->
    <div class="card card-custom">
        <div class="card-header bg-white py-3 px-4 border-bottom">
            <h5 class="fw-bold mb-0 text-dark">Daftar Transaksi ({{ $transactions->count() }} Data)</h5>
        </div>
        <div class="card-body p-0">
            @if($transactions->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-receipt fs-2 mb-2 d-block"></i>
                    Belum ada riwayat transaksi yang ditemukan.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary" style="font-size: 0.82rem; text-transform: uppercase;">
                            <tr>
                                <th class="ps-4">No. Invoice</th>
                                <th>Tanggal & Waktu</th>
                                <th>Pelanggan</th>
                                <th>Plat Motor</th>
                                <th>Kasir</th>
                                <th>Metode Bayar</th>
                                <th class="text-end">Total Bayar</th>
                                <th class="text-center pe-4 no-print">Aksi & Struk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $t)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light text-dark border font-monospace px-2 py-1 fw-bold fs-6">
                                        {{ $t->kode_transaksi }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-dark fw-semibold">{{ $t->created_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $t->created_at->format('H:i') }} WIB</small>
                                </td>
                                <td>
                                    <strong class="text-dark">{{ $t->nama_pelanggan }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border font-monospace px-2 py-1">
                                        {{ $t->nomor_polisi ?: '-' }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $t->user->name ?? 'Admin' }}</small>
                                </td>
                                <td>
                                    @if($t->metode_pembayaran === 'Cash')
                                        <span class="badge badge-payment-cash px-3 py-1 rounded-pill">
                                            <i class="fa-solid fa-money-bill-wave me-1"></i> Cash
                                        </span>
                                    @elseif($t->metode_pembayaran === 'QRIS')
                                        <span class="badge badge-payment-qris px-3 py-1 rounded-pill">
                                            <i class="fa-solid fa-qrcode me-1"></i> QRIS
                                        </span>
                                    @elseif($t->metode_pembayaran === 'Transfer Bank BRI')
                                        <span class="badge badge-payment-bri px-3 py-1 rounded-pill">
                                            <i class="fa-solid fa-building-columns me-1"></i> Bank BRI
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-dark fs-6">
                                    Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="text-center pe-4 no-print">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-info" title="Lihat Rincian Item" onclick="lihatDetailTrx({{ $t->id }})">
                                            <i class="fa-solid fa-eye"></i> Detail
                                        </button>
                                        <a href="{{ route('kasir.receipt', $t->id) }}" target="_blank" class="btn btn-sm btn-outline-dark" title="Cetak Struk">
                                            <i class="fa-solid fa-print"></i> Struk
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>

<!-- MODAL DETAIL TRANSAKSI -->
<div class="modal fade" id="modalDetailTrx" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-file-invoice text-danger me-2"></i> Rincian Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="detailTrxBody">
                <div class="text-center py-4"><i class="fa-solid fa-spinner fa-spin fs-4 text-secondary"></i> Memuat data...</div>
            </div>
            <div class="modal-footer border-top py-3">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="btnCetakModalStruk" target="_blank" class="btn btn-dark rounded-pill px-4 fw-bold">
                    <i class="fa-solid fa-print me-1"></i> Cetak Struk
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function lihatDetailTrx(id) {
        const body = document.getElementById('detailTrxBody');
        body.innerHTML = '<div class="text-center py-4"><i class="fa-solid fa-spinner fa-spin fs-4 text-secondary"></i> Memuat data...</div>';
        
        document.getElementById('btnCetakModalStruk').href = `/kasir/struk/${id}`;
        new bootstrap.Modal(document.getElementById('modalDetailTrx')).show();

        fetch(`/transaksi/${id}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(trx => {
            let itemsHtml = '';
            trx.details.forEach(d => {
                itemsHtml += `
                    <tr>
                        <td>
                            <strong>${d.nama_item}</strong>
                            <small class="d-block text-muted font-monospace">${d.kode_barang || '-'}</small>
                        </td>
                        <td class="text-center">${d.jumlah} ${d.satuan}</td>
                        <td class="text-end">Rp ${parseFloat(d.harga_satuan).toLocaleString('id-ID')}</td>
                        <td class="text-end fw-bold">Rp ${parseFloat(d.subtotal).toLocaleString('id-ID')}</td>
                    </tr>
                `;
            });

            body.innerHTML = `
                <div class="bg-light p-3 rounded-3 border mb-3">
                    <div class="d-flex justify-content-between mb-1"><span>Invoice:</span><strong class="font-monospace">${trx.kode_transaksi}</strong></div>
                    <div class="d-flex justify-content-between mb-1"><span>Pelanggan:</span><span>${trx.nama_pelanggan} (${trx.nomor_polisi || '-'})</span></div>
                    <div class="d-flex justify-content-between mb-1"><span>Waktu:</span><span>${new Date(trx.created_at).toLocaleString('id-ID')}</span></div>
                    <div class="d-flex justify-content-between"><span>Metode Bayar:</span><span class="badge bg-dark">${trx.metode_pembayaran}</span></div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-sm table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml}
                        </tbody>
                    </table>
                </div>

                <div class="p-3 bg-light rounded-3 border">
                    <div class="d-flex justify-content-between fw-bold fs-6 mb-1"><span>Total Belanja:</span><span>Rp ${parseFloat(trx.total_harga).toLocaleString('id-ID')}</span></div>
                    <div class="d-flex justify-content-between text-muted small"><span>Uang Bayar:</span><span>Rp ${parseFloat(trx.bayar).toLocaleString('id-ID')}</span></div>
                    <div class="d-flex justify-content-between text-muted small"><span>Kembalian:</span><span>Rp ${parseFloat(trx.kembalian).toLocaleString('id-ID')}</span></div>
                </div>
            `;
        })
        .catch(err => {
            body.innerHTML = `<div class="alert alert-danger">Gagal memuat detail transaksi: ${err.message}</div>`;
        });
    }
</script>
@endpush
