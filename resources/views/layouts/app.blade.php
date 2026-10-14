<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Tarida Motor - Sistem Manajemen Bengkel' }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #dc2626;
            --brand-primary: #dc2626;
            --brand-dark: #991b1b;
            --bg-canvas: #f8fafc;
            --card-border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-canvas);
            color: #1e293b;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);
            min-height: 100vh;
            color: #e2e8f0;
            transition: all 0.3s ease;
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            overflow-y: auto;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }

        .sidebar-brand {
            padding: 24px 20px;
            background: rgba(0, 0, 0, 0.25);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-brand .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: white;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.35);
        }

        .sidebar-menu {
            padding: 16px 12px;
        }

        .menu-header {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            font-weight: 700;
            padding: 12px 14px 6px;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.92rem;
            margin-bottom: 4px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-link-custom i {
            width: 24px;
            font-size: 1.05rem;
            margin-right: 12px;
            text-align: center;
        }

        .nav-link-custom:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }

        .nav-link-custom.active {
            color: #ffffff;
            background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%);
            box-shadow: 0 4px 14px rgba(220, 38, 38, 0.35);
            font-weight: 600;
        }

        .category-scroll-list {
            max-height: 280px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .category-scroll-list::-webkit-scrollbar {
            width: 4px;
        }
        .category-scroll-list::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        /* Main Content Wrapper */
        .main-wrapper {
            margin-left: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        /* Top Navbar */
        .top-navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--card-border);
            padding: 14px 28px;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        /* Content Body */
        .content-body {
            padding: 28px;
            flex: 1;
        }

        /* Card Enhancements */
        .card-custom {
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            background: #ffffff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-custom:hover {
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.06);
        }

        /* Stat Cards */
        .stat-card {
            border-radius: 16px;
            border: none;
            overflow: hidden;
            position: relative;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
        }

        .stat-card .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
        }

        /* Badges */
        .badge-payment-cash {
            background-color: #dcfce7;
            color: #15803d;
            font-weight: 600;
            border: 1px solid #bbf7d0;
        }
        .badge-payment-qris {
            background-color: #e0f2fe;
            color: #0369a1;
            font-weight: 600;
            border: 1px solid #bae6fd;
        }
        .badge-payment-bri {
            background-color: #ede9fe;
            color: #6d28d9;
            font-weight: 600;
            border: 1px solid #ddd6fe;
        }

        /* Footer */
        .footer-admin {
            padding: 16px 28px;
            background: #ffffff;
            border-top: 1px solid var(--card-border);
            font-size: 0.85rem;
            color: #64748b;
        }

        /* Print Media Styles */
        @media print {
            .no-print, .sidebar, .top-navbar, .footer-admin, .btn, .modal {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            .main-wrapper {
                margin-left: 0 !important;
                padding: 0 !important;
            }
            .content-body {
                padding: 0 !important;
            }
            .print-only {
                display: block !important;
            }
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- SIDEBAR UTAMA (NO PRINT) -->
    <aside class="sidebar no-print" id="appSidebar">
        <!-- Brand Header -->
        <div class="sidebar-brand d-flex align-items-center gap-3">
            <div class="logo-icon">
                <i class="fa-solid fa-motorcycle"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-white tracking-wide">TARIDA MOTOR</h5>
                <small class="text-white-50" style="font-size: 0.75rem;">Sistem Manajemen Bengkel</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <div class="sidebar-menu">
            <div class="menu-header">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('kasir.index') }}" class="nav-link-custom {{ request()->routeIs('kasir.*') ? 'active' : '' }}">
                <i class="fa-solid fa-cash-register"></i>
                <span>Kasir & Pembayaran</span>
                <span class="badge bg-danger ms-auto rounded-pill" style="font-size: 0.7rem;">POS</span>
            </a>
            <a href="{{ route('transaksi.index') }}" class="nav-link-custom {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt"></i>
                <span>Riwayat Transaksi</span>
            </a>

            <div class="menu-header mt-3">Inventori & Master Data</div>
            <a href="{{ route('kategori.index') }}" class="nav-link-custom {{ request()->routeIs('kategori.index') ? 'active' : '' }}">
                <i class="fa-solid fa-tags"></i>
                <span>Kelola Kategori</span>
            </a>
            <a href="{{ route('produk.index') }}" class="nav-link-custom {{ request()->routeIs('produk.index') ? 'active' : '' }}">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>Semua Barang</span>
            </a>
            <a href="{{ route('jasa.index') }}" class="nav-link-custom {{ request()->routeIs('jasa.*') ? 'active' : '' }}">
                <i class="fa-solid fa-wrench"></i>
                <span>Layanan & Jasa Servis</span>
                <span class="badge bg-warning text-dark ms-auto rounded-pill" style="font-size: 0.68rem;">Pasang</span>
            </a>

            <div class="menu-header mt-3">Kategori Produk Bengkel</div>
            <div class="category-scroll-list">
                @foreach(App\Models\Category::all() as $navCat)
                    <a href="{{ route('produk.byCategory', $navCat->id) }}" 
                       class="nav-link-custom {{ request()->is('produk/kategori/' . $navCat->id) ? 'active' : '' }}" 
                       style="font-size: 0.86rem; padding: 8px 12px;">
                        <i class="fa-solid {{ $navCat->icon ?: 'fa-box' }}"></i>
                        <span class="text-truncate">{{ $navCat->nama_kategori }}</span>
                    </a>
                @endforeach
            </div>

            <div class="menu-header mt-3">Pengaturan</div>
            <a href="{{ route('profil') }}" class="nav-link-custom {{ request()->routeIs('profil') ? 'active' : '' }}">
                <i class="fa-solid fa-user-gear"></i>
                <span>Profil Admin</span>
            </a>

            <div class="p-3 mt-3">
                <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                    @csrf
                    <button type="button" onclick="confirmLogout()" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 py-2 fw-semibold" style="border-radius: 10px;">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar no-print d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none border" id="toggleSidebarBtn">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Bengkel & Sparepart Tarida Motor</h5>
                    <small class="text-muted d-none d-sm-inline">
                        <i class="fa-regular fa-calendar-days me-1"></i> {{ Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('kasir.index') }}" class="btn btn-danger btn-sm px-3 py-2 fw-bold d-flex align-items-center gap-2 shadow-sm rounded-pill">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="d-none d-sm-inline">Buka Kasir</span>
                </a>

                <div class="dropdown">
                    <button class="btn btn-light border dropdown-toggle d-flex align-items-center gap-2 rounded-pill px-3 py-1" data-bs-toggle="dropdown">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.85rem;">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </div>
                        <span class="fw-semibold text-dark d-none d-md-inline" style="font-size: 0.9rem;">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2">
                        <li class="px-3 py-2 border-bottom">
                            <span class="d-block fw-bold text-dark">{{ auth()->user()->name }}</span>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('profil') }}">
                                <i class="fa-solid fa-user-circle me-2 text-primary"></i> Profil Saya
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('dashboard') }}">
                                <i class="fa-solid fa-gauge me-2 text-info"></i> Dashboard
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <button class="dropdown-item py-2 text-danger" onclick="confirmLogout()">
                                <i class="fa-solid fa-power-off me-2"></i> Logout
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Dynamic Main Content -->
        <main class="content-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4 rounded-3" role="alert">
                    <i class="fa-solid fa-circle-check fs-5 text-success"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4 rounded-3" role="alert">
                    <i class="fa-solid fa-triangle-exclamation fs-5 text-danger"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
                    <div class="fw-bold mb-1"><i class="fa-solid fa-circle-exclamation me-1"></i> Terdapat kesalahan pengisian data:</div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer-admin no-print d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <strong>TARIDA MOTOR</strong> &copy; {{ date('Y') }} - Sistem Manajemen Bengkel & Penjualan Sparepart.
            </div>
            <div class="text-muted">
                Versi 2.0 &bull; Database Laragon MySQL
            </div>
        </footer>
    </div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Toggle Sidebar on Mobile
        const toggleBtn = document.getElementById('toggleSidebarBtn');
        const sidebar = document.getElementById('appSidebar');
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }

        // Konfirmasi Logout
        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda yakin ingin keluar dari sistem Tarida Motor?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>