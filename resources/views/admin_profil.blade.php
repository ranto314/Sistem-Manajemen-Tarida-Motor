@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">

    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-user-gear text-danger me-2"></i> Profil Administrator Bengkel</h4>
        <p class="text-muted mb-0">Kelola informasi akun login dan keamanan sistem Tarida Motor</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card card-custom p-4 text-center">
                <div class="bg-danger text-white rounded-circle mx-auto d-flex align-items-center justify-content-center fw-bold fs-2 mb-3 shadow" style="width: 80px; height: 80px;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ auth()->user()->name }}</h5>
                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-1 mb-3">Administrator Bengkel</span>
                <p class="text-muted small mb-0">{{ auth()->user()->email }}</p>
                <hr>
                <div class="text-start small text-muted">
                    <div class="mb-2"><i class="fa-solid fa-shield-halved me-2 text-success"></i> Status: <strong>Aktif</strong></div>
                    <div><i class="fa-solid fa-clock me-2 text-primary"></i> Terdaftar sejak: <strong>{{ auth()->user()->created_at->format('d/m/Y') }}</strong></div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-custom">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark">Perbarui Data Akun & Password</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profil.update') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">NAMA LENGKAP <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">EMAIL LOGIN <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                        </div>

                        <div class="alert alert-light border small text-muted my-3">
                            <i class="fa-solid fa-circle-info text-primary me-1"></i> Kosongkan password jika tidak ingin mengubah password akun Anda.
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">PASSWORD BARU</label>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">KONFIRMASI PASSWORD BARU</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan Profil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection