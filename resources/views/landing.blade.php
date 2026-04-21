<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROMETE — Sistema de Gestión</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #0d0d0f;
            color: #e8e6e1;
            min-height: 100vh;
        }

        /* ── NAV ── */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 4rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            position: sticky;
            top: 0;
            background: #0d0d0f;
            z-index: 100;
        }

        .logo {
            font-family: 'Syne', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            color: #f5f3ee;
            text-transform: uppercase;
            text-decoration: none;
        }

        .logo span { color: #c9a96e; }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
        }

        .nav-links a {
            color: rgba(232,230,225,0.5);
            text-decoration: none;
            font-size: 0.82rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            transition: color 0.2s;
        }

        .nav-links a:hover { color: #c9a96e; }

        .nav-btn {
            background: #c9a96e;
            color: #0d0d0f;
            border: none;
            padding: 0.55rem 1.5rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.82rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            cursor: pointer;
            transition: opacity 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .nav-btn:hover { opacity: 0.85; }

        /* ── HERO ── */
        .hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
            padding: 7rem 4rem 6rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,169,110,0.07) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-tag {
            display: inline-block;
            font-size: 0.7rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #c9a96e;
            border: 1px solid rgba(201,169,110,0.3);
            padding: 0.3rem 1rem;
            margin-bottom: 2rem;
        }

        .hero h1 {
            font-family: 'Syne', sans-serif;
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -0.01em;
            color: #f5f3ee;
            margin-bottom: 1.5rem;
        }

        .hero h1 em {
            font-style: normal;
            color: #c9a96e;
        }

        .hero-desc {
            font-size: 1rem;
            color: rgba(232,230,225,0.5);
            line-height: 1.75;
            max-width: 440px;
            font-weight: 300;
            margin-bottom: 2.5rem;
        }

        .hero-btns {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-primary {
            background: #c9a96e;
            color: #0d0d0f;
            padding: 0.8rem 2.2rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            transition: opacity 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover { opacity: 0.85; }

        .btn-ghost {
            background: transparent;
            color: rgba(232,230,225,0.5);
            padding: 0.8rem 2.2rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            border: 1px solid rgba(255,255,255,0.1);
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            transition: border-color 0.2s, color 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-ghost:hover {
            border-color: rgba(255,255,255,0.25);
            color: #e8e6e1;
        }

        /* ── DASHBOARD MOCKUP ── */
        .dashboard-mock {
            background: #141416;
            border: 1px solid rgba(255,255,255,0.08);
            padding: 1.8rem;
        }

        .mock-topbar {
            display: flex;
            gap: 7px;
            margin-bottom: 1.4rem;
        }

        .mock-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
        }

        .mock-dot.gold { background: #c9a96e; }

        .mock-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.8rem;
            margin-bottom: 1.2rem;
        }

        .mock-stat {
            background: #1a1a1e;
            border: 1px solid rgba(255,255,255,0.05);
            padding: 1rem;
        }

        .mock-stat-label {
            font-size: 0.62rem;
            color: rgba(232,230,225,0.3);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.5rem;
        }

        .mock-stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 1.7rem;
            font-weight: 700;
            color: #f5f3ee;
        }

        .mock-stat-value.gold { color: #c9a96e; }

        .mock-table-header {
            display: flex;
            justify-content: space-between;
            padding: 0 0 0.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 0.62rem;
            color: rgba(232,230,225,0.3);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .mock-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.65rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            font-size: 0.8rem;
            color: #e8e6e1;
        }

        .mock-row:last-child { border-bottom: none; }

        .badge {
            font-size: 0.62rem;
            padding: 0.2rem 0.7rem;
            text-transform: uppercase;
        }

        .badge-green { background: rgba(99,153,34,0.15); color: #97c459; }
        .badge-amber { background: rgba(186,117,23,0.15); color: #EF9F27; }
        .badge-red   { background: rgba(180,40,40,0.15);  color: #e06060; }

        /* ── STATS BAR ── */
        .stats-bar {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border-top: 1px solid rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .stat-item {
            padding: 3rem 4rem;
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        .stat-item:last-child { border-right: none; }

        .stat-num {
            font-family: 'Syne', sans-serif;
            font-size: 2.8rem;
            font-weight: 800;
            color: #c9a96e;
            margin-bottom: 0.4rem;
        }

        .stat-label {
            font-size: 0.75rem;
            color: rgba(232,230,225,0.35);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        /* ── FEATURES ── */
        .features {
            padding: 6rem 4rem;
        }

        .section-label {
            font-size: 0.7rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #c9a96e;
            margin-bottom: 3.5rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.06);
        }

        .feature-card {
            background: #0d0d0f;
            padding: 2.8rem 2.5rem;
            transition: background 0.25s;
        }

        .feature-card:hover { background: #141416; }

        .feature-icon {
            width: 40px; height: 40px;
            background: rgba(201,169,110,0.08);
            border: 1px solid rgba(201,169,110,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.8rem;
            font-size: 16px;
        }

        .feature-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: #f5f3ee;
            margin-bottom: 0.8rem;
        }

        .feature-desc {
            font-size: 0.85rem;
            color: rgba(232,230,225,0.4);
            line-height: 1.7;
            font-weight: 300;
        }

        /* ── CTA ── */
        .cta-section {
            padding: 6rem 4rem;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .cta-section h2 {
            font-family: 'Syne', sans-serif;
            font-size: 3rem;
            font-weight: 800;
            color: #f5f3ee;
            margin-bottom: 1rem;
        }

        .cta-section p {
            color: rgba(232,230,225,0.4);
            margin-bottom: 2.5rem;
            font-size: 0.95rem;
            font-weight: 300;
        }

        /* ── FOOTER ── */
        footer {
            padding: 2rem 4rem;
            border-top: 1px solid rgba(255,255,255,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-copy {
            font-size: 0.72rem;
            color: rgba(232,230,225,0.2);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            nav { padding: 1.2rem 2rem; }
            .nav-links { display: none; }
            .hero { grid-template-columns: 1fr; padding: 4rem 2rem; gap: 3rem; }
            .hero h1 { font-size: 2.8rem; }
            .stats-bar { grid-template-columns: repeat(2, 1fr); }
            .stat-item { padding: 2rem; }
            .features { padding: 4rem 2rem; }
            .features-grid { grid-template-columns: 1fr; }
            .cta-section { padding: 4rem 2rem; }
            footer { padding: 1.5rem 2rem; flex-direction: column; gap: 0.75rem; text-align: center; }
        }
    </style>
</head>
<body>

    {{-- ── NAV ── --}}
    <nav>
        <a href="{{ url('/') }}" class="logo">Promet<span>e</span></a>
        <ul class="nav-links">
            <li><a href="#funcionalidades">Funcionalidades</a></li>
            <li><a href="#stats">Estadísticas</a></li>
            <li><a href="#contacto">Contacto</a></li>
        </ul>
        <a href="{{ route('login') }}" class="nav-btn">Acceder</a>
    </nav>

    {{-- ── HERO ── --}}
    <section class="hero">
        <div class="hero-content">
            <div class="hero-tag">Sistema de gestión integral</div>
            <h1>Control total.<br/>Sin <em>fricciones.</em></h1>
            <p class="hero-desc">
                Gestiona préstamos de materiales, inventario de productos y usuarios
                desde un único panel. Rápido, fiable y sin complicaciones.
            </p>
            <div class="hero-btns">
                <a href="{{ route('login') }}" class="btn-primary">Entrar al sistema</a>
                <a href="#funcionalidades" class="btn-ghost">Ver más</a>
            </div>
        </div>

        <div class="hero-visual">
            <div class="dashboard-mock">
                <div class="mock-topbar">
                    <div class="mock-dot gold"></div>
                    <div class="mock-dot"></div>
                    <div class="mock-dot"></div>
                </div>
                <div class="mock-stats">
                    <div class="mock-stat">
                        <div class="mock-stat-label">Materiales</div>
                        <div class="mock-stat-value">48</div>
                    </div>
                    <div class="mock-stat">
                        <div class="mock-stat-label">Préstamos</div>
                        <div class="mock-stat-value gold">12</div>
                    </div>
                    <div class="mock-stat">
                        <div class="mock-stat-label">Stock</div>
                        <div class="mock-stat-value">203</div>
                    </div>
                </div>
                <div class="mock-table-header">
                    <span>Material</span>
                    <span>Estado</span>
                </div>
                <div class="mock-row">
                    <span>Taladro Bosch 18V</span>
                    <span class="badge badge-green">Disponible</span>
                </div>
                <div class="mock-row">
                    <span>Sierra circular</span>
                    <span class="badge badge-amber">Prestado</span>
                </div>
                <div class="mock-row">
                    <span>Andamio mod. A</span>
                    <span class="badge badge-red">Mantenimiento</span>
                </div>
                <div class="mock-row">
                    <span>Nivel láser</span>
                    <span class="badge badge-green">Disponible</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ── STATS ── --}}
    <div class="stats-bar" id="stats">
        <div class="stat-item">
            <div class="stat-num">100%</div>
            <div class="stat-label">Trazabilidad</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">0</div>
            <div class="stat-label">Pérdidas de stock</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">3</div>
            <div class="stat-label">Módulos integrados</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">∞</div>
            <div class="stat-label">Escalabilidad</div>
        </div>
    </div>

    {{-- ── FEATURES ── --}}
    <section class="features" id="funcionalidades">
        <div class="section-label">Funcionalidades clave</div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">📦</div>
                <div class="feature-title">Gestión de materiales</div>
                <div class="feature-desc">
                    Registra, categoriza y asigna materiales a usuarios.
                    Historial completo de movimientos y préstamos en tiempo real.
                </div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🛒</div>
                <div class="feature-title">Control de productos</div>
                <div class="feature-desc">
                    Inventario siempre actualizado con control de stock.
                    Registro de transacciones por usuario, fecha y cantidad.
                </div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👥</div>
                <div class="feature-title">Roles y usuarios</div>
                <div class="feature-desc">
                    Sistema de roles diferenciados. Cada usuario accede
                    únicamente a lo que le corresponde según su perfil.
                </div>
            </div>
        </div>
    </section>

    {{-- ── CTA ── --}}
    <section class="cta-section" id="contacto">
        <h2>Listo para empezar</h2>
        <p>Accede al panel y toma el control de tu inventario ahora mismo.</p>
        <a href="{{ route('login') }}" class="btn-primary" style="font-size:1rem; padding:0.9rem 2.8rem;">
            Entrar al sistema
        </a>
    </section>

    {{-- ── FOOTER ── --}}
    <footer>
        <a href="{{ url('/') }}" class="logo" style="font-size:1rem;">Promet<span>e</span></a>
        <div class="footer-copy">Sistema de gestión interno &mdash; Todos los derechos reservados</div>
    </footer>

</body>
</html>