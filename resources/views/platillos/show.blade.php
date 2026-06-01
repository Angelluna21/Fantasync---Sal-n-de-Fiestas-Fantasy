<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $platillo->nombre }} · FantaSync</title>
    <meta name="description" content="Ficha técnica del platillo {{ $platillo->nombre }} — FantaSync">
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/platillos.css'])
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>

    <main class="dashboard-layout">
        <!-- Navegación superior -->
        <nav class="top-nav" aria-label="Menú superior">
            <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo">
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Cerrar sesión
                </button>
            </form>
        </nav>

        <!-- Volver al Listado -->
        <nav aria-label="Navegación de retorno" class="medium-container">
            <a href="{{ route('platillos.index') }}" class="btn-back-nav">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Catálogo
            </a>
        </nav>

        <!-- Encabezado -->
        <header class="dashboard-header medium-container">
            <hgroup>
                <p class="eyebrow">Administración de Menú</p>
                <h1 class="dashboard-title">{{ $platillo->nombre }}</h1>
                <p class="dashboard-description">Detalles de la preparación culinaria y listado de ingredientes involucrados en su fórmula.</p>
            </hgroup>
        </header>

        <!-- Detalles del Platillo -->
        <section aria-label="Ficha de detalles del platillo">
            <article class="details-card">
                <section class="details-grid" aria-label="Datos técnicos del platillo">
                    <article class="detail-block">
                        <span class="detail-label">Nombre del Platillo</span>
                        <span class="detail-value">{{ $platillo->nombre }}</span>
                    </article>

                    <article class="detail-block">
                        <span class="detail-label">Categoría de Menú</span>
                        @if($platillo->categoriaPlatillo)
                            <span class="badge">{{ $platillo->categoriaPlatillo->nombre }}</span>
                        @else
                            <span class="badge badge-gray">Sin Categoría</span>
                        @endif
                    </article>

                    <article class="detail-block">
                        <span class="detail-label">Porciones Base</span>
                        <span class="detail-value">{{ $platillo->porciones_base ?? 1 }} pz</span>
                    </article>

                    <article class="detail-block">
                        <span class="detail-label">Total de Insumos</span>
                        <span class="detail-value">{{ $platillo->ingredientes->count() }}</span>
                    </article>

                    @if($platillo->descripcion)
                    <article class="detail-block detail-full-width">
                        <span class="detail-label">Descripción</span>
                        <span class="detail-text">{{ $platillo->descripcion }}</span>
                    </article>
                    @endif
                </section>

                <!-- Fórmula / Ingredientes -->
                <section class="ingredients-section" aria-label="Fórmula de ingredientes">
                    <h3 class="associated-label ingredients-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Fórmula de Insumos Requeridos ({{ $platillo->ingredientes->count() }})
                    </h3>

                    @if($platillo->ingredientes->count() > 0)
                        <section class="ingredients-grid" aria-label="Grid de ingredientes de la receta">
                            @foreach($platillo->ingredientes as $ingrediente)
                                <article class="ingredient-card">
                                    <h4>{{ $ingrediente->nombre }}</h4>
                                    <span class="unit-badge {{ $ingrediente->unidad }}">{{ $ingrediente->unidad }}</span>
                                </article>
                            @endforeach
                        </section>
                    @else
                        <article class="empty-ingredients-state">
                            <p>Este platillo aún no cuenta con una receta o ingredientes asignados.</p>
                        </article>
                    @endif
                </section>

                <!-- Botones de Acción de Ficha -->
                <footer class="form-actions">
                    <a href="{{ route('platillos.index') }}" class="btn-cancel">Volver</a>
                    <a href="{{ route('platillos.edit', $platillo->id) }}" class="btn-save btn-edit-link">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Editar Ficha
                    </a>
                </footer>
            </article>
        </section>
    </main>

    <!-- Footer -->
    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
</body>
</html>
