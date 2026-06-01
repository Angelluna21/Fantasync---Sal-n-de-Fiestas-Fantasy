<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salones · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/salones.css'])
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
                <p class="eyebrow">Gestión de Espacios</p>
                <h1 class="dashboard-title">Salones</h1>
                <p class="dashboard-description">Administra los salones disponibles, su capacidad y los eventos asociados a cada espacio.</p>
            </hgroup>
        </header>

        <!-- Sección de acciones -->
        <section class="salones-section" aria-label="Gestión de salones">
            <menu class="action-bar">
                <a href="{{ route('salones.create') }}" class="btn-create">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Crear Salón
                </a>
            </menu>

            <!-- Grid de salones -->
            <section class="salones-grid">
                @forelse($salones as $salon)
                    <article class="salon-card">
                        <header class="card-header">
                            <hgroup class="header-content">
                                <h2 class="salon-name">{{ $salon->nombre }}</h2>
                                @if($salon->alias)
                                    <span class="salon-alias">{{ $salon->alias }}</span>
                                @endif
                            </hgroup>
                            <span class="salon-badge">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </span>
                        </header>

                        <section class="card-body">
                            @if($salon->sucursal)
                                <section class="salon-info">
                                    <p class="info-label">Ubicación</p>
                                    <p class="info-value">{{ $salon->sucursal->nombre }}</p>
                                </section>
                            @endif

                            <section class="events-section">
                                <p class="events-label">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Eventos Asociados
                                </p>
                                @if($salon->eventos->count() > 0)
                                    <ul class="events-list">
                                        @foreach($salon->eventos as $evento)
                                            <li class="event-badge">{{ $evento->nombre }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="no-events">Sin eventos asignados</p>
                                @endif
                            </section>
                        </section>

                        <footer class="card-footer">
                            <a href="{{ route('salones.show', $salon->id) }}" class="btn-action btn-view" title="Ver">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <a href="{{ route('salones.edit', $salon->id) }}" class="btn-action btn-edit" title="Editar">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <button type="button" class="btn-action btn-delete" title="Eliminar" onclick="confirmDelete('{{ route('salones.destroy', $salon->id) }}', '{{ $salon->nombre }}')">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </footer>
                    </article>
                @empty
                    <section class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <h3>No hay salones registrados</h3>
                        <p>Crea un salón para comenzar a gestionar tus espacios.</p>
                        <a href="{{ route('salones.create') }}" class="btn-create-empty">Crear Primer Salón</a>
                    </section>
                @endforelse
            </section>
        </section>
    </main>

    <!-- Footer -->
    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
    <!-- Diálogo de Confirmación Customizado -->
    <dialog id="delete-confirm-dialog" class="delete-dialog">
        <section class="dialog-content">
            <figure class="dialog-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </figure>
            <h3 class="dialog-title">¿Eliminar Salón?</h3>
            <p class="dialog-message">¿Estás seguro de que deseas eliminar permanentemente el salón <strong id="delete-salon-name"></strong>? Esta acción no se puede deshacer y eliminará todos los eventos asociados.</p>
            
            <form id="delete-confirm-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <menu class="dialog-actions">
                    <button type="button" class="btn-dialog-cancel" onclick="closeDeleteDialog()">Cancelar</button>
                    <button type="submit" class="btn-dialog-confirm">Eliminar</button>
                </menu>
            </form>
        </section>
    </dialog>

    <script>
        const deleteDialog = document.getElementById('delete-confirm-dialog');
        const deleteForm = document.getElementById('delete-confirm-form');
        const deleteSalonNameEl = document.getElementById('delete-salon-name');

        function confirmDelete(url, salonName) {
            if (deleteDialog && deleteForm && deleteSalonNameEl) {
                deleteForm.action = url;
                deleteSalonNameEl.textContent = salonName;
                deleteDialog.showModal();
            }
        }

        function closeDeleteDialog() {
            if (deleteDialog) {
                deleteDialog.close();
            }
        }

        // Cerrar dialog haciendo clic en el backdrop
        if (deleteDialog) {
            deleteDialog.addEventListener('click', (e) => {
                const dialogDimensions = deleteDialog.getBoundingClientRect();
                if (
                    e.clientX < dialogDimensions.left ||
                    e.clientX > dialogDimensions.right ||
                    e.clientY < dialogDimensions.top ||
                    e.clientY > dialogDimensions.bottom
                ) {
                    deleteDialog.close();
                }
            });
        }
    </script>
</body>
</html>
