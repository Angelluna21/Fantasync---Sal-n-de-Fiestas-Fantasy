<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nóminas · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/eventos.css', 'resources/css/auth.css', 'resources/css/nominas.css'])
    @livewireStyles
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>
    
    <main class="dashboard-layout">
        <!-- Navegación superior -->
        <nav class="top-nav" aria-label="Menú superior">
            <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo">
            </a>

            <x-user-menu />
        </nav>

        <!-- Volver al Panel -->
        <nav class="eventos-back-nav">
            <a href="{{ route('dashboard') }}" class="btn-back-nav">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Panel
            </a>
        </nav>

        <section class="eventos-section" aria-label="Lista de Nóminas" style="margin-top: 2rem;">
            <header style="border-bottom: 2px solid rgba(122, 40, 138, 0.15); padding-bottom: 1.5rem; margin-bottom: 2rem; text-align: center;">
                <p class="eyebrow" style="color: var(--accent-magenta); margin-bottom: 0.2rem; font-size: 0.95rem; text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Recursos Humanos</p>
                <h1 style="color: var(--primary-purple); font-size: 2.5rem; font-weight: 800; margin: 0 0 0.5rem 0; line-height: 1.2;">Nóminas</h1>
                <p style="color: var(--text-main); font-size: 1.1rem; margin: 0 auto; max-width: 600px;">Gestión de empleados, sueldos y horas extra para eventos.</p>
            </header>

            @if(session('success'))
                <aside class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </aside>
            @endif

            <livewire:nomina-table />
        </section>
    </main>

    <footer class="dashboard-footer eventos-footer">
        <p>© 2026 FantaSync · Dashboard Administrativo</p>
    </footer>

    @livewireScripts
</body>
</html>
