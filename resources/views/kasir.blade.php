@extends('layouts.app')

@section('content')
<div class="pos-container">

    <div class="row g-4">
        <!-- ==================== KOLOM KIRI: KATALOG PRODUK & JASA ==================== -->
        <div class="col-lg-7 col-xl-8 no-print">
            <div class="card card-custom h-100">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">
                                <i class="fa-solid fa-cart-flatbed text-danger me-2"></i> Katalog Sparepart & Layanan Jasa Bengkel
                            </h5>
                            <small class="text-muted">Klik barang atau jasa untuk menambahkan ke keranjang transaksi kasir</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <!-- Switch Tipe Item: Semua, Sparepart, Jasa -->
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check" name="filter_item_type" id="typeAll" value="all" checked onchange="filterItemType('all')">
                                <label class="btn btn-outline-dark" for="typeAll">Semua</label>

                                <input type="radio" class="btn-check" name="filter_item_type" id="typeProduct" value="product" onchange="filterItemType('product')">
                                <label class="btn btn-outline-danger" for="typeProduct">📦 Barang</label>

                                <input type="radio" class="btn-check" name="filter_item_type" id="typeService" value="service" onchange="filterItemType('service')">
                                <label class="btn btn-outline-primary" for="typeService">🛠️ Jasa / Pasang</label>
                            </div>
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill font-monospace" id="productCountBadge">
                                {{ $products->count() + $services->count() }} Item
                            </span>
                        </div>
                    </div>

                    <!-- Search Box Real-Time -->
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" id="posSearchInput" class="form-control border-start-0 bg-light" placeholder="Cari nama barang, jasa pasang/servis, kode SKU, atau tipe motor..." onkeyup="filterPosProducts()">
                        <button class="btn btn-outline-secondary" type="button" onclick="clearPosSearch()">Bersihkan</button>
                    </div>

                    <!-- Category Pills Horizontal Scroll -->
                    <div class="category-pills-wrapper d-flex gap-2 overflow-x-auto pb-1" id="categoryPills">
                        <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 active cat-filter-btn" data-category="all" onclick="filterByCategory('all', this)">
                            Semua Item
                        </button>

                        <!-- Tab Khusus Jasa & Pemasangan -->
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 text-nowrap cat-filter-btn fw-bold" data-category="service" onclick="filterByCategory('service', this)">
                            <i class="fa-solid fa-wrench me-1"></i> Layanan & Jasa Servis ({{ $services->count() }})
                        </button>

                        @foreach($categories as $c)
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 text-nowrap cat-filter-btn" data-category="{{ $c->id }}" onclick="filterByCategory('{{ $c->id }}', this)">
                                <i class="fa-solid {{ $c->icon ?: 'fa-box' }} me-1 text-danger"></i> {{ $c->nama_kategori }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Catalog Grid (Produk & Jasa) -->
                <div class="card-body p-3 overflow-y-auto" style="max-height: 640px;">
                    <div class="row g-3" id="productGrid">
                        
                        <!-- 1. LOOP DAFTAR LAYANAN JASA SERVIS -->
                        @foreach($services as $s)
                        <div class="col-sm-6 col-md-4 col-xl-3 product-card-col service-item-col" 
                             data-id="{{ $s->id }}"
                             data-type="service"
                             data-code="{{ strtolower($s->kode_jasa ?: 'js-'.$s->id) }}"
                             data-name="{{ strtolower($s->nama_jasa) }}"
                             data-vehicle="jasa servis pasang"
                             data-category="service"
                             data-price="{{ $s->harga_jasa }}"
                             data-stock="999999"
                             data-unit="Jasa">
                            
                            <div class="product-item-card service-card p-3 rounded-4 border bg-white h-100 d-flex flex-column justify-content-between position-relative"
                                 style="border-color: #cbd5e1; background: linear-gradient(180deg, #ffffff 0%, #faf5ff 100%);"
                                 onclick="addToCartService({{ json_encode($s) }})">
                                
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-purple-subtle text-purple border font-monospace px-2 py-0" style="font-size: 0.72rem; background-color: #ede9fe; color: #6d28d9;">
                                            <i class="fa-solid fa-wrench me-1"></i> JASA
                                        </span>
                                        <span class="badge rounded-pill px-2 py-0" style="font-size: 0.68rem; background-color: #f3e8ff; color: #7e22ce;">
                                            Pasang di Tempat
                                        </span>
                                    </div>

                                    <h6 class="fw-bold text-dark text-clamp-2 mb-1" style="font-size: 0.88rem; min-height: 42px;">
                                        {{ $s->nama_jasa }}
                                    </h6>

                                    <small class="text-muted d-block text-truncate mb-2" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-tag me-1 text-primary"></i> {{ $s->kategori_jasa ?: 'Layanan Bengkel' }}
                                    </small>
                                </div>

                                <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                    <div class="fw-bold text-primary" style="font-size: 0.95rem;">
                                        Rp {{ number_format($s->harga_jasa, 0, ',', '.') }}
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" title="Tambah Jasa">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- 2. LOOP DAFTAR PRODUK BARANG / SPAREPART -->
                        @foreach($products as $p)
                        <div class="col-sm-6 col-md-4 col-xl-3 product-card-col product-item-col" 
                             data-id="{{ $p->id }}"
                             data-type="product"
                             data-code="{{ strtolower($p->kode_barang) }}"
                             data-name="{{ strtolower($p->nama_barang) }}"
                             data-vehicle="{{ strtolower($p->tipe_kendaraan ?? '') }}"
                             data-category="{{ $p->category_id }}"
                             data-price="{{ $p->harga_jual }}"
                             data-stock="{{ $p->stok }}"
                             data-unit="{{ $p->satuan }}">
                            
                            <div class="product-item-card p-3 rounded-4 border bg-white h-100 d-flex flex-column justify-content-between position-relative {{ $p->stok <= 0 ? 'out-of-stock' : '' }}"
                                 onclick="{{ $p->stok > 0 ? "addToCartProduct(" . json_encode($p) . ")" : "alertOutOfStock('" . addslashes($p->nama_barang) . "')" }}">
                                
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-light text-primary border font-monospace px-2 py-0" style="font-size: 0.72rem;">
                                            {{ $p->kode_barang }}
                                        </span>
                                        @if($p->stok <= 0)
                                            <span class="badge bg-danger rounded-pill px-2 py-0" style="font-size: 0.7rem;">Habis</span>
                                        @elseif($p->stok < 5)
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-2 py-0" style="font-size: 0.7rem;">Sisa {{ $p->stok }}</span>
                                        @else
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-0" style="font-size: 0.7rem;">Stok {{ $p->stok }}</span>
                                        @endif
                                    </div>

                                    <h6 class="fw-bold text-dark text-clamp-2 mb-1" style="font-size: 0.88rem; min-height: 42px;">
                                        {{ $p->nama_barang }}
                                    </h6>

                                    <small class="text-muted d-block text-truncate mb-2" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-motorcycle me-1 text-secondary"></i> {{ $p->tipe_kendaraan ?: 'Universal' }}
                                    </small>
                                </div>

                                <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                    <div class="fw-bold text-danger" style="font-size: 0.95rem;">
                                        Rp {{ number_format($p->harga_jual, 0, ',', '.') }}
                                    </div>
                                    <button type="button" class="btn btn-sm btn-light border rounded-circle text-danger p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" title="Tambah Barang">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div id="noProductsFound" class="text-center py-5 d-none text-muted">
                        <i class="fa-solid fa-magnifying-glass fs-2 mb-2 d-block"></i>
                        Tidak ada barang atau layanan jasa yang sesuai dengan pencarian.
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== KOLOM KANAN: KERANJANG GABUNGAN & PEMBAYARAN ==================== -->
        <div class="col-lg-5 col-xl-4 no-print">
            <div class="card card-custom h-100">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-receipt text-danger me-2"></i> Keranjang Kasir</h5>
                        <small class="text-muted">Gabungan pembelian sparepart & ongkos jasa</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1" onclick="clearCart()" title="Kosongkan Keranjang">
                        <i class="fa-solid fa-trash-can"></i> Reset
                    </button>
                </div>

                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <!-- Data Pelanggan -->
                        <div class="p-3 mb-3 rounded-3 bg-light border">
                            <div class="row g-2">
                                <div class="col-7">
                                    <label class="form-label small fw-semibold text-secondary mb-1">Nama Pelanggan</label>
                                    <input type="text" id="custName" class="form-control form-control-sm bg-white" placeholder="Pelanggan Umum" value="Pelanggan Umum">
                                </div>
                                <div class="col-5">
                                    <label class="form-label small fw-semibold text-secondary mb-1">No. Plat Motor</label>
                                    <input type="text" id="custPlate" class="form-control form-control-sm bg-white text-uppercase" placeholder="B 1234 ABC">
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Item Keranjang (Barang & Jasa) -->
                        <div class="cart-items-wrapper overflow-y-auto mb-3 pe-1" id="cartItemsList" style="max-height: 250px; min-height: 120px;">
                            <!-- Dynamic Cart Items via JavaScript -->
                        </div>
                    </div>

                    <!-- Ringkasan & Form Pembayaran -->
                    <div class="pt-2 border-top">
                        <!-- Total Pembayaran -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-secondary">TOTAL PEMBAYARAN</span>
                            <h3 class="fw-extrabold text-danger mb-0" id="cartTotalDisplay">Rp 0</h3>
                        </div>

                        <!-- PILIHAN METODE PEMBAYARAN: CASH, QRIS, BANK BRI -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark mb-2">METODE PEMBAYARAN <span class="text-danger">*</span></label>
                            <div class="d-grid gap-2 grid-payment-methods">
                                <!-- 1. Cash -->
                                <input type="radio" class="btn-check" name="payment_method" id="payMethodCash" value="Cash" checked onchange="switchPaymentMethod('Cash')">
                                <label class="btn btn-outline-success text-start d-flex align-items-center justify-content-between py-2 px-3 rounded-3" for="payMethodCash">
                                    <span><i class="fa-solid fa-money-bill-wave me-2"></i> Uang Tunai (Cash)</span>
                                    <i class="fa-solid fa-check check-indicator"></i>
                                </label>

                                <!-- 2. QRIS -->
                                <input type="radio" class="btn-check" name="payment_method" id="payMethodQris" value="QRIS" onchange="switchPaymentMethod('QRIS')">
                                <label class="btn btn-outline-info text-start d-flex align-items-center justify-content-between py-2 px-3 rounded-3" for="payMethodQris">
                                    <span><i class="fa-solid fa-qrcode me-2"></i> QRIS Tarida Motor</span>
                                    <i class="fa-solid fa-check check-indicator"></i>
                                </label>

                                <!-- 3. Transfer BRI -->
                                <input type="radio" class="btn-check" name="payment_method" id="payMethodBri" value="Transfer Bank BRI" onchange="switchPaymentMethod('Transfer Bank BRI')">
                                <label class="btn btn-outline-primary text-start d-flex align-items-center justify-content-between py-2 px-3 rounded-3" for="payMethodBri">
                                    <span><i class="fa-solid fa-building-columns me-2"></i> Transfer Bank BRI</span>
                                    <i class="fa-solid fa-check check-indicator"></i>
                                </label>
                            </div>
                        </div>

                        <!-- PANEL INPUT CASH -->
                        <div id="panelCashInput" class="p-3 mb-3 rounded-3 border bg-light">
                            <label class="form-label small fw-semibold text-secondary mb-1">Uang Diterima / Dibayar (Rp)</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-white fw-bold">Rp</span>
                                <input type="number" id="cashPaidInput" class="form-control fw-bold fs-6" placeholder="0" min="0" oninput="calculateChange()">
                            </div>

                            <!-- Tombol Cepat Nominal Uang -->
                            <div class="d-flex flex-wrap gap-1 mb-2">
                                <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2" style="font-size: 0.75rem;" onclick="setExactCash()">Uang Pas</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2" style="font-size: 0.75rem;" onclick="setCashAmount(50000)">50.000</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2" style="font-size: 0.75rem;" onclick="setCashAmount(100000)">100.000</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2" style="font-size: 0.75rem;" onclick="setCashAmount(200000)">200.000</button>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <span class="small fw-semibold text-secondary">Kembalian:</span>
                                <span class="fw-bold fs-6" id="cashChangeDisplay">Rp 0</span>
                            </div>
                        </div>

                        <!-- PANEL INFO QRIS -->
                        <div id="panelQrisInfo" class="p-3 mb-3 rounded-3 border bg-light d-none text-center">
                            <div class="badge bg-info text-white mb-2 px-3 py-1 rounded-pill">QRIS RESMI TARIDA MOTOR</div>
                            <div class="bg-white p-3 rounded-3 border d-inline-block shadow-sm mb-2">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=TARIDA_MOTOR_QRIS_PAYMENT" alt="QRIS Tarida Motor" style="width: 130px; height: 130px;" class="rounded">
                            </div>
                            <div class="small fw-bold text-dark">NMID: ID1020304050607</div>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">
                                Mendukung: GoPay, OVO, DANA, BCA Mobile, Livin', ShopeePay & E-Wallet Lainnya.
                            </small>
                        </div>

                        <!-- PANEL INFO BRI -->
                        <div id="panelBriInfo" class="p-3 mb-3 rounded-3 border bg-light d-none">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <strong class="text-primary"><i class="fa-solid fa-building-columns me-1"></i> BANK BRI</strong>
                                <span class="badge bg-primary rounded-pill">Transfer Bank</span>
                            </div>
                            <div class="bg-white p-2 rounded border mb-2">
                                <small class="text-muted d-block">Nomor Rekening:</small>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold font-monospace fs-6 text-dark" id="briAccountNo">0123-01-098765-53-1</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" onclick="copyBriAccount()">
                                        <i class="fa-regular fa-copy"></i> Salin
                                    </button>
                                </div>
                                <small class="text-secondary d-block mt-1">Atas Nama: <strong>TARIDA MOTOR WORKSHOP</strong></small>
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">
                                Pastikan bukti transfer telah terverifikasi oleh kasir sebelum menyelesaikan transaksi.
                            </small>
                        </div>

                        <!-- Tombol Bayar & Cetak Struk -->
                        <button type="button" id="btnSubmitPayment" class="btn btn-danger w-100 py-3 rounded-pill fw-bold fs-6 shadow d-flex align-items-center justify-content-center gap-2" onclick="submitTransaction()">
                            <i class="fa-solid fa-receipt"></i>
                            <span>PROSES BAYAR & CETAK STRUK</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ==================== MODAL CETAK STRUK HASIL TRANSAKSI ==================== -->
<div class="modal fade" id="modalStrukHasil" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white py-3 border-0 rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-circle-check me-2"></i> Transaksi Berhasil!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="text-muted mb-3">Transaksi gabungan barang & jasa telah berhasil tersimpan dan stok barang otomatis terpotong.</p>
                
                <div class="p-3 bg-light rounded-3 border mb-4 text-start font-monospace small" id="strukPreviewBox">
                    <!-- Preview ringkasan struk -->
                </div>

                <div class="d-grid gap-2">
                    <button type="button" id="btnPrintReceiptNow" class="btn btn-dark rounded-pill py-2 fw-bold d-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-print"></i> Cetak Struk (Thermal Printer)
                    </button>
                    <button type="button" class="btn btn-outline-secondary rounded-pill py-2" data-bs-dismiss="modal">
                        Selesai & Transaksi Baru
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .product-item-card {
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .product-item-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        border-color: #ef4444 !important;
    }
    .service-card:hover {
        border-color: #7e22ce !important;
        box-shadow: 0 8px 20px rgba(126, 34, 206, 0.15) !important;
    }
    .product-item-card.out-of-stock {
        opacity: 0.6;
        cursor: not-allowed;
        background: #f8fafc !important;
    }

    .text-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .grid-payment-methods .btn-check:checked + .btn {
        font-weight: 700;
        border-width: 2px;
    }
    .grid-payment-methods .check-indicator {
        display: none;
    }
    .grid-payment-methods .btn-check:checked + .btn .check-indicator {
        display: inline-block;
    }

    .cart-item-row {
        transition: background-color 0.2s;
    }
    .cart-item-row:hover {
        background-color: #f8fafc;
    }
</style>
@endpush

@push('scripts')
<script>
    // GLOBAL STATE POS
    let cart = [];
    let currentPaymentMethod = 'Cash';
    let currentTransactionId = null;
    let currentTypeFilter = 'all'; // all, product, service

    // ADD TO CART: PRODUK BARANG
    function addToCartProduct(product) {
        const itemKey = `product_${product.id}`;
        const existing = cart.find(item => item.key === itemKey);

        if (existing) {
            if (existing.qty + 1 > product.stok) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Terbatas',
                    text: `Maksimal stok untuk ${product.nama_barang} adalah ${product.stok} unit.`
                });
                return;
            }
            existing.qty += 1;
        } else {
            cart.push({
                key: itemKey,
                id: product.id,
                type: 'product',
                kode: product.kode_barang,
                nama: product.nama_barang,
                harga: parseFloat(product.harga_jual),
                stok: parseInt(product.stok),
                satuan: product.satuan || 'Pcs',
                qty: 1
            });
        }

        renderCart();
    }

    // ADD TO CART: LAYANAN JASA SERVIS
    function addToCartService(service) {
        const itemKey = `service_${service.id}`;
        const existing = cart.find(item => item.key === itemKey);

        if (existing) {
            existing.qty += 1;
        } else {
            cart.push({
                key: itemKey,
                id: service.id,
                type: 'service',
                kode: service.kode_jasa || `JS-${service.id}`,
                nama: service.nama_jasa,
                harga: parseFloat(service.harga_jasa),
                stok: 999999, // Jasa tidak dibatasi stok fisik
                satuan: 'Jasa',
                qty: 1
            });
        }

        renderCart();
    }

    function alertOutOfStock(nama) {
        Swal.fire({
            icon: 'error',
            title: 'Stok Habis',
            text: `Barang "${nama}" saat ini habis (0 unit)! Silakan restok terlebih dahulu.`
        });
    }

    // UPDATE QUANTITY
    function updateQty(key, delta) {
        const item = cart.find(i => i.key === key);
        if (!item) return;

        const newQty = item.qty + delta;
        if (newQty <= 0) {
            removeFromCart(key);
            return;
        }

        if (item.type === 'product' && newQty > item.stok) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Tidak Cukup',
                text: `Maksimal stok tersedia adalah ${item.stok} unit.`
            });
            return;
        }

        item.qty = newQty;
        renderCart();
    }

    // REMOVE FROM CART
    function removeFromCart(key) {
        cart = cart.filter(i => i.key !== key);
        renderCart();
    }

    // CLEAR CART
    function clearCart() {
        if (cart.length === 0) return;
        cart = [];
        renderCart();
    }

    // RENDER CART
    function renderCart() {
        const container = document.getElementById('cartItemsList');
        const totalDisplay = document.getElementById('cartTotalDisplay');

        if (cart.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="fa-solid fa-basket-shopping fs-3 mb-2 d-block text-secondary opacity-50"></i>
                    <small>Keranjang belanja masih kosong.<br>Pilih sparepart atau jasa di sebelah kiri.</small>
                </div>
            `;
            totalDisplay.innerText = 'Rp 0';
            calculateChange();
            return;
        }

        let total = 0;
        let html = '';

        cart.forEach(item => {
            const subtotal = item.harga * item.qty;
            total += subtotal;

            const isService = item.type === 'service';
            const badgeType = isService 
                ? '<span class="badge me-1" style="font-size:0.65rem; background-color:#ede9fe; color:#6d28d9;"><i class="fa-solid fa-wrench"></i> JASA</span>'
                : '<span class="badge me-1 bg-light text-secondary border" style="font-size:0.65rem;"><i class="fa-solid fa-box"></i> BARANG</span>';

            html += `
                <div class="cart-item-row p-2 mb-2 rounded border bg-white d-flex align-items-center justify-content-between">
                    <div style="max-width: 55%;">
                        <div class="d-flex align-items-center mb-1">
                            ${badgeType}
                            <strong class="d-inline-block text-truncate text-dark small" title="${item.nama}">${item.nama}</strong>
                        </div>
                        <span class="text-muted" style="font-size: 0.75rem;">Rp ${item.harga.toLocaleString('id-ID')} / ${item.satuan}</span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center border rounded">
                            <button type="button" class="btn btn-xs btn-light px-2 py-0" onclick="updateQty('${item.key}', -1)">-</button>
                            <span class="px-2 small fw-bold">${item.qty}</span>
                            <button type="button" class="btn btn-xs btn-light px-2 py-0" onclick="updateQty('${item.key}', 1)">+</button>
                        </div>
                        <span class="small fw-bold text-dark text-nowrap" style="min-width: 65px; text-align: right;">
                            Rp ${subtotal.toLocaleString('id-ID')}
                        </span>
                        <button type="button" class="btn btn-sm text-danger p-0 ms-1" onclick="removeFromCart('${item.key}')" title="Hapus">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        totalDisplay.innerText = `Rp ${total.toLocaleString('id-ID')}`;

        calculateChange();
    }

    function getTotalCartAmount() {
        return cart.reduce((acc, item) => acc + (item.harga * item.qty), 0);
    }

    // SWITCH METODE PEMBAYARAN (CASH, QRIS, BANK BRI)
    function switchPaymentMethod(method) {
        currentPaymentMethod = method;

        const panelCash = document.getElementById('panelCashInput');
        const panelQris = document.getElementById('panelQrisInfo');
        const panelBri = document.getElementById('panelBriInfo');

        panelCash.classList.add('d-none');
        panelQris.classList.add('d-none');
        panelBri.classList.add('d-none');

        const total = getTotalCartAmount();

        if (method === 'Cash') {
            panelCash.classList.remove('d-none');
            calculateChange();
        } else if (method === 'QRIS') {
            panelQris.classList.remove('d-none');
            document.getElementById('cashPaidInput').value = total;
            document.getElementById('cashChangeDisplay').innerText = 'Rp 0';
        } else if (method === 'Transfer Bank BRI') {
            panelBri.classList.remove('d-none');
            document.getElementById('cashPaidInput').value = total;
            document.getElementById('cashChangeDisplay').innerText = 'Rp 0';
        }
    }

    // CASH CALCULATION & QUICK BUTTONS
    function setExactCash() {
        const total = getTotalCartAmount();
        document.getElementById('cashPaidInput').value = total;
        calculateChange();
    }

    function setCashAmount(amt) {
        document.getElementById('cashPaidInput').value = amt;
        calculateChange();
    }

    function calculateChange() {
        if (currentPaymentMethod !== 'Cash') return;

        const total = getTotalCartAmount();
        const paid = parseFloat(document.getElementById('cashPaidInput').value) || 0;
        const changeDisplay = document.getElementById('cashChangeDisplay');

        if (paid >= total) {
            const change = paid - total;
            changeDisplay.className = 'fw-bold fs-6 text-success';
            changeDisplay.innerText = `Rp ${change.toLocaleString('id-ID')}`;
        } else {
            const shortage = total - paid;
            changeDisplay.className = 'fw-bold fs-6 text-danger';
            changeDisplay.innerText = `Kurang Rp ${shortage.toLocaleString('id-ID')}`;
        }
    }

    function copyBriAccount() {
        const text = '012301098765531';
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'No. Rekening BRI Disalin!',
                showConfirmButton: false,
                timer: 1500
            });
        });
    }

    // FILTER TIPE ITEM: SEMUA, PRODUK, JASA
    function filterItemType(type) {
        currentTypeFilter = type;
        filterPosProducts();
    }

    // SEARCH & FILTER PRODUK & JASA
    function filterPosProducts() {
        const query = document.getElementById('posSearchInput').value.toLowerCase().trim();
        const activeCategory = document.querySelector('.cat-filter-btn.active').getAttribute('data-category');
        const cards = document.querySelectorAll('.product-card-col');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            const code = card.getAttribute('data-code');
            const vehicle = card.getAttribute('data-vehicle');
            const category = card.getAttribute('data-category');
            const itemType = card.getAttribute('data-type');

            const matchesQuery = name.includes(query) || code.includes(query) || vehicle.includes(query);
            const matchesCat = (activeCategory === 'all' || category === activeCategory);
            const matchesType = (currentTypeFilter === 'all' || itemType === currentTypeFilter);

            if (matchesQuery && matchesCat && matchesType) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('noProductsFound').classList.toggle('d-none', visibleCount > 0);
        document.getElementById('productCountBadge').innerText = `${visibleCount} Item`;
    }

    function clearPosSearch() {
        document.getElementById('posSearchInput').value = '';
        filterPosProducts();
    }

    function filterByCategory(catId, btnElement) {
        document.querySelectorAll('.cat-filter-btn').forEach(btn => {
            btn.classList.remove('btn-dark', 'active');
            btn.classList.add('btn-outline-secondary');
        });

        btnElement.classList.remove('btn-outline-secondary');
        btnElement.classList.add('btn-dark', 'active');

        filterPosProducts();
    }

    // SUBMIT TRANSAKSI KE SERVER VIA AJAX
    function submitTransaction() {
        if (cart.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Keranjang Kosong',
                text: 'Silakan pilih setidaknya satu produk atau jasa untuk melakukan transaksi!'
            });
            return;
        }

        const total = getTotalCartAmount();
        let paid = parseFloat(document.getElementById('cashPaidInput').value) || 0;

        if (currentPaymentMethod === 'Cash' && paid < total) {
            Swal.fire({
                icon: 'warning',
                title: 'Pembayaran Kurang',
                text: `Uang pembayaran tunai kurang Rp ${(total - paid).toLocaleString('id-ID')}!`
            });
            return;
        }

        if (currentPaymentMethod !== 'Cash') {
            paid = total;
        }

        const payload = {
            metode_pembayaran: currentPaymentMethod,
            nama_pelanggan: document.getElementById('custName').value.trim() || 'Pelanggan Umum',
            nomor_polisi: document.getElementById('custPlate').value.trim() || '-',
            bayar: paid,
            items: cart.map(i => ({ 
                id: i.id, 
                type: i.type, 
                qty: i.qty 
            }))
        };

        const submitBtn = document.getElementById('btnSubmitPayment');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Memproses...';

        fetch("{{ route('kasir.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-receipt"></i> PROSES BAYAR & CETAK STRUK';

            if (status !== 200 || !body.success) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memproses Transaksi',
                    text: body.message || 'Terjadi kesalahan sistem.'
                });
                return;
            }

            // SUKSES
            currentTransactionId = body.transaction_id;

            // Render Preview Modal Struk
            const previewBox = document.getElementById('strukPreviewBox');
            previewBox.innerHTML = `
                <div class="text-center fw-bold border-bottom pb-1 mb-2">TARIDA MOTOR</div>
                <div class="d-flex justify-content-between"><span>No. Struk:</span><strong>${body.kode_transaksi}</strong></div>
                <div class="d-flex justify-content-between"><span>Metode:</span><span>${payload.metode_pembayaran}</span></div>
                <div class="d-flex justify-content-between"><span>Total:</span><strong>Rp ${total.toLocaleString('id-ID')}</strong></div>
                <div class="d-flex justify-content-between"><span>Bayar:</span><span>Rp ${paid.toLocaleString('id-ID')}</span></div>
                <div class="d-flex justify-content-between"><span>Kembali:</span><span>Rp ${(paid - total).toLocaleString('id-ID')}</span></div>
            `;

            // Setup tombol cetak langsung
            document.getElementById('btnPrintReceiptNow').onclick = function() {
                window.open(`/kasir/struk/${currentTransactionId}`, '_blank');
            };

            // Tampilkan Modal Struk
            new bootstrap.Modal(document.getElementById('modalStrukHasil')).show();

            // Reset Form Kasir
            cart = [];
            renderCart();
            document.getElementById('custPlate').value = '';
            document.getElementById('custName').value = 'Pelanggan Umum';
            document.getElementById('cashPaidInput').value = '';
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-receipt"></i> PROSES BAYAR & CETAK STRUK';
            Swal.fire({
                icon: 'error',
                title: 'Koneksi Error',
                text: error.message
            });
        });
    }

    // Inisialisasi
    document.addEventListener('DOMContentLoaded', () => {
        renderCart();
    });
</script>
@endpush