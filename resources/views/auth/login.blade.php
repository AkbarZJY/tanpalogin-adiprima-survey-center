<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Adiprima Survey Center</title>
    <!-- Favicon (Icon Only without text) -->
    <link rel="icon" type="image/svg" href="{{ asset('images/adiprima-icon.svg') }}">
    <link rel="alternate icon" type="image/svg" href="{{ asset('images/adiprima-icon.svg') }}">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --color-navy: #0C2B64;
            --color-navy-dark: #061B40;
            --color-blue-accent: #1E40AF;
            --font-family: 'Plus Jakarta Sans', sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-family);
            background-color: #F8FAFC;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .login-card {
            width: 100%;
            max-width: 1050px;
            background: #FFFFFF;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(12, 43, 100, 0.15);
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
        }

        /* Left Hero Banner */
        .login-banner {
            background: linear-gradient(135deg, var(--color-navy), #16469D);
            color: #FFFFFF;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .login-banner::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            pointer-events: none;
        }

        .banner-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .banner-brand-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #FFFFFF;
        }

        .banner-brand-text h2 {
            font-size: 1.3rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .banner-brand-text p {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .banner-headline {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .banner-headline h1 {
            font-size: 2.1rem;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.03em;
            margin-bottom: 0.75rem;
        }

        .banner-headline p {
            font-size: 0.95rem;
            opacity: 0.85;
            line-height: 1.5;
        }

        /* Mockup Visual Widget */
        .mockup-preview-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .mockup-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .mockup-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
        }

        .stat-box {
            background: rgba(255, 255, 255, 0.15);
            padding: 0.75rem;
            border-radius: 10px;
            text-align: center;
        }

        .stat-box small {
            display: block;
            font-size: 0.65rem;
            opacity: 0.8;
            margin-bottom: 0.25rem;
        }

        .stat-box strong {
            font-size: 1.1rem;
            font-weight: 800;
        }

        /* Right Form Panel */
        .login-form-container {
            padding: 3.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-header h2 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 0.35rem;
            letter-spacing: -0.02em;
        }

        .form-header p {
            font-size: 0.9rem;
            color: #64748B;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            color: #94A3B8;
            font-size: 1.1rem;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.8rem;
            font-size: 0.95rem;
            font-family: inherit;
            border: 1.5px solid #E2E8F0;
            border-radius: 12px;
            background-color: #F8FAFC;
            color: #0F172A;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-navy);
            background-color: #FFFFFF;
            box-shadow: 0 0 0 4px rgba(12, 43, 100, 0.08);
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
            font-size: 0.85rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #475569;
            cursor: pointer;
        }

        .forgot-link {
            color: #2563EB;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            padding: 0.95rem;
            background-color: var(--color-navy);
            color: #FFFFFF;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(12, 43, 100, 0.25);
        }

        .btn-submit:hover {
            background-color: var(--color-navy-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(12, 43, 100, 0.35);
        }

        .demo-accounts {
            margin-top: 1.75rem;
            background-color: #EFF6FF;
            border: 1px dashed #BFDBFE;
            padding: 1rem;
            border-radius: 12px;
            font-size: 0.8rem;
            color: #1E40AF;
        }

        .demo-accounts strong {
            display: block;
            margin-bottom: 0.4rem;
        }

        @media (max-width: 850px) {
            .login-card {
                grid-template-columns: 1fr;
            }
            .login-banner {
                display: none;
            }
            .login-form-container {
                padding: 2.5rem 1.5rem;
            }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Left Hero Banner -->
        <div class="login-banner">
            <div class="banner-brand">
                <img src="{{ asset('images/adiprima-icon.svg') }}" alt="Logo PT Adiprima Suraprinta" style="width: 48px; height: 48px; border-radius: 10px; background: #FFFFFF; padding: 2px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <div class="banner-brand-text">
                    <h2>PT ADIPRIMA SURAPRINTA</h2>
                    <p>SURVEY CENTER &bull; JAWA POS GROUP</p>
                </div>
            </div>

            <div class="banner-headline">
                <h1>Pusat Pengelolaan Survei<br>PT Adiprima Suraprinta</h1>
                <p>Sistem informasi terpadu dalam memfasilitasi pelaksanaan, pemantauan, serta analisis berbagai kebutuhan riset perusahaan.</p>
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="login-form-container">
            <div class="form-header">
                <h2><i class="bi bi-shield-lock-fill" style="color: var(--color-navy); margin-right: 0.25rem;"></i> Login Admin</h2>
                <p>Masuk untuk mengakses panel administrasi Survey Center</p>
                <div style="background: #FEF3C7; border: 1px solid #FDE68A; color: #92400E; padding: 0.65rem 0.85rem; border-radius: 8px; font-size: 0.8rem; margin-top: 0.75rem; display: flex; align-items: center; gap: 0.4rem;">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Halaman ini <strong>khusus untuk Admin</strong>. Karyawan dapat langsung mengisi survei tanpa perlu login.</span>
                </div>
            </div>

            @if($errors->any())
                <div style="background-color: #FDE8E8; border: 1px solid #F8B4B4; color: #9B1C1C; padding: 0.85rem; border-radius: 10px; font-size: 0.85rem; margin-bottom: 1.25rem;">
                    <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="login">Username / NIK</label>
                    <div class="input-wrapper">
                        <i class="bi bi-person input-icon"></i>
                        <input type="text" id="login" name="login" class="form-control" placeholder="Masukkan username atau NIK Anda" value="{{ old('login') }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" style="accent-color: var(--color-navy);">
                        <span>Ingat saya</span>
                    </label>
                    <a href="#" class="forgot-link" onclick="alert('Silakan hubungi tim IT HRGA PT Adiprima Suraprinta untuk meriset password Anda.'); return false;">Lupa password?</a>
                </div>

                <button type="submit" class="btn-submit">
                    Masuk <i class="bi bi-arrow-right-short" style="font-size: 1.2rem; vertical-align: middle;"></i>
                </button>
            </form>

            <div class="demo-accounts">
                <strong><i class="bi bi-info-circle-fill"></i> Akun Login Admin (Demo):</strong>
                <div>• <strong>Admin HRGA:</strong> <code>admin</code> / Password: <code>password</code></div>
            </div>

            <div style="text-align: center; margin-top: 1.25rem;">
                <a href="{{ route('home') }}" style="color: #64748B; font-size: 0.85rem; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

</body>
</html>
