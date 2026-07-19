<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $categoria->nombre }} · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/platillos.css'])
</head>

<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>

    <main class="dashboard-layout">
        <nav class="top-nav" aria-label="Menú superior">
            <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo">
            </a>
            <x-user-menu />
        </nav>

        <nav aria-label="Navegación de retorno" class="platillos-section">
            <a href="{{ route('categorias.index') }}" class="btn-back-nav">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Categorías
            </a>
        </nav>

        <header class="dashboard-header">
            <hgroup>
                <p class="eyebrow">{{ $categoria->grupo ?? 'Sin grupo' }}</p>
                <h1 class="dashboard-title">{{ $categoria->nombre }}</h1>
                <p class="dashboard-description">Orden: {{ $categoria->orden }} · {{ $categoria->platillos->count() }} platillo(s) asociado(s)</p>
            </hgroup>
        </header>

        <section class="platillos-section" aria-label="Detalle de la categoría" style="max-width: 700px; margin: 0 auto;">
            <div class="platillo-card" style="padding: 2rem;">
                <menu class="card-actions-menu" style="justify-content: flex-end;">
                    <li>
                        <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn-action btn-edit" title="Editar Categoría">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <button type="button" class="btn-action btn-delete" title="Eliminar" onclick="document.getElementById('delete-form').submit()">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </li>
                </menu>

                <form id="delete-form" action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" style="display:none;"
                    onsubmit="return confirm('¿Eliminar permanentemente \'{{ $categoria->nombre }}\'?')">
                    @csrf
                    @method('DELETE')
                </form>

                <h4 class="associated-label" style="margin-top: 1.5rem;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2v10z"></path>
                    </svg>
                    Platillos en esta categoría
                </h4>

                @if($categoria->platillos->isEmpty())
                    <p class="no-platillos">Todavía no hay platillos en esta categoría.</p>
                @else
                    <menu class="platillos-list" aria-label="Platillos de esta categoría" style="flex-direction: column; gap: 8px; align-items: flex-start;">
                        @foreach($categoria->platillos as $platillo)
                            <li><span class="platillo-badge">{{ $platillo->nombre }}</span></li>
                        @endforeach
                    </menu>
                @endif
            </div>
        </section>
    </main>

    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
</body>

</html>