<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Sucursal · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/sucursales.css'])
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

        <!-- Contenedor -->
        <section class="show-container">
            <a href="{{ route('sucursales.index') }}" class="btn-back-nav">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a la lista de sucursales
            </a>

            @if(session('success'))
                <aside role="alert" style="background-color: rgba(76, 175, 80, 0.15); color: #2e7d32; padding: 1rem 1.5rem; border-radius: 1rem; border: 1px solid rgba(76, 175, 80, 0.3); margin-bottom: 1.5rem; font-weight: 700;">
                    {{ session('success') }}
                </aside>
            @endif

            <!-- Tarjeta -->
            <article class="detail-card">
                <header class="detail-header">
                    <hgroup>
                        <h1 class="detail-title">{{ $sucursal->nombre }}</h1>
                        <span class="location-badge" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: var(--accent-yellow); color: var(--primary-purple); border-radius: 50%;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </span>
                    </hgroup>
                </header>

                <main class="detail-body">
                    <!-- Dirección -->
                    <section class="detail-section">
                        <h2 class="section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Ubicación / Dirección
                        </h2>
                        <p class="section-text">{{ $sucursal->direccion ?? 'Sin dirección registrada' }}</p>
                    </section>

                    <!-- Salones -->
                    <section class="detail-section">
                        <h2 class="section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Salones Registrados en esta Sucursal
                        </h2>
                        @if($sucursal->salones->count() > 0)
                            <section class="salons-show-grid">
                                @foreach($sucursal->salones as $salon)
                                    <article class="salon-show-item">
                                        <hgroup>
                                            <h3 class="salon-show-name">{{ $salon->nombre }}</h3>
                                            @if($salon->alias)
                                                <span class="salon-show-alias">{{ $salon->alias }}</span>
                                            @endif
                                        </hgroup>
                                        <a href="{{ route('salones.show', $salon->id) }}" style="margin-top: 1rem; font-size: 0.85rem; font-weight: 700; color: var(--primary-purple); text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
                                            Ver salón
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </article>
                                @endforeach
                            </section>
                        @else
                            <p style="margin: 0; color: #bcbcbc; font-style: italic;">Esta sucursal no tiene salones asignados actualmente.</p>
                        @endif
                        
                        <menu style="margin-top: 1rem; padding: 0;">
                            <a href="{{ route('salones.create', ['sucursal_id' => $sucursal->id]) }}" class="btn-add-salon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Crear Salón para esta Sucursal
                            </a>
                        </menu>
                    </section>

                    <!-- Acciones de Sucursal -->
                    <menu class="detail-actions">
                        <a href="{{ route('sucursales.edit', $sucursal->id) }}" class="btn-show-edit">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Editar Sucursal
                        </a>

                        <form action="{{ route('sucursales.destroy', $sucursal->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta sucursal permanentemente? Esto no eliminará los salones, pero quedarán sin sucursal asignada.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-show-delete">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Eliminar Sucursal
                            </button>
                        </form>
                    </menu>
                </main>
            </article>
        </section>
    </main>

    <footer class="page-footer" style="text-align: center; margin-top: 3rem; padding-bottom: 2rem; color: #8c8c8c; font-size: 0.9rem;">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
</body>
</html>
