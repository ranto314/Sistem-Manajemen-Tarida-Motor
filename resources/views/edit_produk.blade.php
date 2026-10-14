@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> Edit Data Barang</h4>
            <p class="text-muted mb-0">Ubah data sparepart / produk: <strong>{{ $product->nama_barang }}</strong></p>
        </div>
        <a href="{{ route('produk.byCategory', $product->category_id) }}" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Kategori
        </a>
    </div>

    <div class="card card-custom" style="max-width: 800px;">
        <div class="card-body p-4">
            <form action="{{ route('produk.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-secondary">KATEGORI PRODUK <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-secondary">KODE BARANG / SKU <span class="text-danger">*</span></label>
                        <input type="text" name="kode_barang" class="form-control text-uppercase" required value="{{ old('kode_barang', $product->kode_barang) }}">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold small text-secondary">NAMA BARANG <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" class="form-control" required value="{{ old('nama_barang', $product->nama_barang) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-secondary">SATUAN <span class="text-danger">*</span></label>
                        <select name="satuan" class="form-select" required>
                            @foreach(['Pcs', 'Botol', 'Tube', 'Set', 'Kaleng', 'Bungkus', 'Roll', 'Lembar', 'Cup'] as $unit)
                                <option value="{{ $unit }}" {{ $product->satuan == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-secondary">TIPE KENDARAAN</label>
                        <input type="text" name="tipe_kendaraan" class="form-control" value="{{ old('tipe_kendaraan', $product->tipe_kendaraan) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-secondary">TAHUN</label>
                        <input type="text" name="tahun" class="form-control" value="{{ old('tahun', $product->tahun) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-secondary">SISA STOK <span class="text-danger">*</span></label>
                        <input type="number" name="stok" class="form-control" min="0" required value="{{ old('stok', $product->stok) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-secondary">HARGA BELI / MODAL (RP)</label>
                        <input type="number" name="harga_beli" class="form-control" min="0" value="{{ old('harga_beli', $product->harga_beli) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-secondary">HARGA JUAL (RP) <span class="text-danger">*</span></label>
                        <input type="number" name="harga_jual" class="form-control" min="0" required value="{{ old('harga_jual', $product->harga_jual) }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold small text-secondary">KETERANGAN / SPESIFIKASI</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $product->keterangan) }}</textarea>
                    </div>

                    <div class="col-12 mt-4 pt-2 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">
                            <i class="fa-solid fa-save me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('produk.byCategory', $product->category_id) }}" class="btn btn-light rounded-pill px-4">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection