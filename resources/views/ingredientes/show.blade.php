<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Ingrediente · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/ingredientes.css'])
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

        <!-- Volver al Listado -->
        <nav aria-label="Navegación de retorno" class="medium-container">
            <a href="{{ route('ingredientes.index') }}" class="btn-back-nav">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Catálogo
            </a>
        </nav>

        <!-- Encabezado -->
        <header class="dashboard-header medium-container">
            <hgroup>
                <p class="eyebrow">Catálogo de Almacén</p>
                <h1 class="dashboard-title">{{ $ingrediente->nombre }}</h1>
                <p class="dashboard-description">Detalles del insumo y listado de preparaciones culinarias que lo requieren.</p>
            </hgroup>
        </header>

        <!-- Detalles del Ingrediente -->
        <section aria-label="Ficha de detalles del ingrediente">
            <article class="details-card">
                <section class="details-grid" aria-label="Datos técnicos del ingrediente">
                    <article class="detail-block">
                        <span class="detail-label">Nombre Comercial / Técnico</span>
                        <span class="detail-value">{{ $ingrediente->nombre }}</span>
                    </article>

                    <article class="detail-block">
                        <span class="detail-label">Unidad de Control en Receta</span>
                        <p style="margin: 0; padding: 0;">
                            <span class="unit-badge {{ $ingrediente->unidad }} show-badge">
                                {{ $ingrediente->unidad }}
                            </span>
                        </p>
                    </article>

                    <article class="detail-block">
                        <span class="detail-label">Categoría de Almacén</span>
                        <p style="margin: 0; padding: 0;">
                            <span style="font-size: 0.95rem; background: rgba(122, 40, 138, 0.1); color: var(--primary-purple); padding: 0.25rem 0.75rem; border-radius: 20px; font-weight: 800; border: 1px solid rgba(122, 40, 138, 0.2); display: inline-block;">
                                {{ $ingrediente->categoria ?? 'Abarrotes' }}
                            </span>
                        </p>
                    </article>

                    <article class="detail-block">
                        <span class="detail-label">Fecha de Alta en Sistema</span>
                        <span class="detail-text">{{ $ingrediente->created_at->format('d/m/Y \a \l\a\s H:i') }} hrs</span>
                    </article>

                    <article class="detail-block">
                        <span class="detail-label">Última Modificación</span>
                        <span class="detail-text">{{ $ingrediente->updated_at->format('d/m/Y \a \l\a\s H:i') }} hrs</span>
                    </article>
                </section>

                <!-- Lista Completa de Platillos -->
                <section class="associated-dishes-section" aria-label="Platillos vinculados">
                    <h3 class="associated-label associated-dishes-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Platillos que utilizan este insumo ({{ $ingrediente->platillos->count() }})
                    </h3>

                    @if($ingrediente->platillos->count() > 0)
                        <section class="dishes-grid" aria-label="Grid de recetas relacionadas">
                            @foreach($ingrediente->platillos as $platillo)
                                <article class="dish-card">
                                    <header>
                                        <h4>{{ $platillo->nombre }}</h4>
                                        <p>{{ $platillo->categoriaPlatillo?->nombre ?? 'Sin Categoría' }}</p>
                                    </header>
                                    <footer class="dish-card-footer">
                                        <a href="{{ route('platillos.show', $platillo->id) }}" class="btn-dish-link">
                                            Ver Ficha
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </footer>
                                </article>
                            @endforeach
                        </section>
                    @else
                        <article class="empty-dishes-state">
                            <p>Este ingrediente aún no se encuentra asignado a ninguna preparación.</p>
                        </article>
                    @endif
                </section>

                <!-- Botones de Acción de Ficha -->
                <footer class="form-actions">
                    <a href="{{ route('ingredientes.index') }}" class="btn-cancel">Volver</a>
                    <a href="{{ route('ingredientes.edit', $ingrediente->id) }}" class="btn-save" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
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
