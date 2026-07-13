<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/sucursales.css'])
    @livewireStyles
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>
    
    <main class="dashboard-layout">
        <menu class="top-nav" aria-label="Menú superior" style="padding: 0; margin: 0;">
            <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo">
            </a>
            <x-user-menu />
        </menu>

        <section style="max-width: 1200px; margin: 0 auto; width: 100%;">
            <a href="{{ route('dashboard') }}" class="btn-back-nav" style="background: rgba(255, 255, 255, 0.15); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.25); backdrop-filter: blur(4px); padding: 0.5rem 1.15rem; font-size: 0.9rem; margin-bottom: 0.5rem; transition: all 0.3s; display: inline-flex; align-items: center; gap: 0.5rem;" onmouseover="this.style.background='var(--accent-yellow)'; this.style.color='var(--primary-purple)'; this.style.borderColor='var(--accent-yellow)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'; this.style.color='#ffffff'; this.style.borderColor='rgba(255, 255, 255, 0.25)';">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 18px; height: 18px; transition: transform 0.3s;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Panel
            </a>
        </section>

        <header class="dashboard-header">
            <hgroup>
                <p class="eyebrow">Menús y Platillos</p>
                <h1 class="dashboard-title">Categorías</h1>
                <p class="dashboard-description">Administra las clasificaciones de alimentos en el entorno de pruebas para los contratos.</p>
            </hgroup>
        </header>

        <section class="sucursales-section" aria-label="Gestión de categorías">
            <livewire:categoria-manager />
        </section>
    </main>

    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Prototipo de Gestión de Eventos</p>
    </footer>

    @livewireScripts
</body>
</html>