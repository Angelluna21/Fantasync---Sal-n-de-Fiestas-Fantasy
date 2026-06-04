<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paso 2: Configurar Menú | FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/contract.css'])
    @livewireStyles
</head>
<body class="contract-page">
    <figure class="contract-background" aria-hidden="true"></figure>

    <main class="contract-layout">
        <nav class="top-nav" aria-label="Navegación del sistema">
            <a href="{{ route('dashboard') }}" aria-label="Volver al inicio" class="logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo">
            </a>
            <section style="display: flex; align-items: center; gap: 1.5rem;" aria-label="Acciones de navegación">
                <x-user-menu />
                <a href="{{ route('dashboard') }}" class="btn-back">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al Panel
                </a>
            </section>
        </nav>

        <header class="contract-header">
            <hgroup>
                <p class="eyebrow">Paso 2 / 2</p>
                <h1 class="contract-title">Selección de Menú y Comanda</h1>
                <p style="color: #6c757d; font-size: 1.1rem; margin-top: 0.5rem;">Asigna el menú para el evento de {{ $evento->nombre_festejado }} ({{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }})</p>
            </hgroup>
        </header>

        <section class="contract-card" style="margin-top: 2rem;">
            @livewire('contrato-menu-builder', ['eventoId' => $evento->id])
        </section>
    </main>

    @livewireScripts
</body>
</html>
