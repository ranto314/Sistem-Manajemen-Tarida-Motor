@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">

    <!-- BREADCRUMB & HEADER KATEGORI -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 no-print">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}" class="text-decoration-none">Kategori</a></li>
                    <li class="breadcrumb-item active text-danger fw-bold">{{ $category->nama_kategori }}</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-3">
                <div class="bg-danger text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="fa-solid {{ $category->icon ?: 'fa-box' }} fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-0">Kategori: {{ $category->nama_kategori }}</h4>
                    <small class="text-muted">{{ $category->deskripsi ?: 'Daftar inventori sparepart & barang bengkel' }}</small>
                </div>
            </div>
        </div>

        <!-- ACTION BUTTONS: TAMBAH PRODUK & TOMBOL CETAK -->
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('produk.print', $category->id) }}" target="_blank" class="btn btn-outline-dark rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak Laporan Kategori
            </a>
            <button class="btn btn-danger rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahProduk">
                <i class="fa-solid fa-plus-circle"></i> Tambah Barang Baru
            </button>
        </div>
    </div>

    <!-- STATISTIK SINGKAT KATEGORI -->
    <div class="row g-3 mb-4 no-print">
        <div class="col-sm-4">
            <div class="card card-custom p-3 bg-white">
                <small class="text-muted fw-semibold">Jumlah Jenis Barang</small>
                <h3 class="fw-bold text-dark mb-0">{{ $products->count() }} Jenis</h3>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card card-custom p-3 bg-white">
                <small class="text-muted fw-semibold">Total Unit Stok</small>
                <h3 class="fw-bold text-success mb-0">{{ $products->sum('stok') }} Unit</h3>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card card-custom p-3 bg-white">
                <small class="text-muted fw-semibold">Stok Menipis (&lt; 5)</small>
                <h3 class="fw-bold text-danger mb-0">{{ $products->where('stok', '<', 5)->count() }} Item</h3>
            </div>
        </div>
    </div>

    <!-- CARD TABEL & FILTER PENCARIAN -->
    <div class="card card-custom">
        <div class="card-header bg-white py-3 px-4 border-bottom no-print">
            <div class="row align-items-center g-3">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-0 text-dark">Daftar Barang: {{ $category->nama_kategori }}</h5>
                    <small class="text-muted">Total {{ $products->count() }} item tersedia dalam kategori ini</small>
                </div>
                <!-- KOTAK CARI / SEARCH REAL-TIME -->
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" id="liveSearchInput" class="form-control border-start-0 bg-light" placeholder="Cari nama barang, kode SKU, atau tipe motor..." onkeyup="filterTabelBarang()">
                        <button class="btn btn-outline-secondary" type="button" onclick="resetSearch()">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- PRINT HEADER (Hanya muncul saat dicetak) -->
        <div class="d-none print-only p-4 text-center border-bottom mb-3">
            <h3 class="fw-bold mb-0">TARIDA MOTOR</h3>
            <p class="mb-0">Jl. Raya Duta Pelni No.50, Tugu, Kec. Cimanggis, Kota Depok, Jawa Barat 16951 &bull; Telp: 081214287931</p>
            <h5 class="mt-3 fw-bold text-decoration-underline">LAPORAN STOK BARANG: KATEGORI {{ strtoupper($category->nama_kategori) }}</h5>
            <small>Dicetak pada: {{ Carbon\Carbon::now()->format('d/m/Y H:i') }} | Petugas: {{ auth()->user()->name }}</small>
        </div>

        <div class="card-body p-0">
            @if($products->isEmpty())
                <div class="text-center py-5">
                    <div class="text-muted mb-3">
                        <i class="fa-solid fa-box-open fs-1 text-secondary"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Belum ada barang di kategori {{ $category->nama_kategori }}</h5>
                    <p class="text-muted">Mulai tambahkan produk sparepart atau perlengkapan pada tombol di bawah ini.</p>
                    <button class="btn btn-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambahProduk">
                        + Tambah Barang Sekarang
                    </button>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tabelBarang">
                        <thead class="table-light text-secondary" style="font-size: 0.82rem; text-transform: uppercase;">
                            <tr>
                                <th class="ps-4">Kode SKU</th>
                                <th>Nama Barang</th>
                                <th>Untuk Kendaraan</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Sisa Stok</th>
                                <th class="text-end">Harga Beli</th>
                                <th class="text-end">Harga Jual</th>
                                <th class="text-center pe-4 no-print">Aksi (CRED)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $p)
                            <tr class="barang-row">
                                <td class="ps-4">
                                    <span class="badge bg-light text-primary border font-monospace px-2 py-1 fw-bold fs-6">
                                        {{ $p->kode_barang }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-dark d-block item-nama">{{ $p->nama_barang }}</strong>
                                    @if($p->keterangan)
                                        <small class="text-muted">{{ $p->keterangan }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="item-tipe text-secondary">{{ $p->tipe_kendaraan ?: 'Universal' }}</span>
                                    @if($p->tahun)
                                        <small class="d-block text-muted">Th: {{ $p->tahun }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ $p->satuan ?: 'Pcs' }}</span>
                                </td>
                                <td class="text-center">
                                    @if($p->stok <= 0)
                                        <span class="badge bg-danger px-3 py-1 rounded-pill">Habis (0)</span>
                                    @elseif($p->stok < 5)
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-1 rounded-pill fw-bold">
                                            {{ $p->stok }} (Menipis)
                                        </span>
                                    @elseif($p->stok < 15)
                                        <span class="badge bg-warning bg-opacity-20 text-dark border px-3 py-1 rounded-pill fw-semibold">
                                            {{ $p->stok }}
                                        </span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-1 rounded-pill fw-bold">
                                            {{ $p->stok }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end text-muted">
                                    Rp {{ number_format($p->harga_beli, 0, ',', '.') }}
                                </td>
                                <td class="text-end fw-bold text-dark">
                                    Rp {{ number_format($p->harga_jual, 0, ',', '.') }}
                                </td>
                                <td class="text-center pe-4 no-print">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-warning" 
                                                title="Edit Barang" 
                                                onclick="editProdukModal({{ json_encode($p) }})">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form action="{{ route('produk.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang: {{ $p->nama_barang }}?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Barang">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
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

<!-- MODAL TAMBAH PRODUK KHUSUS KATEGORI INI -->
<div class="modal fade" id="modalTambahProduk" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fa-solid fa-circle-plus text-danger me-2"></i> Tambah Barang: {{ $category->nama_kategori }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('produk.store') }}" method="POST">
                @csrf
                <input type="hidden" name="category_id" value="{{ $category->id }}">

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold small text-secondary">KODE BARANG / SKU <span class="text-danger">*</span></label>
                            <input type="text" name="kode_barang" class="form-control text-uppercase" placeholder="Contoh: OLI-MPX-01" required value="{{ strtoupper(substr($category->nama_kategori, 0, 3)) }}-{{ rand(100, 999) }}">
                            <small class="text-muted" style="font-size: 0.75rem;">Kode SKU unik untuk identifikasi produk</small>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-semibold small text-secondary">NAMA BARANG <span class="text-danger">*</span></label>
                            <input type="text" name="nama_barang" class="form-control" placeholder="Nama lengkap sparepart / barang" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">UNTUK KENDARAAN / TIPE MOTOR</label>
                            <input type="text" name="tipe_kendaraan" class="form-control" placeholder="Contoh: Honda Beat, Vario 125, NMAX, Universal">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-secondary">TAHUN</label>
                            <input type="text" name="tahun" class="form-control" placeholder="Semua / 2018-2023">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-secondary">SATUAN <span class="text-danger">*</span></label>
                            <select name="satuan" class="form-select" required>
                                <option value="Pcs">Pcs</option>
                                <option value="Botol">Botol</option>
                                <option value="Tube">Tube</option>
                                <option value="Set">Set</option>
                                <option value="Kaleng">Kaleng</option>
                                <option value="Bungkus">Bungkus</option>
                                <option value="Roll">Roll</option>
                                <option value="Lembar">Lembar</option>
                                <option value="Cup">Cup</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">STOK AWAL <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control" min="0" placeholder="0" required value="10">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">HARGA BELI / MODAL (RP)</label>
                            <input type="number" name="harga_beli" class="form-control" min="0" placeholder="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">HARGA JUAL KONSUMEN (RP) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_jual" class="form-control" min="0" placeholder="0" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-secondary">KETERANGAN / SPESIFIKASI</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan seperti merk, kekentalan, ukuran baut, dll."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT PRODUK -->
<div class="modal fade" id="modalEditProduk" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fa-solid fa-pen-to-square text-warning me-2"></i> Edit Data Barang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditProduk" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="category_id" id="editCategoryId">

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold small text-secondary">KODE BARANG / SKU <span class="text-danger">*</span></label>
                            <input type="text" name="kode_barang" id="editKodeBarang" class="form-control text-uppercase" required>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-semibold small text-secondary">NAMA BARANG <span class="text-danger">*</span></label>
                            <input type="text" name="nama_barang" id="editNamaBarang" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">UNTUK KENDARAAN</label>
                            <input type="text" name="tipe_kendaraan" id="editTipeKendaraan" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-secondary">TAHUN</label>
                            <input type="text" name="tahun" id="editTahun" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-secondary">SATUAN <span class="text-danger">*</span></label>
                            <select name="satuan" id="editSatuan" class="form-select" required>
                                <option value="Pcs">Pcs</option>
                                <option value="Botol">Botol</option>
                                <option value="Tube">Tube</option>
                                <option value="Set">Set</option>
                                <option value="Kaleng">Kaleng</option>
                                <option value="Bungkus">Bungkus</option>
                                <option value="Roll">Roll</option>
                                <option value="Lembar">Lembar</option>
                                <option value="Cup">Cup</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">SISA STOK <span class="text-danger">*</span></label>
                            <input type="number" name="stok" id="editStok" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">HARGA BELI (RP)</label>
                            <input type="number" name="harga_beli" id="editHargaBeli" class="form-control" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">HARGA JUAL (RP) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_jual" id="editHargaJual" class="form-control" min="0" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-secondary">KETERANGAN / SPESIFIKASI</label>
                            <textarea name="keterangan" id="editKeterangan" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // FITUR CARI LIVE / REAL-TIME FILTER
    function filterTabelBarang() {
        const query = document.getElementById('liveSearchInput').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.barang-row');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function resetSearch() {
        document.getElementById('liveSearchInput').value = '';
        filterTabelBarang();
    }

    // MODAL EDIT POPULATION
    function editProdukModal(p) {
        document.getElementById('formEditProduk').action = `/produk/${p.id}`;
        document.getElementById('editCategoryId').value = p.category_id;
        document.getElementById('editKodeBarang').value = p.kode_barang;
        document.getElementById('editNamaBarang').value = p.nama_barang;
        document.getElementById('editTipeKendaraan').value = p.tipe_kendaraan || '';
        document.getElementById('editTahun').value = p.tahun || '';
        document.getElementById('editSatuan').value = p.satuan || 'Pcs';
        document.getElementById('editStok').value = p.stok;
        document.getElementById('editHargaBeli').value = p.harga_beli || 0;
        document.getElementById('editHargaJual').value = p.harga_jual;
        document.getElementById('editKeterangan').value = p.keterangan || '';

        new bootstrap.Modal(document.getElementById('modalEditProduk')).show();
    }
</script>
@endpush