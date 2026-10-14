<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarida Motor - Sistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">🏍️ TARIDA MOTOR - SISTEM</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h3 class="mb-4">Daftar Inventori (Fokus Matic & Bebek)</h3>

        <div class="row">
            <!-- Tabel Barang -->
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header bg-dark text-white">Stok Barang / Sparepart</div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $p)
                                <tr>
                                    <td>{{ $p->nama_barang }}</td>
                                    <td>{{ $p->category->nama_kategori }}</td>
                                    <td>{{ $p->stok }}</td>
                                    <td>Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tabel Jasa & Pembayaran -->
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header bg-warning fw-bold">Daftar Jasa Servis</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($services as $s)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $s->nama_jasa }}
                                <span class="badge bg-primary rounded-pill">Rp {{ number_format($s->harga_jasa, 0, ',', '.') }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-header bg-success text-white">Metode Pembayaran</div>
                    <div class="card-body">
                        <ul>
                            <li>Cash (Uang Tunai)</li>
                            <li>Transfer Bank BRI</li>
                            <li>QRIS</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>