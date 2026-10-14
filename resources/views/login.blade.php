<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Bengkel Tarida Motor</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #090d16 0%, #1e1b4b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Decorative glowing circles */
        .glow-circle-1 {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(220, 38, 38, 0.25) 0%, rgba(220, 38, 38, 0) 70%);
            top: -100px;
            right: -100px;
            border-radius: 50%;
            pointer-events: none;
        }

        .glow-circle-2 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.2) 0%, rgba(59, 130, 246, 0) 70%);
            bottom: -150px;
            left: -150px;
            border-radius: 50%;
            pointer-events: none;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            width: 100%;
            max-width: 460px;
            transition: transform 0.3s ease;
        }

        .brand-badge {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 10px 20px -5px rgba(220, 38, 38, 0.5);
            margin-bottom: 16px;
        }

        .form-control:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.15);
        }

        .input-group-text {
            background-color: #f8fafc;
            border-color: #dee2e6;
            color: #64748b;
        }

        .btn-login {
            background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%);
            border: none;
            color: white;
            font-weight: 700;
            padding: 13px;
            border-radius: 12px;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.35);
            transition: all 0.25s ease;
        }

        .btn-login:hover {
            background: linear-gradient(90deg, #b91c1c 0%, #dc2626 100%);
            box-shadow: 0 12px 25px rgba(220, 38, 38, 0.45);
            transform: translateY(-2px);
            color: white;
        }

        .demo-credential-box {
            background: #f1f5f9;
            border: 1px dashed #cbd5e1;
            border-radius: 14px;
            padding: 12px 16px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

    <div class="glow-circle-1"></div>
    <div class="glow-circle-2"></div>

    <div class="login-card p-4 p-sm-5">
        <div class="text-center mb-4">
            <div class="brand-badge">
                <i class="fa-solid fa-motorcycle"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">TARIDA MOTOR</h3>
            <p class="text-muted small mb-0">Sistem Manajemen Bengkel & Penjualan Sparepart</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="fa-solid fa-circle-exclamation text-danger fs-5"></i>
                <div class="small">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="fa-solid fa-circle-check text-success fs-5"></i>
                <div class="small">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf

            <!-- Email Input -->
            <div class="mb-3">
                <label class="form-label fw-semibold text-secondary small">EMAIL ADMINISTRATOR</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" name="email" id="emailInput" class="form-control py-2" placeholder="admin@tarida.com" required value="{{ old('email', 'admin@tarida.com') }}">
                </div>
            </div>

            <!-- Password Input -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="form-label fw-semibold text-secondary small">PASSWORD</label>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" id="passwordInput" class="form-control py-2" placeholder="Masukkan password" required value="admin123">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility()">
                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" checked>
                    <label class="form-check-label small text-muted" for="rememberMe">
                        Ingat Saya
                    </label>
                </div>
                <small class="text-muted"><i class="fa-solid fa-shield-halved text-success me-1"></i>Akses Terproteksi</small>
            </div>

            <!-- Tombol Masuk -->
            <button type="submit" class="btn btn-login w-100 mb-4">
                <i class="fa-solid fa-right-to-bracket me-2"></i> MASUK KE SISTEM
            </button>

            <!-- Kredensial Demo Cepat -->
            <div class="demo-credential-box">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong class="text-dark"><i class="fa-solid fa-key text-warning me-1"></i> Akun Login Default:</strong>
                    <button type="button" class="btn btn-sm btn-outline-dark py-0 px-2" style="font-size: 0.75rem;" onclick="fillCredentials()">
                        Gunakan Akun Ini
                    </button>
                </div>
                <div class="text-muted" style="font-size: 0.8rem;">
                    Email: <span class="fw-bold text-dark">admin@tarida.com</span> &bull; Sandi: <span class="fw-bold text-dark">admin123</span>
                </div>
            </div>
        </form>

        <div class="text-center mt-4 text-muted small">
            &copy; {{ date('Y') }} Tarida Motor. All rights reserved.
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        function fillCredentials() {
            document.getElementById('emailInput').value = 'admin@tarida.com';
            document.getElementById('passwordInput').value = 'admin123';
        }
    </script>
</body>
</html>