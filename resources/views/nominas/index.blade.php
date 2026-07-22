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

        <header class="dashboard-header">
            <hgroup>
                <p class="eyebrow">Recursos Humanos</p>
                <h1 class="dashboard-title">Nóminas</h1>
                <p class="dashboard-description">Gestión de empleados, sueldos y horas extra para eventos.</p>
            </hgroup>
        </header>

        <section class="eventos-section" aria-label="Lista de Nóminas">
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
