@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">

    <!-- HEADER & ACTION -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 no-print">
        <div>
            <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-boxes-stacked text-danger me-2"></i> Master Data Semua Barang Bengkel</h4>
            <p class="text-muted mb-0">Manajemen lengkap seluruh inventori produk dan suku cadang Tarida Motor</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('produk.print') }}" target="_blank" class="btn btn-outline-dark rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak Seluruh Inventori
            </a>
            <button class="btn btn-danger rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahProdukSemua">
                <i class="fa-solid fa-plus-circle"></i> Tambah Barang Baru
            </button>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="card card-custom mb-4 no-print">
        <div class="card-body p-3">
            <form action="{{ route('produk.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="cari" class="form-control border-start-0 bg-light" placeholder="Cari nama, kode SKU, motor..." value="{{ request('cari') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="kategori" class="form-select bg-light" onchange="this.form.submit()">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama_kategori }} ({{ $cat->products_count }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="filter_stok" class="form-select bg-light" onchange="this.form.submit()">
                        <option value="">-- Semua Status Stok --</option>
                        <option value="menipis" {{ request('filter_stok') == 'menipis' ? 'selected' : '' }}>⚠️ Stok Menipis (&lt; 5)</option>
                        <option value="habis" {{ request('filter_stok') == 'habis' ? 'selected' : '' }}>❌ Stok Habis (0)</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-100 rounded-pill fw-semibold">Filter</button>
                    <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary rounded-pill" title="Reset Filter"><i class="fa-solid fa-rotate-right"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- DATA TABLE -->
    <div class="card card-custom">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark">Data Inventori ({{ $products->count() }} Barang Ditemukan)</h5>
        </div>
        <div class="card-body p-0">
            @if($products->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-inbox fs-2 mb-2 d-block"></i>
                    Tidak ada barang yang cocok dengan filter pencarian.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary" style="font-size: 0.82rem; text-transform: uppercase;">
                            <tr>
                                <th class="ps-4">Kode SKU</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Motor / Tipe</th>
                                <th class="text-center">Sisa Stok</th>
                                <th class="text-end">Harga Beli</th>
                                <th class="text-end">Harga Jual</th>
                                <th class="text-center pe-4 no-print">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $p)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light text-primary border font-monospace px-2 py-1 fw-bold fs-6">
                                        {{ $p->kode_barang }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-dark d-block">{{ $p->nama_barang }}</strong>
                                    <small class="text-muted">{{ $p->satuan }} &bull; {{ $p->keterangan }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('produk.byCategory', $p->category_id) }}" class="badge bg-light text-dark border text-decoration-none px-2 py-1">
                                        <i class="fa-solid {{ $p->category->icon ?? 'fa-box' }} me-1 text-danger"></i>
                                        {{ $p->category->nama_kategori ?? 'Umum' }}
                                    </a>
                                </td>
                                <td>
                                    <span class="text-secondary">{{ $p->tipe_kendaraan ?: 'Universal' }}</span>
                                    @if($p->tahun)
                                        <small class="d-block text-muted">({{ $p->tahun }})</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($p->stok <= 0)
                                        <span class="badge bg-danger px-3 py-1 rounded-pill">Habis (0)</span>
                                    @elseif($p->stok < 5)
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-1 rounded-pill fw-bold">
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
                                                onclick="editProdukSemuaModal({{ json_encode($p) }})">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form action="{{ route('produk.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus barang {{ $p->nama_barang }}?');" class="d-inline">
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

<!-- MODAL TAMBAH PRODUK SEMUA -->
<div class="modal fade" id="modalTambahProdukSemua" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fa-solid fa-circle-plus text-danger me-2"></i> Tambah Master Barang Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('produk.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">KATEGORI PRODUK <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">KODE BARANG / SKU <span class="text-danger">*</span></label>
                            <input type="text" name="kode_barang" class="form-control text-uppercase" placeholder="Contoh: BAN-FDR-01" required value="TRD-{{ rand(1000, 9999) }}">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold small text-secondary">NAMA LENGKAP BARANG <span class="text-danger">*</span></label>
                            <input type="text" name="nama_barang" class="form-control" placeholder="Nama sparepart / oli / perlengkapan" required>
                        </div>
                        <div class="col-md-4">
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

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">UNTUK KENDARAAN</label>
                            <input type="text" name="tipe_kendaraan" class="form-control" placeholder="Beat, Vario, NMAX, Universal">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">TAHUN</label>
                            <input type="text" name="tahun" class="form-control" placeholder="Semua / 2020-2024">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">STOK <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control" min="0" required value="10">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">HARGA BELI / MODAL (RP)</label>
                            <input type="number" name="harga_beli" class="form-control" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">HARGA JUAL KONSUMEN (RP) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_jual" class="form-control" min="0" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-secondary">KETERANGAN / SPESIFIKASI</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan spesifikasi"></textarea>
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

<!-- MODAL EDIT PRODUK SEMUA -->
<div class="modal fade" id="modalEditProdukSemua" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fa-solid fa-pen-to-square text-warning me-2"></i> Edit Data Barang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditProdukSemua" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">KATEGORI <span class="text-danger">*</span></label>
                            <select name="category_id" id="editSemuaCategoryId" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">KODE BARANG / SKU <span class="text-danger">*</span></label>
                            <input type="text" name="kode_barang" id="editSemuaKodeBarang" class="form-control text-uppercase" required>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold small text-secondary">NAMA BARANG <span class="text-danger">*</span></label>
                            <input type="text" name="nama_barang" id="editSemuaNamaBarang" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">SATUAN <span class="text-danger">*</span></label>
                            <select name="satuan" id="editSemuaSatuan" class="form-select" required>
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

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">UNTUK KENDARAAN</label>
                            <input type="text" name="tipe_kendaraan" id="editSemuaTipeKendaraan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">TAHUN</label>
                            <input type="text" name="tahun" id="editSemuaTahun" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">SISA STOK <span class="text-danger">*</span></label>
                            <input type="number" name="stok" id="editSemuaStok" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">HARGA BELI (RP)</label>
                            <input type="number" name="harga_beli" id="editSemuaHargaBeli" class="form-control" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">HARGA JUAL (RP) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_jual" id="editSemuaHargaJual" class="form-control" min="0" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-secondary">KETERANGAN</label>
                            <textarea name="keterangan" id="editSemuaKeterangan" class="form-control" rows="2"></textarea>
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
    function editProdukSemuaModal(p) {
        document.getElementById('formEditProdukSemua').action = `/produk/${p.id}`;
        document.getElementById('editSemuaCategoryId').value = p.category_id;
        document.getElementById('editSemuaKodeBarang').value = p.kode_barang;
        document.getElementById('editSemuaNamaBarang').value = p.nama_barang;
        document.getElementById('editSemuaTipeKendaraan').value = p.tipe_kendaraan || '';
        document.getElementById('editSemuaTahun').value = p.tahun || '';
        document.getElementById('editSemuaSatuan').value = p.satuan || 'Pcs';
        document.getElementById('editSemuaStok').value = p.stok;
        document.getElementById('editSemuaHargaBeli').value = p.harga_beli || 0;
        document.getElementById('editSemuaHargaJual').value = p.harga_jual;
        document.getElementById('editSemuaKeterangan').value = p.keterangan || '';

        new bootstrap.Modal(document.getElementById('modalEditProdukSemua')).show();
    }
</script>
@endpush