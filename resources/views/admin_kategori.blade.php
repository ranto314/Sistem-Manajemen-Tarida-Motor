@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">

    <!-- Header Section -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-tags text-danger me-2"></i> Kelola Kategori Produk Bengkel</h4>
            <p class="text-muted mb-0">Atur kategori sparepart, pelumas, dan perlengkapan servis di Bengkel Tarida Motor</p>
        </div>
        <button class="btn btn-danger rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
            <i class="fa-solid fa-plus-circle"></i> Tambah Kategori Baru
        </button>
    </div>

    <!-- Category Grid / Cards -->
    <div class="row g-3">
        @foreach($categories as $category)
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card card-custom h-100 p-3 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fa-solid {{ $category->icon ?: 'fa-box' }} fs-5"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border-0 rounded-circle" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical text-muted"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li>
                                    <button class="dropdown-item py-2" onclick="editKategori({{ $category->id }}, '{{ addslashes($category->nama_kategori) }}', '{{ addslashes($category->deskripsi ?? '') }}', '{{ $category->icon ?? 'fa-box' }}')">
                                        <i class="fa-solid fa-pen-to-square text-warning me-2"></i> Edit Kategori
                                    </button>
                                </li>
                                <li>
                                    <form action="{{ route('kategori.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $category->nama_kategori }} beserta seluruh barang di dalamnya?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="fa-solid fa-trash-can me-2"></i> Hapus Kategori
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark mb-1">{{ $category->nama_kategori }}</h5>
                    <p class="text-muted small mb-3" style="min-height: 38px;">
                        {{ $category->deskripsi ?: 'Tidak ada deskripsi' }}
                    </p>
                </div>

                <div class="pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-light text-dark border px-2 py-1">
                            <i class="fa-solid fa-box me-1"></i> {{ $category->products_count }} Jenis Barang
                        </span>
                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">
                            <i class="fa-solid fa-cubes me-1"></i> Total Stok: {{ $category->products_sum_stok ?: 0 }}
                        </span>
                    </div>

                    <a href="{{ route('produk.byCategory', $category->id) }}" class="btn btn-outline-danger w-100 rounded-pill btn-sm fw-semibold">
                        Lihat & Kelola Barang <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

<!-- MODAL TAMBAH KATEGORI -->
<div class="modal fade" id="modalTambahKategori" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-circle-plus text-danger me-2"></i> Tambah Kategori Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">NAMA KATEGORI <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Oli Mesin, Ban Luar, Baut & Mur" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">ICON (FontAwesome Class)</label>
                        <select name="icon" class="form-select">
                            <option value="fa-box">📦 fa-box (Standar)</option>
                            <option value="fa-oil-can">🛢️ fa-oil-can (Oli Mesin)</option>
                            <option value="fa-gears">⚙️ fa-gears (Oli Gardan / Transmisi)</option>
                            <option value="fa-spray-can-sparkles">✨ fa-spray-can-sparkles (Pelumas / Gemuk)</option>
                            <option value="fa-circle-notch">⭕ fa-circle-notch (Ban Luar & Dalam)</option>
                            <option value="fa-cog">⚙️ fa-cog (Sparepart CVT)</option>
                            <option value="fa-screwdriver-wrench">🔧 fa-screwdriver-wrench (Baut & Mur)</option>
                            <option value="fa-paint-roller">🎨 fa-paint-roller (Pewarna / Pilox)</option>
                            <option value="fa-toolbox">🧰 fa-toolbox (Umum Bengkel)</option>
                            <option value="fa-shield-halved">🛡️ fa-shield-halved (Kampas Rem)</option>
                            <option value="fa-bolt">⚡ fa-bolt (Aki & Kelistrikan)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">DESKRIPSI KATEGORI</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Keterangan singkat tentang jenis produk pada kategori ini"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT KATEGORI -->
<div class="modal fade" id="modalEditKategori" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> Edit Data Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditKategori" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">NAMA KATEGORI <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" id="editNamaKategori" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">ICON (FontAwesome Class)</label>
                        <select name="icon" id="editIconKategori" class="form-select">
                            <option value="fa-box">📦 fa-box (Standar)</option>
                            <option value="fa-oil-can">🛢️ fa-oil-can (Oli Mesin)</option>
                            <option value="fa-gears">⚙️ fa-gears (Oli Gardan / Transmisi)</option>
                            <option value="fa-spray-can-sparkles">✨ fa-spray-can-sparkles (Pelumas / Gemuk)</option>
                            <option value="fa-circle-notch">⭕ fa-circle-notch (Ban Luar & Dalam)</option>
                            <option value="fa-cog">⚙️ fa-cog (Sparepart CVT)</option>
                            <option value="fa-screwdriver-wrench">🔧 fa-screwdriver-wrench (Baut & Mur)</option>
                            <option value="fa-paint-roller">🎨 fa-paint-roller (Pewarna / Pilox)</option>
                            <option value="fa-toolbox">🧰 fa-toolbox (Umum Bengkel)</option>
                            <option value="fa-shield-halved">🛡️ fa-shield-halved (Kampas Rem)</option>
                            <option value="fa-bolt">⚡ fa-bolt (Aki & Kelistrikan)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">DESKRIPSI KATEGORI</label>
                        <textarea name="deskripsi" id="editDeskripsiKategori" class="form-control" rows="3"></textarea>
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
    function editKategori(id, nama, deskripsi, icon) {
        document.getElementById('formEditKategori').action = `/kategori/${id}`;
        document.getElementById('editNamaKategori').value = nama;
        document.getElementById('editDeskripsiKategori').value = deskripsi;
        document.getElementById('editIconKategori').value = icon;
        new bootstrap.Modal(document.getElementById('modalEditKategori')).show();
    }
</script>
@endpush
