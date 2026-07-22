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
            <section class="nav-actions" aria-label="Acciones de navegación">
                <x-user-menu />
                @php $contrato = \App\Models\Contrato::where('evento_id', $evento->id)->first(); @endphp
                @if($contrato)
                <a href="{{ route('contratos.edit', $contrato->id) }}" class="btn-back" style="background: rgba(255,255,255,0.2); margin-right: 10px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Editar Contrato
                </a>
                @endif
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
                <p class="contract-subtitle">Asigna el menú para el evento de {{ $evento->nombre_festejado }} ({{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }})</p>
            </hgroup>
        </header>

        <section class="contract-card contract-card-margin">
            @livewire('contrato-menu-builder', ['eventoId' => $evento->id])
        </section>
    </main>

    @livewireScripts
</body>
</html>
