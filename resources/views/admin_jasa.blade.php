@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">

    <!-- HEADER SECTION -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 no-print">
        <div>
            <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-wrench text-danger me-2"></i> Layanan & Jasa Servis Bengkel</h4>
            <p class="text-muted mb-0">Kelola daftar jasa servis, ongkos pasang sparepart di tempat, dan tarif kerja teknisi Tarida Motor</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('jasa.print') }}" target="_blank" class="btn btn-outline-dark rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak Daftar Tarif Jasa
            </a>
            <button class="btn btn-danger rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahJasa">
                <i class="fa-solid fa-plus-circle"></i> Tambah Layanan Jasa
            </button>
        </div>
    </div>

    <!-- STATISTIK SINGKAT JASA -->
    <div class="row g-3 mb-4 no-print">
        <div class="col-sm-4">
            <div class="card card-custom p-3 bg-white">
                <small class="text-muted fw-semibold">Total Jenis Layanan Jasa</small>
                <h3 class="fw-bold text-dark mb-0">{{ $services->count() }} Layanan</h3>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card card-custom p-3 bg-white">
                <small class="text-muted fw-semibold">Tarif Jasa Termurah</small>
                <h3 class="fw-bold text-success mb-0">Rp {{ number_format($services->min('harga_jasa') ?: 0, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card card-custom p-3 bg-white">
                <small class="text-muted fw-semibold">Tarif Jasa Tertinggi</small>
                <h3 class="fw-bold text-danger mb-0">Rp {{ number_format($services->max('harga_jasa') ?: 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- FILTER & TABEL JASA -->
    <div class="card card-custom">
        <div class="card-header bg-white py-3 px-4 border-bottom no-print">
            <div class="row align-items-center g-3">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-0 text-dark">Daftar Ongkos & Jasa Servis</h5>
                    <small class="text-muted">Jasa ini langsung muncul di Kasir dan bisa digabung dengan pembelian sparepart</small>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" id="liveSearchJasaInput" class="form-control border-start-0 bg-light" placeholder="Cari nama jasa, kode, atau kategori pekerjaan..." onkeyup="filterTabelJasa()">
                        <button class="btn btn-outline-secondary" type="button" onclick="resetSearchJasa()">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if($services->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-screwdriver-wrench fs-2 mb-2 d-block"></i>
                    Belum ada data layanan jasa servis.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tabelJasa">
                        <thead class="table-light text-secondary" style="font-size: 0.82rem; text-transform: uppercase;">
                            <tr>
                                <th class="ps-4">Kode Jasa</th>
                                <th>Nama Layanan & Pasang</th>
                                <th>Kategori Pekerjaan</th>
                                <th>Keterangan / Deskripsi</th>
                                <th class="text-end">Tarif Biaya (Rp)</th>
                                <th class="text-center pe-4 no-print">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $s)
                            <tr class="jasa-row">
                                <td class="ps-4">
                                    <span class="badge bg-light text-primary border font-monospace px-2 py-1 fw-bold fs-6">
                                        {{ $s->kode_jasa ?: 'JS-'.$s->id }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-dark d-block jasa-nama">{{ $s->nama_jasa }}</strong>
                                    <span class="badge bg-purple-subtle text-purple border rounded-pill px-2 py-0" style="font-size: 0.72rem; background-color: #f3e8ff; color: #7e22ce;">
                                        <i class="fa-solid fa-screwdriver me-1"></i> Bisa Pasang di Tempat
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border px-2 py-1">
                                        {{ $s->kategori_jasa ?: 'Umum' }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $s->keterangan ?: '-' }}</small>
                                </td>
                                <td class="text-end fw-bold text-dark fs-6">
                                    Rp {{ number_format($s->harga_jasa, 0, ',', '.') }}
                                </td>
                                <td class="text-center pe-4 no-print">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-warning" 
                                                title="Edit Jasa" 
                                                onclick="editJasaModal({{ json_encode($s) }})">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form action="{{ route('jasa.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus layanan jasa {{ $s->nama_jasa }}?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Jasa">
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

<!-- MODAL TAMBAH JASA -->
<div class="modal fade" id="modalTambahJasa" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fa-solid fa-circle-plus text-danger me-2"></i> Tambah Layanan / Jasa Servis
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('jasa.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold small text-secondary">KODE JASA</label>
                            <input type="text" name="kode_jasa" class="form-control text-uppercase" placeholder="Contoh: JS-BAN-01" value="JS-{{ rand(100, 999) }}">
                            <small class="text-muted" style="font-size: 0.75rem;">Kode unik identifikasi jasa</small>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-semibold small text-secondary">NAMA LAYANAN / JASA PASANG <span class="text-danger">*</span></label>
                            <input type="text" name="nama_jasa" class="form-control" placeholder="Contoh: Jasa Pasang Ban Tubeless, Jasa Ganti Oli" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">KATEGORI PEKERJAAN</label>
                            <select name="kategori_jasa" class="form-select">
                                <option value="Perawatan Rutin">Perawatan Rutin (Ganti Oli / Filter)</option>
                                <option value="Ban & Roda">Ban & Roda (Pasang Ban / Tambal)</option>
                                <option value="Transmisi CVT">Transmisi CVT (Servis CVT / V-Belt)</option>
                                <option value="Pengereman">Pengereman (Ganti Kampas / Minyak Rem)</option>
                                <option value="Servis Mesin">Servis Mesin (Tune Up / Skir Klep)</option>
                                <option value="Kelistrikan">Kelistrikan (Pasang Aki / Lampu / Busi)</option>
                                <option value="Penggerak Roda">Penggerak Roda (Pasang Rantai & Gear)</option>
                                <option value="Umum">Umum / Pasang Baut & Aksesoris</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">BIAYA / ONGKOS JASA (RP) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_jasa" class="form-control" min="0" placeholder="Contoh: 20000" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-secondary">KETERANGAN / DESKRIPSI PENGERJAAN</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Detail pekerjaan teknisi / waktu pengerjaan"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Simpan Layanan Jasa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT JASA -->
<div class="modal fade" id="modalEditJasa" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fa-solid fa-pen-to-square text-warning me-2"></i> Edit Data Layanan Jasa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditJasa" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold small text-secondary">KODE JASA</label>
                            <input type="text" name="kode_jasa" id="editKodeJasa" class="form-control text-uppercase">
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-semibold small text-secondary">NAMA LAYANAN / JASA <span class="text-danger">*</span></label>
                            <input type="text" name="nama_jasa" id="editNamaJasa" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">KATEGORI PEKERJAAN</label>
                            <select name="kategori_jasa" id="editKategoriJasa" class="form-select">
                                <option value="Perawatan Rutin">Perawatan Rutin (Ganti Oli / Filter)</option>
                                <option value="Ban & Roda">Ban & Roda (Pasang Ban / Tambal)</option>
                                <option value="Transmisi CVT">Transmisi CVT (Servis CVT / V-Belt)</option>
                                <option value="Pengereman">Pengereman (Ganti Kampas / Minyak Rem)</option>
                                <option value="Servis Mesin">Servis Mesin (Tune Up / Skir Klep)</option>
                                <option value="Kelistrikan">Kelistrikan (Pasang Aki / Lampu / Busi)</option>
                                <option value="Penggerak Roda">Penggerak Roda (Pasang Rantai & Gear)</option>
                                <option value="Umum">Umum / Pasang Baut & Aksesoris</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">BIAYA / ONGKOS JASA (RP) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_jasa" id="editHargaJasa" class="form-control" min="0" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-secondary">KETERANGAN / DESKRIPSI</label>
                            <textarea name="keterangan" id="editKeteranganJasa" class="form-control" rows="2"></textarea>
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
    function filterTabelJasa() {
        const query = document.getElementById('liveSearchJasaInput').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.jasa-row');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    }

    function resetSearchJasa() {
        document.getElementById('liveSearchJasaInput').value = '';
        filterTabelJasa();
    }

    function editJasaModal(s) {
        document.getElementById('formEditJasa').action = `/jasa/${s.id}`;
        document.getElementById('editKodeJasa').value = s.kode_jasa || '';
        document.getElementById('editNamaJasa').value = s.nama_jasa;
        document.getElementById('editKategoriJasa').value = s.kategori_jasa || 'Umum';
        document.getElementById('editHargaJasa').value = s.harga_jasa;
        document.getElementById('editKeteranganJasa').value = s.keterangan || '';

        new bootstrap.Modal(document.getElementById('modalEditJasa')).show();
    }
</script>
@endpush
