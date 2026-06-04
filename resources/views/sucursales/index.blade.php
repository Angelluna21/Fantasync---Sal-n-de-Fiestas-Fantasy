<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucursales · FantaSync</title>
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

        <!-- Volver al Panel -->
        <nav style="max-width: 1200px; margin: 0 auto; width: 100%;">
            <a href="{{ route('dashboard') }}" class="btn-back-nav" style="background: rgba(255, 255, 255, 0.15); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.25); backdrop-filter: blur(4px); padding: 0.5rem 1.15rem; font-size: 0.9rem; margin-bottom: 0.5rem; transition: all 0.3s;" onmouseover="this.style.background='var(--accent-yellow)'; this.style.color='var(--primary-purple)'; this.style.borderColor='var(--accent-yellow)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'; this.style.color='#ffffff'; this.style.borderColor='rgba(255, 255, 255, 0.25)';">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 18px; height: 18px; transition: transform 0.3s;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Panel
            </a>
        </nav>

        <!-- Encabezado -->
        <header class="dashboard-header">
            <hgroup>
                <p class="eyebrow">Ubicaciones</p>
                <h1 class="dashboard-title">Sucursales</h1>
                <p class="dashboard-description">Administra las sucursales, ubicaciones y servicios gastronómicos disponibles para tus eventos.</p>
            </hgroup>
        </header>

        <!-- Sección de acciones -->
        <section class="sucursales-section" aria-label="Gestión de sucursales">
            <menu class="action-bar">
                <a href="{{ route('sucursales.create') }}" class="btn-create">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Crear Sucursal
                </a>
            </menu>

            <!-- Grid de sucursales (vistas como tarjetas) -->
            <section class="sucursales-grid">
                @forelse($sucursales as $sucursal)
                    <article class="sucursal-card">
                        <header class="card-header">
                            <h2 class="card-title">{{ $sucursal->nombre }}</h2>
                            <span class="location-badge">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </span>
                        </header>

                        <main class="card-body">
                            <p class="address">{{ $sucursal->direccion ?? 'Ubicación no especificada' }}</p>
                            
                            <section class="salons-section">
                                <p class="salons-label">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    Salones
                                </p>
                                @if($sucursal->salones->count() > 0)
                                    <article class="salons-list">
                                        @foreach($sucursal->salones as $salon)
                                            <span class="salon-badge">{{ $salon->nombre }}</span>
                                        @endforeach
                                    </article>
                                @else
                                    <p class="no-salons">Sin salones asignados</p>
                                @endif
                            </section>
                        </main>

                        <footer class="card-footer">
                            <a href="{{ route('sucursales.show', $sucursal->id) }}" class="btn-action btn-view" title="Ver">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <a href="{{ route('sucursales.edit', $sucursal->id) }}" class="btn-action btn-edit" title="Editar">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('sucursales.destroy', $sucursal->id) }}" method="POST" class="form-delete">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Eliminar" onclick="return confirm('¿Eliminar esta sucursal?')">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </footer>
                    </article>
                @empty
                    <section class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <h3>No hay sucursales registradas</h3>
                        <p>Crea una sucursal para comenzar a gestionar tus ubicaciones.</p>
                        <a href="{{ route('sucursales.create') }}" class="btn-create-empty">Crear Primera Sucursal</a>
                    </section>
                @endforelse
            </section>
        </section>
    </main>

    <!-- Footer -->
    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
</body>
</html>
